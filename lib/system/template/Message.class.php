<?php

namespace skies\system\template;

/**
 * @author    Janek Ostendorf (ozzy) <ozzy2345de@gmail.com>
 * @copyright Copyright (c) Janek Ostendorf
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU General Public License, version 3
 * @package
 */
class Message {

	/**
	 * The variable the different types will be saved in
	 *
	 * @var array
	 */
	private $store;

	/**
	 * Control variable
	 *
	 * @var int
	 */
	private $i;

	/**
	 * Style of the message to be printed
	 *
	 * @var string
	 */
	private $style;

	/**#@+
	 * Constants for identifing
	 */
	const NOTICE = 1;

	const SUCCESS = 2;

	const ERROR = 3;

	/**#@-*/

	/**
	 * Reads the style file and creates the message object
	 *
	 * @param string $css_class CSS class of the message block
	 *
	 * @return \skies\system\template\Message
	 */
	public function __construct($css_class) {

		$this->style = <<<HTML

<div class="%css_class%">
    %message%
</div>

HTML;

		$this->style = str_replace('%css_class%', $css_class, $this->style);


		$this->i     = 0;
		$this->store = array();

		return true;

	}

	/**
	 * Adds a message to the store variable
	 *
	 * @param string $msg      Message
	 * @param array  $userVars Custom variables. See \skies\system\language\Language::replaceVars()
	 *
	 * @return int|bool ID of the new message or false
	 */
	public function add($msg, $userVars = []) {

		if(!is_string($msg)) {
			return false;
		}

		$this->store[$this->i] = \Skies::$language->replaceVars($msg, $userVars);

		return $this->i++;

	}

	/**
	 * Deletes the message with the ID $id from the storage variable
	 *
	 * @param int $id Message ID
	 *
	 * @return bool
	 */
	public function del($id) {

		if(!isset($this->store[$id])) {
			return false;
		}

		unset($this->store[$id]);

		return true;

	}

	/**
	 * Prints all messages of this object
	 */
	public function printMessages() {

		foreach($this->store as $id => $msg) {

			echo str_ireplace("%message%", $msg, $this->style);

			unset($this->store[$id]);

		}

	}

	/**
	 * @static
	 *
	 * Prints all messages
	 */
	public static function printAll() {

		foreach(\Skies::$message as $message) {

			$message->printMessages();

		}

	}

}

?>
