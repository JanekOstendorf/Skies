<?php

namespace skies\system\language;

use skies\model\template\ITemplateArray;
use skies\system\exception\SystemException;
use skies\util\Spyc;

/**
 * @author    Janek Ostendorf (ozzy) <ozzy2345de@gmail.com>
 * @copyright Copyright (c) Janek Ostendorf
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU General Public License, version 3
 * @package   skies.system.language
 */
class Language implements ITemplateArray {

	/**
	 * Language ID
	 *
	 * @var string
	 */
	protected $id = '';

	/**
	 * Detailed name
	 *
	 * @var string
	 */
	protected $title = '';

	/**
	 * Description
	 *
	 * @var string
	 */
	protected $description = '';

	/**
	 * Array holding all the data
	 *
	 * @var array
	 */
	protected $data = [];

	/**
	 * Default language?
	 *
	 * @var bool
	 */
	protected $default = false;

	/**
	 * Language config file (YAML array)
	 *
	 * @var array
	 */
	protected $configFile = [];

	/**
	 * Did we fall back to the default langauge somewhere?
	 *
	 * @var bool
	 */
	protected static $fallbackUsed = false;

	/**
	 * Hm, what do you think this __construct does ... coffee?
	 * @param string $id      Language identifer
	 * @param bool   $loadVars Shall we load the language vars?
	 * @param bool   $default Is this the default language?
	 * @throws \skies\system\exception\SystemException
	 */
	public function __construct($id, $loadVars = true, $default = false) {

		// Blah blah
		$this->default = $default;
		$this->id      = $id;

		// Parse config file
		if(!file_exists($this->getDirectoryPath()))
			throw new SystemException('Failed to load language "'.$this->id.'": The language directory does not exist.');

		if(!file_exists($this->getDirectoryPath().'language.yml'))
			throw new SystemException('Failed to load language "'.$this->id.'": The language config file does not exist.');

		$this->configFile = Spyc::YAMLLoad($this->getDirectoryPath().'language.yml')['language'];

		// Load the vars
		if($loadVars) {
			foreach($this->configFile['files'] as $curFile) {

				$curFileVars = Spyc::YAMLLoad($this->getDirectoryPath().$curFile);

				// Remove the ending and save the vars
				$superVar = pathinfo($this->getDirectoryPath().$curFile, PATHINFO_FILENAME);
				$this->data[$superVar] = $curFileVars;

			}
		}

		$this->title = $this->configFile['title'];
		$this->description = $this->configFile['description'];

	}

	public function get($var, $userVars = []) {

		if(explode('.', $var)[0] == 'config') {
			$varData = $this->replaceVars($this->getConfig($var), $userVars);
		}
		else {
			$varData = $this->replaceVars($this->getVar($var), $userVars);
		}

		if($varData === null) {

			// Fall back to the default language
			if(!$this->default) {
				$varData = \Skies::getDefaultLanguage()->get($var, $userVars);
				self::$fallbackUsed = true;
			}
			else
				$varData = '{{'.$var.'}}';
		}

		return $varData;

	}

	protected function getVar($var) {

		// Explode
		$var_arr = explode('.', $var);

		// temporary array
		$tmp = $this->data;

		// Try to get the string, recurse deeper and deeper ...
		foreach($var_arr as $cur) {

			if(isset($tmp[$cur])) {
				$tmp = $tmp[$cur];
			}
			else {
				$tmp = null;
				break;
			}
		}

		return $tmp;

	}

	protected function getConfig($var) {

		// Explode
		$var_arr = explode('.', $var);

		// Remove the 'config' from the start
		$var_arr = array_slice($var_arr, 1);

		// temporary array
		$tmp = \Skies::getConfig();

		// Try to get the string, recurse deeper and deeper ...
		foreach($var_arr as $cur) {

			if(isset($tmp[$cur])) {
				$tmp = $tmp[$cur];
			}
			else {
				$tmp = null;
				break;
			}
		}

		return $tmp;

	}


	public function replaceVars($varData, $userVars = []) {

		$matches = [];

		// Language vars ({}-braces)
		if(preg_match_all('/\{\{[a-zA-Z0-9\-\_\.]+\}\}/', $varData, $matches) > 0) {

			foreach($matches[0] as $tag) {

				$varName = substr($tag, 2, strlen($tag) - 4);

				$varData = str_replace($tag, $this->get($varName), $varData);

			}

		}

		$matches = [];

		// Constants (upper case, normal braces)
		if(preg_match_all('/\(\([A-Z0-9\.\-\_]+\)\)/', $varData, $matches) > 0) {

			foreach($matches[0] as $tag) {

				$constName = substr($tag, 2, strlen($tag) - 4);

				if(defined($constName)) {
					$varData = str_replace($tag, constant($constName), $varData);
				}

			}

		}

		if(!empty($userVars)) {

			$matches = [];

			if(preg_match_all('/\[\[[a-zA-Z0-9\.\-\_]+\]\]/', $varData, $matches) > 0) {

				foreach($matches[0] as $tag) {

					$varName = substr($tag, 2, strlen($tag) - 4);

					if(isset($userVars[$varName])) {
						$varData = str_replace($tag, $this->replaceVars($userVars[$varName]), $varData);
					}

				}

			}

		}

		return $varData;

	}

	/**
	 * @return array
	 */
	public function getTemplateArray() {

		return [
			'id' => $this->id,
			'title' => $this->title,
			'description' => $this->description,
			'default' => $this->default,
			'object' => $this
		];

	}

	/**
	 * Get the path to the languages directory
	 *
	 * @return string
	 */
	public function getDirectoryPath() {

		return ROOT_DIR.DIR_LANGUAGE.$this->id.'/';

	}

	/**
	 * Did we use the fallback language (the default one)
	 *
	 * @return bool
	 */
	public static function getFallbackUsed() {

		return self::$fallbackUsed;

	}

}

?>
