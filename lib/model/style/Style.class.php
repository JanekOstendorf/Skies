<?php

namespace skies\model\style;

use skies\system\exception\SystemException;
use skies\system\template\ITemplateArray;
use skies\util\Spyc;

/**
 * @author    Janek Ostendorf (ozzy) <ozzy2345de@gmail.com>
 * @copyright Copyright (c) Janek Ostendorf
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU General Public License, version 3
 * @package   skies.system.style
 */
class Style implements ITemplateArray {

	/**
	 * Template's short name
	 *
	 * @var string
	 */
	protected $name = '';

	/**
	 * Template title
	 *
	 * @var string
	 */
	protected $title = '';

	/**
	 * List of Cascading Stylesheets to include
	 *
	 * @var array
	 */
	protected $cssFiles = [];

	/**
	 * CSS file for error pages
	 *
	 * @var string
	 */
	protected $errorCss = '';

	/**
	 * List of JavaScript files to include
	 *
	 * @var array
	 */
	protected $jsFiles = [];

	/**
	 * Custom template directory
	 *
	 * @var string
	 */
	protected $templateDir = '';

	/**
	 * StyleScript
	 *
	 * @var StyleScript|null
	 */
	protected $styleScript = null;

	/**
	 * Meta information
	 *
	 * @var array
	 */
	protected $meta = [];

	/**
	 * Content of our style.yml
	 *
	 * @var array
	 */
	protected $config = [];

	/**
	 * Init a style
	 *
	 * @param string $name Short name of the style
	 * @throws \skies\system\exception\SystemException
	 */
	public function __construct($name) {

		if(!file_exists(ROOT_DIR.DIR_STYLE.$name.'/style.yml')) {
			throw new SystemException('Failed to load style '.$name.'!', 0, 'Failed to find the configuration file of the style "'.$name.'".');
		}

		// Open config file
		$this->config = Spyc::YAMLLoad(ROOT_DIR.DIR_STYLE.$name.'/style.yml');

		if($this->config === false) {
			throw new SystemException('Failed to load style '.$name.'!', 0, 'Failed to read the configuration file of the style "'.$name.'".');
		}

		$this->cssFiles = isset($this->config['cssFiles']) ? $this->config['cssFiles'] : [];
		$this->jsFiles = isset($this->config['jsFiles']) ? $this->config['jsFiles'] : [];
		$this->templateDir = empty($this->config['templateDir']) ? null : $this->config['templateDir'];
		$this->meta = $this->config['meta'];

		$this->name = $this->config['name'];
		$this->title = $this->config['title'];
		$this->errorCss = $this->config['errorCss'];

		// Check for a style script
		if(isset($this->config['styleScript']) && !empty($this->config['styleScript'])) {

			$styleScriptClass = 'skies\style\\'.$this->name.'\\'.$this->config['styleScript'];
			$this->styleScript = new $styleScriptClass($this);

		}

	}

	/**
	 * Executes custom style code
	 */
	public function prepare() {

		if($this->styleScript instanceof StyleScript) {
			$this->styleScript->prepare();
		}

	}

	/**
	 * Get the URL path to this style directory
	 *
	 * @return string URL to this style directory
	 */
	public function getStyleDirUrl() {

		return SUBDIR.'style/'.$this->name.'/';

	}

	/**
	 * Get the absolute path to this style directory
	 *
	 * @return string Absolute path to the style directory
	 */
	public function getStylePath() {

		return ROOT_DIR.'style/'.$this->name.'/';

	}

	/**
	 * Get additional JS-files for this style
	 *
	 * @return array
	 */
	public function getJsFiles() {

		return $this->jsFiles;

	}

	/**
	 * Get CSS-files for this style
	 *
	 * @return array
	 */
	public function getCssFiles() {

		return $this->cssFiles;

	}

	/**
	 * Get the config of this style
	 *
	 * @return array
	 */
	public function getConfig() {

		return $this->config;

	}

	/**
	 * Get the error CSS-file
	 *
	 * @return string
	 */
	public function getErrorCss() {

		return $this->errorCss;

	}

	/**
	 * Get the path to the custom template directory
	 *
	 * @return null|string
	 */
	public function getTemplatePath() {

		if($this->templateDir != null) {
			return $this->getStylePath().$this->templateDir;
		}

		return null;

	}

	/**
	 * Get an array suitable for assignment
	 *
	 * @return array
	 */
	public function getTemplateArray() {

		return [
			'name' => $this->name,
			'title' => $this->title,
			'cssFiles' => $this->cssFiles,
			'jsFiles' => $this->jsFiles,
			'meta' => $this->meta,
			'config' => $this->config,
			'dir' => $this->getStyleDirUrl(),
			'errorCss' => $this->getErrorCss(),
			'object' => $this
		];

	}
}

?>
