<?php
/**
 * @author    Janek Ostendorf (ozzy) <ozzy2345de@gmail.com>
 * @copyright Copyright (c) 2013 Janek Ostendorf
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU General Public License, version 3
 */

namespace skies\system\template;

use Smarty;

require_once ROOT_DIR.'lib/smarty/Smarty.class.php';
/**
 * Template engine. Wrapper for smarty
 */

class TemplateEngine {

	/**
	 * @var \Smarty
	 */
	protected $smarty = null;

	/**
	 * Our assigned variables
	 *
	 * @var array
	 */
	protected $vars = [];

	/**
	 * @param string $tplDir   Main template directory
	 * @param string $styleDir Style dependant templates
	 */
	public function __construct($tplDir, $styleDir = '') {

		$this->smarty = new Smarty();

		$this->smarty->setTemplateDir($styleDir ? [$styleDir, $tplDir] : $tplDir);
		$this->smarty->setPluginsDir([

			// Smarty plugins
			ROOT_DIR.'lib/smarty/plugins/',
			ROOT_DIR.'lib/smarty/sysplugins/',
			// Skies plugins
			ROOT_DIR.'lib/system/smartyPlugins/'

		]);
		$this->smarty->setCompileDir(DIR_CACHE.'template/');
		$this->smarty->setCacheDir(ROOT_DIR.DIR_CACHE); //self::Config('system.cache.dir')

	}

	/**
	 * Assign template variables
	 *
	 * @param array $data Data to be assigned
	 */
	public function assign($data) {

		$this->vars = array_merge($this->vars, $data);
		$this->smarty->assign($data);

	}

	/**
	 * Show a template
	 *
	 * @param string $templateName Template to show
	 */
	public function show($templateName) {

		$this->smarty->display($templateName);

	}

	/**
	 * Parses a template and returns it
	 *
	 * @param string $templateName Template to parse
	 * @return string
	 */
	public function parse($templateName) {

		return $this->smarty->fetch($templateName);

	}

	public function flushCache() {

		$this->smarty->clearAllCache();

	}

	public function getVars() {

		return $this->vars;

	}

}
