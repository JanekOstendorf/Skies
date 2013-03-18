<?php
/**
 * @author    Janek Ostendorf (ozzy) <ozzy2345de@gmail.com>
 * @copyright Copyright (c) 2013 Janek Ostendorf
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU General Public License, version 3
 */
 
namespace skies\util;

/**
 * Utility methods for security stuff
 */
class SecureUtil {

	/**
	 * Encrypt a password
	 * @param   string  $password
	 * @param   string  $email
	 * @param   string     $rounds
	 * @return  string  Password
	 */
	public static function EncryptPassword($password, $email, $rounds = '08') {
		$string = hash_hmac('whirlpool', str_pad($password, strlen($password) * 4, sha1($email), STR_PAD_BOTH), \Skies::getConfig()['salt'], false);
		$salt   = substr(str_shuffle('./0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 22);
		return crypt($string, '$2a$'.$rounds.'$'.$salt);
	}

	/**
	 * Compare the password and e-mail with the encrypted
	 * @param   string  $password
	 * @param   string  $email
	 * @param   string  $stored     The encrypted password
	 * @return  bool
	 */
	public static function CheckPassword($password, $email, $stored) {
		$string = hash_hmac('whirlpool', str_pad($password, strlen($password) * 4, sha1($email), STR_PAD_BOTH), \Skies::getConfig()['salt'], false);
		return crypt($string, substr($stored, 0, 30)) == $stored;
	}

}
