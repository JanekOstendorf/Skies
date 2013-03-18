<?php

namespace skies\system\language;

/**
 * @author    Janek Ostendorf (ozzy) <ozzy2345de@gmail.com>
 * @copyright Copyright (c) Janek Ostendorf
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU General Public License, version 3
 * @package   skies.system.language
 */
class Language {

	/**
	 * Language ID
	 *
	 * @var int
	 */
	protected $id = 0;

	/**
	 * Short language identifier
	 *
	 * @var string
	 */
	protected $name = '';

	/**
	 * Detailed name
	 *
	 * @var string
	 */
	protected $title = '';

	/**
	 * Array holding all the model
	 *
	 * @var array<mixed>
	 */
	//protected $model = [];

	/**
	 * Default language?
	 *
	 * @var bool
	 */
	protected $default = false;

	/**
	 * Buffer for already fetched language vars
	 *
	 * @var array
	 */
	protected $buffer = [];

	/**
	 * Hm, what do you think this __construct does ... coffee?
	 *
	 * @param int  $id      Language ID
	 * @param bool $default Is this the default language?
	 */
	public function __construct($id, $default = false) {

		// Fetch info
		$query = \Skies::getDb()->prepare('SELECT * FROM `language` WHERE `langID` = :id');
		$query->execute([':id' => $id]);

		$data = $query->fetchArray();

		$this->name  = $data['langName'];
		$this->title = $data['langTitle'];

		/*// Fetch model
		$query = 'SELECT * FROM `'.TBL_PRE.'language-model` WHERE `langID` = '.\escape($id);

		$this->model = \Skies::getDb()->query($query)->fetch_array(MYSQLI_ASSOC);*/

		// Blah blah
		$this->default = $default;
		$this->id      = $id;

	}

	public function get($var, $userVars = [], $nl2br = false) {

		if(isset($this->buffer[$var])) {

			return ($nl2br ? nl2br($this->buffer[$var], true) : $this->buffer[$var]);

		}
		else {

			if(explode('.', $var)[0] == 'config') {
				$varData = $this->replaceVars($this->getConfig($var), $userVars);
			}
			else {
				$varData = $this->replaceVars($this->getDB($var), $userVars);
			}

			// Save to the buffer
			$this->buffer[$var] = $varData;

			if($varData == $var) {
				$varData = '{{'.$varData.'}}';
			}

			return ($nl2br ? nl2br($varData, true) : $varData);

		}

	}

	/**
	 * Fetch the language variable form the DB
	 *
	 * @param string $var language variable
	 *
	 * @return string Content of the language variable
	 */
	protected function getDB($var) {

		$query = \Skies::getDb()->prepare('SELECT * FROM `language-data` WHERE `langID` = :id AND `varName` = :var');
		$query->execute([':id' => $this->id, ':var' => $var]);

		if($query->rowCount() == 0 && !$this->default) {

			return \Skies::getDefaultLanguage()->get($var);

		}
		elseif($query->rowCount() == 1) {

			return $query->fetchArray()['varData'];

		}
		else {

			return $var;

		}


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

		// Language vars (lower case)
		if(preg_match_all('/\{\{[a-z0-9\-\_\.]+\}\}/', $varData, $matches) > 0) {

			foreach($matches[0] as $tag) {

				$varName = substr($tag, 2, strlen($tag) - 4);

				$varData = str_replace($tag, $this->get($varName), $varData);

			}

		}

		$matches = [];

		// Constants (upper case)
		if(preg_match_all('/\{\{[A-Z0-9\.\-\_]+\}\}/', $varData, $matches) > 0) {

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

}

?>
