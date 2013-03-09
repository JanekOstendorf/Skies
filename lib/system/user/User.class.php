<?php

namespace skies\system\user;

use skies\lan\Team;
use skies\util\SecureUtil;
use skies\util\UserUtil;

/**
 * @author    Janek Ostendorf (ozzy) <ozzy2345de@gmail.com>
 * @copyright Copyright (c) Janek Ostendorf
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU General Public License, version 3
 * @package   skies.user
 */
class User {

	/**
	 * User ID
	 *
	 * @var int
	 */
	protected $id;

	/**
	 * User name
	 *
	 * @var string
	 */
	protected $name;

	/**
	 * User's mail address
	 *
	 * @var string
	 */
	protected $mail;

	/**
	 * Password string
	 *
	 * @var string
	 */
	protected $password;

	/**
	 * Last user activity (UNIX)
	 *
	 * @var int
	 */
	protected $lastActivity;

	/**
	 * Array holding custom model about this user. (buffer)
	 *
	 * @var array
	 */
	protected $data;

	/**
	 * @var bool
	 */
	protected $hasPassword = false;

	/**
	 * @param int $userId User's ID
	 *
	 * @return User
	 */
	public function __construct($userId) {

		// Normal users
		if($userId != GUEST_ID) {

			if(!UserUtil::userExists($userId)) {
				return false;
			}

			// Fetch info
			$query = \Skies::$db->prepare('SELECT * FROM `user` WHERE userId = :userId');
			$query->execute([':userId' => $userId]);

			$data = $query->fetchArray();

			// Write into our vars
			$this->id          = $data['userId'];
			$this->name        = $data['userName'];
			$this->mail        = $data['userMail'];
			$this->hasPassword = ($data['userPassword'] != '');
			$this->lastActivity = $data['userLastActivity'];
			$this->password    = $data['userPassword'];

		}

		// Guests
		else {

			$this->id   = GUEST_ID;
			$this->name = null;
			$this->mail = null;

		}

	}

	/**
	 * Update user's info. Writes to DB first and then fetches new stuff
	 */
	public function update() {

		// No need for this if we're a guest
		if($this->isGuest()) {
			return;
		}

		// Write stuff into DB
		$query = \Skies::$db->prepare('UPDATE `user` SET
			`userMail` = :mail,
            `userName` = :name,
            `userLastActivity` = :lastActivity
            WHERE `userId` = :id');

		$query->execute([
			':mail' => $this->mail,
		    ':name' => $this->name,
		    ':lastActivity' => $this->lastActivity,
		    ':id' => $this->id
		]);

		// Delete cache
		$this->data = [];

		// Fetch stuff again
		$this->__construct($this->id);


	}

	/**
	 * @param string $password Password to check
	 * @return bool
	 */
	public function checkPassword($password) {

		return SecureUtil::CheckPassword($password, $this->mail, $this->password);

	}

	/**
	 * Is this user a guest?
	 *
	 * @return bool
	 */
	public function isGuest() {

		return $this->id == GUEST_ID;

	}

	/**
	 * @return string User name
	 */
	public function getName() {

		return $this->name;

	}

	/**
	 * @return int User ID
	 */
	public function getId() {

		return $this->id;

	}

	/**
	 * @return string User's mail address
	 */
	public function getMail() {

		return $this->mail;

	}

	/**
	 * Changes user's user name
	 *
	 * @param string $name User name
	 */
	public function setName($name) {

		$this->name = $name;

	}

	/**
	 * Changes user's mail address
	 *
	 * @param string $mail     User's mail address
	 * @param string $password The user's password
	 *
	 * @return bool Is the password correct? (Success)
	 */
	public function setMail($mail, $password) {

		if($this->checkPassword($password)) {

			$this->mail = $mail;

			// Rehash the password
			$this->setPassword($password);

			return true;

		}

		// Nope, you're wrong
		return false;

	}

	/**
	 * Changes the user's password
	 *
	 * @param string $password Plain text password
	 */
	public function setPassword($password) {

		$passwordHash = SecureUtil::EncryptPassword($password, $this->mail);

		$query = \Skies::$db->prepare('UPDATE `user` SET `userPassword` = :password WHERE `userID` = id');

		$query->execute([
			':password' => $passwordHash,
		    ':id' => $this->id
		]);

	}

	/**
	 * Sets the dataField for this user
	 *
	 * @param string $data  Data field name
	 * @param mixed  $value Value to set
	 *
	 * @return bool Success?
	 */
	public function setData($data, $value) {

		if(!UserUtil::setData($this->getId(), $data, $value))
			return false;

		$this->data[$data] = $value;

		return true;

	}

	/**
	 * Get the model field for this user
	 *
	 * @param string $data Data field name
	 *
	 * @return mixed|null Null if there is no value. Else the value.
	 */
	public function getData($data) {

		if(isset($this->data[$data]))
			return $this->data[$data];

		return UserUtil::getData($this->id, $data);

	}

	/**
	 * @return bool
	 */
	public function hasPassword() {

		return $this->hasPassword;
	}

	/**
	 * @return int
	 */
	public function getLastActivity() {

		return $this->lastActivity;

	}

	/**
	 * @param int $lastActivity
	 */
	public function setLastActivity($lastActivity) {

		$this->lastActivity = $lastActivity;

	}

}

?>
