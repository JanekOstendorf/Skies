<?php

// Options
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

use skies\data\template\TemplateEngine;
use skies\system\page\SystemPages;
use skies\system\user\Session;
use skies\system\user\User;
use skies\system\language\Language;
use skies\system\style\Style;
use skies\system\template\Message;

use skies\util\Benchmark;
use skies\util\LanguageUtil;
use skies\util\PageUtil;
use skies\util\spyc;
use skies\util\SessionUtil;

/**
 * @author    Janek Ostendorf (ozzy) <ozzy2345de@gmail.com>
 * @copyright Copyright (c) Janek Ostendorf
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU General Public License, version 3
 * @package   skies.system
 */
class Skies {

	/**
	 * MySQL handler
	 *
	 * @var \skies\system\database\Database
	 */
	public static $db = null;

	/**
	 * Session handler
	 *
	 * @var \skies\system\user\Session
	 */
	public static $session = null;

	/**
	 * Current user
	 *
	 * @var \skies\system\user\User
	 */
	public static $user = null;

	/**
	 * Default language defined in the config file
	 *
	 * @var \skies\system\language\Language
	 */
	public static $defLanguage = null;

	/**
	 * Language of this user
	 *
	 * @var \skies\system\language\Language
	 */
	public static $language = null;

	/**
	 * Configuration array
	 *
	 * @var array<string|array>
	 */
	public static $config = null;

	/**
	 * Template Engine
	 *
	 * @var \skies\data\template\TemplateEngine
	 */
	public static $template = null;

	/**
	 * Currently used style
	 *
	 * @var \skies\system\style\Style
	 */
	public static $style = null;

	/**
	 * Array of Message objects
	 *
	 * @var \skies\system\template\Message[]
	 */
	public static $message = [];

	/**
	 * Current page
	 *
	 * @var \skies\data\Page
	 */
	public static $page = null;

	/**
	 * Initialize Skies
	 */
	public function __construct() {

		Benchmark::start();

		$this->initConfig();
		$this->initDb();
		$this->initSession();
		$this->initLanguage();
		$this->initStyle();
		$this->initTemplate();
		$this->initPage();

		$this->assignDefaults();
		self::$page->prepare();

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
	private function initDb() {

		// NULL values
		$dbHost = $dbUser = $dbPassword = $dbName = '';
		$dbPort = 0;
        $dbType = 'skies\system\database\MysqlDatabase';

		// Fetch configuration
		require_once ROOT_DIR.'/lib/config.inc.php';

		self::$db = new $dbType($dbHost, $dbUser, $dbPassword, $dbName, $dbPort);

        if(!self::$db instanceof \skies\system\database\Database || !self::$db->isSupported())
            throw new \skies\system\exception\SystemException('Failed to create database object.', 0, 'Failed to create Database object or database type is not supported.');

	}

	/**
	 * Initialize the session
	 */
	private function initSession() {

		// Do some clean ups
		\skies\util\SessionUtil::cleanUp();

		self::$session = new skies\system\user\Session();

		self::$user = self::$session->getUser();

	}

	/**
	 * Read the config
	 *
	 * @throws skies\system\exception\SystemException
	 */
	private function initConfig() {

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
	private function initLanguage() {

		self::$language = self::$defLanguage = LanguageUtil::getDefaultLanguage();

		// TODO: get this from user's data
		//self::$language = new \skies\system\language\Language((isset($_GET['lang']) ? $_GET['lang'] : 1));

	}

	/**
	 * Initialize the style
	 */
	private function initStyle() {

		// TODO: Get used style from user - if configured.
		self::$style = new Style(self::$config['defaultStyle']);

		/**#@+
		 * Message objects
		 *
		 * @var \skies\system\template\Message
		 */
		self::$message['error']   = new \skies\system\template\Message('error');
		self::$message['notice']  = new \skies\system\template\Message('notice');
		self::$message['success'] = new \skies\system\template\Message('success');
		/**#@-*/

	}

	/**
	 * Init the template engine and assign default values
	 */
	private function initTemplate() {

		self::$template = new TemplateEngine(ROOT_DIR.DIR_TPL, self::$style->getStylePath().'tpl/');

	}

	/**
	 * Initialize the current page
	 */
	private function initPage() {

		// Parse GET arguments
		if(isset($_GET['__0'])) {

			$args = explode('/', $_GET['__0']);

			$i = 0;

			foreach($args as $argument) {

				$_GET['_'.$i++] = $argument;

			}

		}

		$pageName = addslashes((isset($_GET['_0']) && !empty($_GET['_0']) ? $_GET['_0'] : self::$config['defaultPage']));

		// Fetch from the DB
		self::$page = PageUtil::getPage($pageName);

	}

	/**#@-*/

	private function assignDefaults() {


		// TODO: More arrays!
		self::$template->assign([
            'config' => self::$config,
		    'user' => self::$user,
		    'style' => self::$style,
		    'page' => self::$page,
		    'subdir' => SUBDIR
        ]);

	}

	/**
	 * Print everything!
	 */
	private function show() {

		// Get nav
		$nav = new \skies\system\navigation\Navigation(1);
		$nav->prepareNav();

		// Assign benchmark result
		self::$template->assign(['benchmarkTime' => Benchmark::getGenerationTime()]);

		self::$template->show('index.tpl');

	}

	/**
	 * Handle our Exceptions
	 *
	 * @static
	 *
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
	 * @param    integer        $errorNo
	 * @param    string         $message
	 * @param    string         $filename
	 * @param    integer        $lineNo
	 *
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
	 * @param     string        $className
	 *
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

}

?>
