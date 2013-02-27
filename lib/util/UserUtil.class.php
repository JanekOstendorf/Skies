<?php

namespace skies\util;

use skies\system\user\User;

/**
 * @author    Janek Ostendorf (ozzy) <ozzy2345de@gmail.com>
 * @copyright Copyright (c) Janek Ostendorf
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU General Public License, version 3
 * @package   skies.util
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
		if(isset($_SERVER['REMOTE_ADDR']))
			$REMOTE_ADDR = $_SERVER['REMOTE_ADDR'];

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
	 * @param    string        $ip
	 *
	 * @return    string
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
		$part7   = base_convert(($ipArray[0] * 256) + $ipArray[1], 10, 16);
		$part8   = base_convert(($ipArray[2] * 256) + $ipArray[3], 10, 16);

		return '::ffff:'.$part7.':'.$part8;
	}

	/**
	 * Converts IPv6 embedded IPv4 address into IPv4 or returns input if true IPv6.
	 *
	 * @param    string        $ip
	 *
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
			$ip    = explode(':', substr($ip, 7));
			$ip[0] = base_convert($ip[0], 16, 10);
			$ip[1] = base_convert($ip[1], 16, 10);

			$ipParts   = array();
			$tmp       = $ip[0] % 256;
			$ipParts[] = ($ip[0] - $tmp) / 256;
			$ipParts[] = $tmp;
			$tmp       = $ip[1] % 256;
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
	 * @static
	 *
	 * @param string $mail Mail address to check
	 *
	 * @return bool Is this address valid?
	 */
	public static function checkMail($mail) {

		// TODO: Allow some special chars
		return (preg_match('/'.self::MAIL_PATTERN.'/', trim($mail)) != 0);

	}

	/**
	 * Check user name for validity
	 *
	 * @static
	 *
	 * @param string $username Mail address to check
	 *
	 * @return bool Is this name valid?
	 */
	public static function checkUsername($username) {

		return (preg_match('/'.self::USERNAME_PATTERN.'/', trim($username)) != 0);

	}

	/**
	 * Deletes the cookie and clears the session variable
	 *
	 * @static
	 *
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
	 * @param int $user_id User ID
	 *
	 * @return bool
	 */
	public static function userExists($user_id) {

		$query = 'SELECT * FROM `'.TBL_PRE.'user` WHERE `userID` = '.\escape($user_id);

		if(!$res = \Skies::$db->query($query)) {
			return false;
		}

		if($res->num_rows != 1) {
			return false;
		}

		return true;

	}

	/**
	 * Checks the password
	 *
	 * @param string $password Clear text password to check
	 * @param int    $user_id  User ID
	 *
	 * @return bool
	 */
	public static function checkPassword($password, $user_id) {

		if(!self::userExists($user_id)) {
			return false;
		}

		// Get the password and the salt
		$query = 'SELECT * FROM `'.TBL_PRE.'user` WHERE userID = '.\escape($user_id);

		if(!$res = \Skies::$db->query($query)) {
			return false;
		}

		if(!$data = $res->fetch_object()) {
			return false;
		}

		// Password (as stored in the db): md5(md5(%password%).%salt%)

		if(md5(md5($password).$data->userSalt) != $data->userPassword) {
			return false;
		}
		else {
			return true;
		}
	}

	/**
	 * Generates a random alphanumeric string
	 *
	 * @param int $length
	 *
	 * @return string
	 */
	public static function randStr($length) {

		$pool = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz123456789';

		$return = '';

		for($i = 1; $i <= $length; $i++) {
			$rand = substr(str_shuffle($pool), 0, 1);
			$return .= $rand;
		}

		return $return;

	}

	/**
	 * Generates salt and hashed password
	 *
	 * @param string $password unencrypted password
	 *
	 * @return object $return->salt and $return->password
	 */
	public static function makePass($password) {

		$salt     = md5(self::randStr(128));
		$password = md5(md5($password).$salt);

		// Save salt and password in an obj
		$return = new \stdClass();

		$return->salt     = $salt;
		$return->password = $password;

		return $return;

	}

	/**
	 * @static
	 *
	 * Gets the ID of the user with the specified userName
	 *
	 * @param string $user_name Name of the user
	 *
	 * @return int ID of the user
	 */
	public static function usernameToID($user_name) {

		$query = 'SELECT * FROM `'.TBL_PRE.'user` WHERE `userName` = \''.\escape($user_name).'\'';

		$result = \Skies::$db->query($query);

		if($result === false || $result->num_rows != 1) {
			return false;
		}
		else {
			return $result->fetch_array(MYSQLI_ASSOC)['userID'];
		}

	}


	/**
	 * Creates a new user
	 *
	 * @param string $user_name     Username
	 * @param string $user_mail     Mail address
	 * @param string $user_password Plaintext password
	 *
	 * @return bool|\skies\system\user\User
	 */
	public static function createUser($user_name, $user_mail, $user_password) {

		// Check the values
		if(!self::checkMail($user_mail) || !self::checkUsername($user_name) || self::usernameToID($user_name) !== false) {

			return false;

		}

		// Crypt the password
		if(!empty($user_password)) {
			$password = self::makePass($user_password);
		}
		else {
			$password           = new \stdClass();
			$password->password = '';
			$password->salt     = '';
		}

		if($password === false) {
			return false;
		}

		$query = 'INSERT INTO `user` (`userMail`, `userName`, `userPassword`, `userSalt`) ';

		// Values
		$query .= 'VALUES(\''.escape($user_mail).'\',
            \''.escape($user_name).'\',
            \''.escape($password->password).'\',
            \''.escape($password->salt).'\')';

		// Execute
		if(!\Skies::$db->query($query)) {
			return false;
		}

		// Fetch user object
		return new \skies\system\user\User(self::usernameToID($user_name));

	}

	/**
	 * Sets the data value for the given dataField and userID. Creates the dataField if not exist.
	 *
	 * @param int    $userID User's ID
	 * @param string $data   Data field's name
	 * @param string $value  Value to set
	 *
	 * @return bool Success?
	 */
	public static function setData($userID, $data, $value) {

		if($userID == null) {
			return false;
		}

		// Does this dataField exist?
		$query  = 'SELECT * FROM `'.TBL_PRE.'user-fields` WHERE `fieldName` = \''.\escape($data).'\'';
		$result = \Skies::$db->query($query);


		// No, create it
		if($result->num_rows != 1) {

			$query = 'INSERT INTO `'.TBL_PRE.'user-fields` (`fieldName`) VALUES (\''.\escape($data).'\')';
			if(\Skies::$db->query($query) === false) {
				return false;
			}

			$fieldID = \Skies::$db->insert_id;

		}
		else {
			$fieldID = $result->fetch_array(MYSQLI_ASSOC)['fieldID'];
		}

		// Is there already an data entry?
		// No
		if(is_null(self::getData($userID, $data))) {

			$query = 'INSERT INTO `'.TBL_PRE.'user-data` (`dataFieldID`, `dataUserID`, `dataValue`)
                    VALUES('.\escape($fieldID).',
                           '.\escape($userID).',
                           \''.\escape($value).'\')';

		}
		// Yes
		else {

			$query = 'UPDATE `'.TBL_PRE.'user-data` SET `dataValue` = \''.\escape($value).'\' WHERE `dataUserID` = '.\escape($userID).' AND `dataFieldID` = '.\escape($fieldID);

		}

		// Save
		return \Skies::$db->query($query) === true;

	}

	/**
	 * Get the data field for one user
	 *
	 * @param int    $userID User's ID
	 * @param string $data   Data field name
	 *
	 * @return mixed|null Null if there is no value. Else the value.
	 */
	public static function getData($userID, $data) {

		if($userID == null) {
			return null;
		}

		$query  = 'SELECT * FROM `'.TBL_PRE.'user-data` INNER JOIN `'.TBL_PRE.'user-fields` ON `dataFieldID` = `fieldID` WHERE `fieldName` = \''.\escape($data).'\' AND `dataUserID` = '.escape($userID);
		$result = \Skies::$db->query($query);

		if($result->num_rows != 1) {
			return null;
		}
		else {
			return $result->fetch_array(MYSQLI_ASSOC)['dataValue'];
		}


	}

	public static function mail_utf8($to, $from_user, $from_email, $subject = '(No subject)', $message = '') {

		$from_user = "=?UTF-8?B?".base64_encode($from_user)."?=";
		$subject   = "=?UTF-8?B?".base64_encode($subject)."?=";

		$headers = "From: $from_user <$from_email>\n".
		           "MIME-Version: 1.0"."\n".
		           "Content-type: text/html; charset=UTF-8"."\n";

		return mail($to, $subject, $message, $headers, '-f '.$from_email);

	}

}

?>
