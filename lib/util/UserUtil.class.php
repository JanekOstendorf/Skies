<?php

namespace skies\util;

use skies\system\user\User;

/**
 * @author    Janek Ostendorf (ozzy) <ozzy2345de@gmail.com>
 * @copyright Copyright (c) Janek Ostendorf
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU General Public License, version 3
 */
class UserUtil {

	/**
	 * Regex pattern for usernames
	 */
	const USERNAME_PATTERN = '[a-zA-Z-_0-9\(\)\[\]\s]+';

	/**
	 * Regex pattern for mail addresses
	 */
	const MAIL_PATTERN = '.+\@.+\.[a-zA-Z]{2,7}';

	/**
	 * Returns the ipv6 address of the client.
	 *
	 * @return     string        ipv6 address
	 */
	public static function getIpAddress() {

		$REMOTE_ADDR = '';
		if(isset($_SERVER['REMOTE_ADDR'])) {
			$REMOTE_ADDR = $_SERVER['REMOTE_ADDR'];
		}

		// darwin fix
		if($REMOTE_ADDR == '::1' || $REMOTE_ADDR == 'fe80::1') {
			$REMOTE_ADDR = '127.0.0.1';
		}

		$REMOTE_ADDR = self::convertIPv4To6($REMOTE_ADDR);

		return $REMOTE_ADDR;
	}

	/**
	 * Converts given ipv4 to ipv6.
	 *
	 * @param string $ip
	 * @return string
	 */
	public static function convertIPv4To6($ip) {

		if(filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) !== false) {
			// given ip is already ipv6
			return $ip;
		}

		if(filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) === false) {
			// invalid ip given
			return '';
		}

		$ipArray = array_pad(explode('.', $ip), 4, 0);
		$part7 = base_convert(($ipArray[0] * 256) + $ipArray[1], 10, 16);
		$part8 = base_convert(($ipArray[2] * 256) + $ipArray[3], 10, 16);

		return '::ffff:'.$part7.':'.$part8;
	}

	/**
	 * Converts IPv6 embedded IPv4 address into IPv4 or returns input if true IPv6.
	 *
	 * @param    string $ip
	 * @return    string
	 */
	public static function convertIPv6To4($ip) {

		// validate if given IP is a proper IPv6 address
		if(filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) === false) {
			// validate if given IP is a proper IPv4 address
			if(filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) === false) {
				// ip address is invalid
				return '';
			}

			return $ip;
		}

		// check if ip is a masked IPv4 address
		if(substr($ip, 0, 7) == '::ffff:') {
			$ip = explode(':', substr($ip, 7));
			$ip[0] = base_convert($ip[0], 16, 10);
			$ip[1] = base_convert($ip[1], 16, 10);

			$ipParts = array();
			$tmp = $ip[0] % 256;
			$ipParts[] = ($ip[0] - $tmp) / 256;
			$ipParts[] = $tmp;
			$tmp = $ip[1] % 256;
			$ipParts[] = ($ip[1] - $tmp) / 256;
			$ipParts[] = $tmp;

			return implode('.', $ipParts);
		}
		else {
			// given ip is an IPv6 address and cannot be converted
			return $ip;
		}

	}

	/**
	 * Check mail address for validity
	 *
	 * @param string $mail Mail address to check
	 * @return bool Is this address valid?
	 */
	public static function checkMail($mail) {

		// TODO: Allow some special chars
		return (preg_match('/'.self::MAIL_PATTERN.'/', trim($mail)) != 0);

	}

	/**
	 * Check user name for validity
	 *
	 * @param        $username
	 * @param string $username Mail address to check
	 * @return bool Is this name valid?
	 */
	public static function checkUsername($username) {

		return (preg_match('/'.self::USERNAME_PATTERN.'/', trim($username)) != 0);

	}

	/**
	 * Deletes the cookie and clears the session variable
	 *
	 * @return void
	 * @param string $cookie Name of the cookie
	 */
	public static function deleteCookie($cookie) {

		session_start();
		$_SESSION = array();
		setcookie($cookie, '', time() - 86400);
		setcookie(session_name(), '', time() - 86400);
		session_destroy();

	}

	/**
	 * Does the user exist?
	 *
	 * @param int $userId User ID
	 * @return bool
	 */
	public static function userExists($userId) {

		$query = \Skies::getDb()->prepare('SELECT * FROM `user` WHERE `userID` = :id');
		$query->execute([':id' => $userId]);

		if($query->getRowCount() != 1) {
			return false;
		}

		return true;

	}

	/**
	 * @static
	 * Gets the ID of the user with the specified userName
	 * @param string $userName Name of the user
	 * @return int ID of the user
	 */
	public static function usernameToID($userName) {

		$query = \Skies::getDb()->prepare('SELECT * FROM `user` WHERE `userName` = :userName');
		$query->execute([':userName' => $userName]);

		if($query->getRowCount() != 1) {
			return false;
		}
		else {
			return $query->fetchArray()['userId'];
		}

	}

	/**
	 * Creates a new user
	 *
	 * @param string $userName     Username
	 * @param string $userMail     Mail address
	 * @param string $userPassword Plaintext password
	 * @return bool|\skies\system\user\User
	 */
	public static function createUser($userName, $userMail, $userPassword) {

		// Check the values
		if(!self::checkMail($userMail) || !self::checkUsername($userName) || self::usernameToID($userName) !== false) {

			return false;

		}

		// Crypt the password
		if(!empty($userPassword)) {
			$password = self::makePass($userPassword);
		}
		else {
			$password = new \stdClass();
			$password->password = '';
			$password->salt = '';
		}

		if($password === false) {
			return false;
		}

		$query = \Skies::getDb()->prepare('INSERT INTO `user` (`userMail`, `userName`, `userPassword`, `userSalt`)
			VALUES(:mail, :name, :password, :salt)');
		$query->execute([
			'mail' => $userMail,
			'name' => $userName,
			'password' => $password->password,
			'salt' => $password->salt
		]);

		// Fetch user object
		return new User(self::usernameToID($userName));

	}

}
