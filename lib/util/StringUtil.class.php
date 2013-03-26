<?php

namespace skies\util;

/**
 * @author    Janek Ostendorf (ozzy) <ozzy2345de@gmail.com>
 * @copyright Copyright (c) Janek Ostendorf
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU General Public License, version 3
 * @package   skies.util
 */
class StringUtil {

	/**
	 * Converts special HTML characters
	 *
	 * @static
	 * @param $string
	 * @return string
	 */
	public static function encodeHtml($string) {

		if(is_object($string)) {
			$string = $string->__toString();
		}

		return @htmlspecialchars($string, ENT_COMPAT, 'UTF-8');

	}

	/**
	 * Alias to php sha1() function
	 *
	 * @static
	 * @param $string
	 * @return string
	 */
	public static function getHash($string) {

		return sha1($string);

	}

	/**
	 * @static
	 * @return string
	 */
	public static function getRandomHash() {

		return self::getHash(self::getRandomString(32));

	}

	/**
	 * Generates a random alphanumeric string
	 *
	 * @param int $length Length of the string
	 * @return string
	 */
	public static function getRandomString($length) {

		$pool = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz123456789';

		$return = '';

		for($i = 1; $i <= $length; $i++) {
			$rand = substr(str_shuffle($pool), 0, 1);
			$return .= $rand;
		}

		return $return;

	}

	/**
	 * Generate a string of spaces of defined length.
	 *
	 * @param int $indent Number of spaces to generate
	 * @return string
	 */
	public static function getIndent($indent) {

		$return = '';

		for($i = 0; $i < $indent; $i++) {

			$return .= ' ';

		}

		return $return;

	}

}

?>
