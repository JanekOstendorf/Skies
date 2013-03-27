<?php

// Options

use skies\model\style\Style;
use skies\model\template\Notification;
use skies\model\template\TemplateEngine;
use skies\system\language\Language;
use skies\system\user\Session;
use skies\system\user\User;
use skies\util\Benchmark;
use skies\util\LanguageUtil;
use skies\util\PageUtil;
use skies\util\SessionUtil;
use skies\util\spyc;

require_once ROOT_DIR.'/options.inc.php';

// Core functions
require_once ROOT_DIR.'/lib/system_functions.inc.php';

// Performance reasons ...
define('NOW', time());

// set exception handler
set_exception_handler(['\Skies', 'handleException']);

// set php error handler
if(DEBUG) {
	error_reporting(E_ALL);
}
else {
	error_reporting(E_ALL ^ E_NOTICE);
}

set_error_handler(['\Skies', 'handleError'], E_ALL);

// set autoload handler
spl_autoload_register(['\Skies', 'autoload']);

/*
 * Includes
 */

/**
 * @author    Janek Ostendorf (ozzy) <ozzy2345de@gmail.com>
 * @copyright Copyright (c) Janek Ostendorf
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU General Public License, version 3
 * @package   skies.system
 */
class Skies {

	/**
	 * Is Skies in the api mode?
	 *
	 * @var bool
	 */
	private static $api = false;

	/**
	 * MySQL handler
	 *
	 * @var \skies\system\database\Database
	 */
	private static $db = null;

	/**
	 * Session handler
	 *
	 * @var \skies\system\user\Session
	 */
	private static $session = null;

	/**
	 * Current user
	 *
	 * @var \skies\system\user\User
	 */
	private static $user = null;

	/**
	 * Default language defined in the config file
	 *
	 * @var \skies\system\language\Language
	 */
	private static $defaultLanguage = null;

	/**
	 * Language of this user
	 *
	 * @var \skies\system\language\Language
	 */
	private static $language = null;

	/**
	 * Configuration array
	 *
	 * @var array<string|array>
	 */
	private static $config = null;

	/**
	 * Template Engine
	 *
	 * @var \skies\model\template\TemplateEngine
	 */
	private static $template = null;

	/**
	 * Currently used style
	 *
	 * @var \skies\model\style\Style
	 */
	private static $style = null;

	/**
	 * Array of Message objects
	 *
	 * @var \skies\model\template\Notification
	 */
	private static $notification = null;

	/**
	 * Current page
	 *
	 * @var \skies\model\Page
	 */
	private static $page = null;

	/**
	 * Initialize Skies
	 *
	 * @param bool $api Is Skies in the API mode?
	 */
	public final function __construct($api = false) {

		Benchmark::start();

		self::$api = ($api == true);

		$this->initConfig();
		$this->initDb();
		$this->initSession();
		$this->initLanguage();
		$this->initStyle();
		$this->initTemplate();
		$this->initPage();

		self::$page->prepare();

		$this->assignDefaults();

		$this->show();

	}

	/**#@+
	 * Init function
	 */

	/**
	 * Connect to the MySQL server
	 *
	 * @throws \skies\system\exception\SystemException
	 */
	private final function initDb() {

		// NULL values
		$dbHost = $dbUser = $dbPassword = $dbName = '';
		$dbPort = 0;
		$dbType = 'skies\system\database\MysqlDatabase';

		// Fetch configuration
		require_once ROOT_DIR.'/lib/config.inc.php';

		self::$db = new $dbType($dbHost, $dbUser, $dbPassword, $dbName, $dbPort);

		if(!self::$db instanceof \skies\system\database\Database || !self::$db->isSupported()) {
			throw new \skies\system\exception\SystemException('Failed to create database object.', 0, 'Failed to create Database object or database type is not supported.');
		}

	}

	/**
	 * Initialize the session
	 */
	private final function initSession($clean = true) {

		// Do some clean ups
		if($clean) {
			\skies\util\SessionUtil::cleanUp();
		}

		self::$session = new skies\system\user\Session();

		self::updateUser();

	}

	/**
	 * Read the config
	 *
	 * @throws skies\system\exception\SystemException
	 */
	private final function initConfig() {

		self::$config = \skies\util\Spyc::YAMLLoad(ROOT_DIR.'config/config.yml');

		if(isset(self::$config[0]) && self::$config[0] == ROOT_DIR.'config/config.yml') {
			throw new \skies\system\exception\SystemException('Failed to open config file!', 0, 'Failed to open required config file.');
		}

		date_default_timezone_set(self::$config['defaultTimezone']);

		/**#@+
		 * Config dependant constants
		 */

		/**
		 * Subdirectory for relative paths (mainly URLs)
		 */
		define('SUBDIR', self::$config['subdir']);

		/**#@-*/
	}

	/**
	 * Initialize the language objects
	 */
	private final function initLanguage() {

		// Default language
		self::$language = self::$defaultLanguage = LanguageUtil::getDefaultLanguage();

		if(self::$user->getData('language') !== null) {
			self::$language = new \skies\system\language\Language(self::$user->getData('language'), true);
		}

	}

	/**
	 * Initialize the style
	 */
	private final function initStyle() {

		// TODO: Get used style from user - if configured.
		self::$style = new Style(self::$config['defaultStyle']);

		// Notification
		self::$notification = new Notification([
			Notification::ERROR => 'error',
			Notification::NOTICE => 'notice',
			Notification::SUCCESS => 'success',
			Notification::WARNING => 'warning'
		]);

	}

