<?php

namespace skies\system\template;

/**
 * @author    Janek Ostendorf (ozzy) <ozzy2345de@gmail.com>
 * @copyright Copyright (c) Janek Ostendorf
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU General Public License, version 3
 * @package   skies.system.template
 */
class Template {

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
	 * Main template file (usually php)
	 *
	 * @var string
	 */
	protected $mainFile = '';

	/**
	 * List of Cascading Stylesheets to include
	 *
	 * @var array<string>
	 */
	protected $cssFiles = [];

	/**
	 * List of JavaScript files to include
	 *
	 * @var array<string>
	 */
	protected $jsFiles = [];

	/**
	 * Meta information
	 *
	 * @var array<string|array>
	 */
	protected $meta = [];

	/**
	 * Content of our template.yml
	 *
	 * @var array<string|array>
	 */
	protected $config = [];

	/**
	 * Init a template
	 *
	 * @param string $name Short name of the template
	 *
	 * @throws \skies\system\exception\SystemException
	 */
	public function __construct($name) {

		if(!file_exists(ROOT_DIR.'/template/'.$name.'/template.yml')) {
			throw new \skies\system\exception\SystemException('Failed to load template '.$name.'!', 0, 'Failed to find the configuration file of the template "'.$name.'".');
		}

		// Open config file
		$this->config = \skies\util\Spyc::YAMLLoad(ROOT_DIR.'/template/'.$name.'/template.yml');

		if($this->config === false) {
			throw new \skies\system\exception\SystemException('Failed to load template '.$name.'!', 0, 'Failed to read the configuration file of the template "'.$name.'".');
		}

		$this->cssFiles = explode(', ', $this->config['css_files']);
		$this->jsFiles  = explode(', ', $this->config['js_files']);
		$this->mainFile = $this->config['main_file'];
		$this->meta     = $this->config['meta'];

		$this->name  = $this->config['name'];
		$this->title = $this->config['title'];

	}

	/**
	 * Prints (includes) the main template and shows the HTML stuff
	 */
	public function printTemplate() {

		include $this->getTemplatePath().'/'.$this->mainFile;

	}

	/**
	 * Get the URL path to this template directory
	 *
	 * @return string URL to this template directory
	 */
	public function getTemplateDirURL() {

		return \Skies::$config['subdir'].'/template/'.$this->name;

	}

	/**
	 * Get the absolute path to this template directory
	 *
	 * @return string Absolute path to the template directory
	 */
	public function getTemplatePath() {

		return ROOT_DIR.'/template/'.$this->name;

	}

	/**
	 * Print the HTML lines for including JS and CSS files
	 *
	 * @param int $indent Number of spaces to add as an indent
	 */
	public function printIncludes($indent = 0) {

		// Buffer for output
		$buffer = '';

		// Indent string
		$indent_str = \skies\util\StringUtil::getIndent($indent);

		// CSS
		foreach($this->cssFiles as $css_file) {

			// Local or http?
			if(preg_match('/^http[s]{0,1}\:\/\//', $css_file) == 0) {
				$css_file = $this->getTemplateDirURL().'/'.$css_file;
			}

			$buffer .= $indent_str.'<link rel="stylesheet" type="text/css" href="'.$css_file.'" />'."\n";

		}

		$buffer .= "\n";

		// JS
		foreach($this->jsFiles as $js_file) {

			// Local or http?
			if(preg_match('/^http[s]{0,1}\:\/\//', $js_file) == 0) {
				$js_file = $this->getTemplateDirURL().'/'.$js_file;
			}

			$buffer .= $indent_str.'<script src="'.$js_file.'"></script>'."\n";

		}

		echo $buffer."\n";


	}

	/**
	 * Include favorite icon
	 *
	 * @param int $indent Indent to prepend
	 */
	public function printFavicon($indent = 0) {

		// Indent string
		$indent_str = \skies\util\StringUtil::getIndent($indent);

		echo $indent_str.'<link rel="shortcut icon" type="'.\skies\util\StringUtil::encodeHtml($this->config['fav_mime']).'" href="'.SUBDIR.'/images/'.$this->config['favicon'].'" />'."\n";

	}

	/**
	 * Print meta data
	 *
	 * @param int $indent
	 */
	public function printMeta($indent = 0) {

		// Indent string
		$indent_str = \skies\util\StringUtil::getIndent($indent);

		$buffer = $indent_str.'<meta http-equiv="content-type" content="text/html; charset=UTF-8">'."\n";

		foreach(\Skies::$config['meta'] as $key => $content) {

			if($key == 'title') {
				continue;
			}

			$buffer .= $indent_str.'<meta name="'.\skies\util\StringUtil::encodeHtml($key).'" content="'.\skies\util\StringUtil::encodeHtml($content).'" />'."\n";

		}

		echo $buffer;

	}

	/**
	 * Print the title tag
	 *
	 * @param int $indent Number of spaces to prepend
	 */
	public function printTitle($indent = 0) {

		// Indent string
		$indent_str = \skies\util\StringUtil::getIndent($indent);

		echo $indent_str.'<title>'.\skies\util\StringUtil::encodeHtml(\Skies::$config['meta']['title']).' &middot; '.\skies\util\StringUtil::encodeHtml(\Skies::$page->getTitle()).'</title>'."\n";

	}

	/**
	 * Prints the content
	 */
	public function printContent() {

		return \Skies::$page->show();

	}

}

?>
