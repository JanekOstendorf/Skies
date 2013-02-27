<?php

namespace skies\system\user;

use skies\lan\Team;

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
	 * Last user activity (UNIX)
	 *
	 * @var int
	 */
	protected $lastActivity;

	/**
	 * When has this user accepted the invitation?
	 *
	 * @var int
	 */
	protected $acceptTime;

	/**
	 * Has this user paid?
	 *
	 * @var int
	 */
	protected $payTime;

	/**
	 * Is this user Admin?
	 *
	 * @var bool
	 */
	protected $isAdmin;

	/**
	 * Array holding custom data about this user. (buffer)
	 *
	 * @var array
	 */
	protected $data;

	/**
	 * @var bool
	 */
	protected $hasPassword = false;

	/**
	 * Team
	 *
	 * @var \skies\lan\Team
	 */
	protected $team = null;

	/**
	 * Does this one have a team?
	 *
	 * @var bool
	 */
	protected $hasTeam = false;

	/**
	 * Is this one a leader?
	 *
	 * @var bool
	 */
	protected $isLeader = false;

	/**
	 * Does this user has to pay the reduced price?
	 *
	 * @var bool
	 */
	protected $reducedPrice = false;

	/**
	 * Did an admin accept for him/her?
	 *
	 * @var bool
	 */
	protected $hasAdminAccepted = false;

	/**
	 * Admin who accepted for me
	 *
	 * @var \skies\system\user\User
	 */
	protected $adminAccepted = null;

	/**
	 * ID of the admin
	 *
	 * @var int
	 */
	protected $adminAcceptedId = 0;


	/**
	 * @param int $userID User's ID
	 *
	 * @return User
	 */
	public function __construct($userID) {

		// Normal users
		if($userID != GUEST_ID) {

			if(!\skies\util\UserUtil::userExists($userID)) {
				return false;
			}

			// Fetch info
			$result = \Skies::$db->query("SELECT * FROM ".TBL_PRE.'user WHERE userID = '.escape($userID));

			$data = $result->fetch_array();

			// Write into our vars
			$this->id          = $data['userID'];
			$this->name        = $data['userName'];
			$this->mail        = $data['userMail'];
			$this->hasPassword = ($data['userPassword'] != '');

			$this->lastActivity = $data['userLastActivity'];
			$this->acceptTime   = $data['userAcceptTime'];
			$this->payTime      = $data['userPayTime'];
			$this->reducedPrice = ($data['userReducedPrice'] == 1);
			$this->isAdmin      = ($data['userIsAdmin'] == 1);

			$this->adminAcceptedId = $data['userAdminAcceptedId'];

			// Team
			$this->team = Team::getUsersTeam($this->id);

			if($this->team === null) {

				// Is he a leader instead?
				$this->team = Team::getLeadersTeam($this->id);

				if($this->team === null)
					$this->hasTeam = false;
				else {

					$this->hasTeam = true;
					$this->isLeader = true;

				}

			}
			else
				$this->hasTeam = true;

			$this->adminAccepted = new User($this->adminAcceptedId);

			if($this->adminAccepted instanceof User && $this->adminAcceptedId != 0) {

				$this->hasAdminAccepted = true;

			}
			else
				$this->hasAdminAccepted = false;


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
		$query = \Skies::$db->prepare('UPDATE `'.TBL_PRE.'user` SET
			`userMail` = ?,
            `userName` = ?,
            `userLastActivity` = ?,
            `userAcceptTime` = ?,
            `userPayTime` = ?,
            `userIsAdmin` = ?,
            `userReducedPrice` = ?,
            `userAdminAcceptedId` = ?
            WHERE `userID` = ?');

		$query->bind_param('ssiiiiiii', $this->mail, $this->name, $this->lastActivity, $this->acceptTime, $this->payTime, $this->isAdmin, $this->reducedPrice, $this->adminAcceptedId, $this->id);

		$query->execute();

		// Delete cache
		$this->data = [];

		// Fetch stuff again
		$this->__construct($this->id);


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
	 * @param string $mail User's mail address
	 */
	public function setMail($mail) {

		$this->mail = $mail;

	}

	/**
	 * Changes the user's password
	 *
	 * @param string $password Plain text password
	 * @return bool Success?
	 */
	public function setPassword($password) {

		$pwObj = \skies\util\UserUtil::makePass($password);

		$query = 'UPDATE `'.TBL_PRE.'user` SET `userPassword` = \''.escape($pwObj->password).'\', `userSalt` = \''.escape($pwObj->salt).'\' WHERE `userID` = '.escape($this->id);

		return \Skies::$db->query($query);

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

		if(!\skies\util\UserUtil::setData($this->getId(), $data, $value))
			return false;

		$this->data[$data] = $value;

		return true;

	}

	/**
	 * Get the data field for this user
	 *
	 * @param string $data Data field name
	 *
	 * @return mixed|null Null if there is no value. Else the value.
	 */
	public function getData($data) {

		if(isset($this->data[$data]))
			return $this->data[$data];

		return \skies\util\UserUtil::getData($this->id, $data);

	}

	/**
	 * @return bool
	 */
	public function isAdmin() {

		return $this->isAdmin;

	}

	/**
	 * @return bool
	 */
	public function isLeader() {

		return $this->isLeader;

	}

	/**
	 * @return bool
	 */
	public function hasTeam() {

		return $this->hasTeam;

	}

	/**
	 * @return \skies\lan\Team
	 */
	public function getTeam() {

		return $this->team;

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
	public function getAcceptTime() {

		return $this->acceptTime;

	}

	/**
	 * @return int
	 */
	public function getPayTime() {

		return $this->payTime;

	}

	/**
	 * @return int
	 */
	public function getLastActivity() {

		return $this->lastActivity;

	}

	/**
	 * @return bool
	 */
	public function hasAccepted() {

		return $this->acceptTime > 0;

	}

	/**
	 * @return bool
	 */
	public function hasPaid() {

		return $this->payTime > 0;

	}

	/**
	 * @return bool
	 */
	public function hasVisited() {

		return $this->lastActivity > 0;

	}

	/**
	 * @param int $acceptTime
	 */
	public function setAcceptTime($acceptTime) {

		$this->acceptTime = $acceptTime;

	}

	/**
	 * @param bool $isAdmin
	 */
	public function setIsAdmin($isAdmin) {

		$this->isAdmin = $isAdmin;

	}

	/**
	 * @param int $payTime
	 */
	public function setPayTime($payTime) {

		$this->payTime = $payTime;

	}

	/**
	 * @param int $lastActivity
	 */
	public function setLastActivity($lastActivity) {

		$this->lastActivity = $lastActivity;

	}

	/**
	 * @return bool
	 */
	public function hasReducedPrice() {

		return $this->reducedPrice;

	}

	/**
	 * @param bool $reducedPrice
	 */
	public function setReducedPrice($reducedPrice) {

		$this->reducedPrice = $reducedPrice;

	}

	/**
	 * @return bool
	 */
	public function hasAdminAccepted() {

		return $this->hasAdminAccepted;

	}

	/**
	 * @param int $adminId
	 */
	public function setAdminAcceptedId($adminId) {

		$this->adminAcceptedId = $adminId;

	}

	/**
	 * @return User
	 */
	public function getAdminAccepted() {

		return $this->adminAccepted;

	}

}

?>