	/**
	 * Init the template engine and assign default values
	 */
	private final function initTemplate() {

		self::$template = new TemplateEngine(ROOT_DIR.DIR_TPL, self::$style->getTemplatePath() ? : '');

		if(isset($_GET['flushCache'])) {
			self::$template->flushCache();
		}

	}

	/**
	 * Initialize the current page
	 */
	private final function initPage() {

		// Parse GET arguments
		if(isset($_GET['__0'])) {

			// Remove prefixed slash
			$_GET['__0'] = ltrim($_GET['__0'], '/');
			$args = explode('/', $_GET['__0']);

			$i = 0;

			foreach($args as $argument) {

				$_GET['_'.$i++] = $argument;

			}

		}

		$pageName = addslashes((isset($_GET['_0']) && !empty($_GET['_0']) ? $_GET['_0'] : self::$config['defaultPage']));

		// Fetch from the DB
		self::$page = PageUtil::getPageFromUrl($args);

	}

	/**#@-*/

	private final function assignDefaults() {

		self::$template->assign([

			// Config
			'config' => self::$config,

			// User
			'user' => self::$user->getTemplateArray(),

			// Current style
			'style' => self::$style->getTemplateArray(),

			// Current page
			'page' => self::$page->getTemplateArray(),

			// Navigation
			'nav' => (new \skies\system\navigation\Navigation(1))->getTemplateArray(),

			// Language
			'language' => self::$language->getTemplateArray(),
			'defaultLanguage' => self::$defaultLanguage->getTemplateArray(),

			// Subdirectory for URLs
			'subdir' => SUBDIR,

			// Time
			'now' => NOW,
		]);

	}

	/**
	 * Print everything!
	 */
	private final function show() {

		// Assign benchmark result
		self::$template->assign(['benchmarkTime' => Benchmark::getGenerationTime()]);
		self::$notification->assign();

		if(self::isApiMode()) {
			print json_encode(self::$template->getVars());
		}
		else {
			self::$template->show('index.tpl');
		}

	}

	/**
	 * Update the global user
	 */
	public static final function updateUser() {

		if(self::$user !== null) {
			self::$user->update();
		}

		self::$user = self::$session->getUser();

	}

	/**
	 * Handle our Exceptions
	 *
	 * @static
	 * @param \Exception $e
	 */
	public static final function handleException(\Exception $e) {

		if($e instanceof skies\system\exception\SystemException) {

			$e->show();
			exit;

		}

		// repack Exception
		self::handleException(new skies\system\exception\SystemException($e->getMessage(), $e->getCode(), '', $e));
	}

	/**
	 * Catches php errors and throws instead a system exception.
	 *
	 * @param    integer $errorNo
	 * @param    string  $message
	 * @param    string  $filename
	 * @param    integer $lineNo
	 * @throws skies\system\exception\SystemException
	 */
	public static final function handleError($errorNo, $message, $filename, $lineNo) {

		if(error_reporting() != 0) {
			$type = 'error';
			switch($errorNo) {
				case 2:
					$type = 'warning';
					break;
				case 8:
					$type = 'notice';
					break;
			}

			throw new skies\system\exception\SystemException('PHP '.$type.' in file '.$filename.' ('.$lineNo.'): '.$message, 0);
		}
	}

	/**
	 * Includes the required util or exception classes automatically.
	 *
	 * @param     string $className
	 * @see        spl_autoload_register()
	 */
	public static final function autoload($className) {

		$namespaces = explode('\\', $className);

		// Is it a valid import?
		if(array_shift($namespaces) == 'skies') {

			$classPath = ROOT_DIR.'lib/'.implode('/', $namespaces).'.class.php';

			if(file_exists($classPath)) {
				require_once($classPath);
			}

		}

	}

	/**
	 * Is Skies in debug mode?
	 *
	 * @return bool
	 */
	public static final function isDebugMode() {

		return (DEBUG == true);

	}

	/**
	 * Is Skies in the API mode?
	 *
	 * @return bool
	 */
	public static final function isApiMode() {

		return (self::$api == true);

	}

	/**
	 * @return array
	 */
	public final static function getConfig() {

		return self::$config;

	}

	/**
	 * @return \skies\system\database\Database
	 */
	public final static function getDb() {

		return self::$db;

	}

	/**
	 * @return \skies\system\language\Language
	 */
	public final static function getDefaultLanguage() {

		return self::$defaultLanguage;

	}

	/**
	 * @return \skies\system\language\Language
	 */
	public final static function getLanguage() {

		return self::$language;

	}

	/**
	 * @return \skies\model\template\Notification
	 */
	public final static function getNotification() {

		return self::$notification;

	}

	/**
	 * @return \skies\model\Page
	 */
	public final static function getPage() {

		return self::$page;

	}

	/**
	 * @return \skies\system\user\Session
	 */
	public final static function getSession() {

		return self::$session;

	}

	/**
	 * @return \skies\model\style\Style
	 */
	public final static function getStyle() {

		return self::$style;

	}

	/**
	 * @return \skies\model\template\TemplateEngine
	 */
	public final static function getTemplate() {

		return self::$template;

	}

	/**
	 * @return \skies\system\user\User
	 */
	public final static function getUser() {

		return self::$user;

	}

}

?>
