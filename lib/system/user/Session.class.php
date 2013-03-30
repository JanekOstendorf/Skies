<?php

namespace skies\system\user;

use skies\util\StringUtil;
use skies\util\UserUtil;

/**
 * @author    Janek Ostendorf (ozzy) <ozzy2345de@gmail.com>
 * @copyright Copyright (c) Janek Ostendorf
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU General Public License, version 3
 * @package   skies.user
 */
class Session {

	/**
	 * Session ID
	 *
	 * @var string
	 */
	protected $id = '';

	/**
	 * User ID
	 *
	 * @var string
	 */
	protected $userId = 0;

	/**
	 * User
	 *
	 * @var User
	 */
	protected $user = null;

	/**
	 * Is this a long login?
	 *
	 * @var bool
	 */
	protected $long = 0;

	/**
	 * IPv6 of the user
	 *
	 * @var string
	 */
	protected $ip;

	/**
	 * IP fetched from the DB
	 *
	 * @var string
	 */
	protected $oldIp;

	/**
	 * Starts session and handles login
	 */
	public function __construct() {

		$this->ip = UserUtil::getIpAddress();

		// Fetch session ID
		if(isset($_COOKIE[COOKIE_PRE.'sessionId']) && !empty($_COOKIE[COOKIE_PRE.'sessionId']) && preg_match("/[0-9a-f]{40}/", $_COOKIE[COOKIE_PRE.'sessionId'])) {

			$this->id = $_COOKIE[COOKIE_PRE.'sessionId'];
			$this->continueSession();

		}
		else {

			$this->newSession();
			$this->continueSession();

		}

	}

	/**
	 * Start a new session
	 */
	protected function newSession() {

		// Create new ID
		$this->id = StringUtil::getRandomHash();

		$query = \Skies::getDb()->prepare('INSERT INTO `session`
			(`sessionId`, `sessionIp`, `sessionLastActivity`, `sessionUserId`)
			VALUES(:id, :ip, :lastActivity, :userId)');

		$query->execute([
			':id' => $this->id,
			':ip' => UserUtil::getIpAddress(),
			':lastActivity' => NOW,
			':userId' => null
		]);

		return setcookie(COOKIE_PRE.'sessionId', $this->id, NOW + (365 * 86400), '/'.SUBDIR) !== false;

	}

	/**
	 * Do all stuff for ongoing sessions
	 */
	protected function continueSession() {

		$query = \Skies::getDb()->prepare('SELECT * FROM `session` WHERE `sessionId` = :id');
		$query->execute([':id' => $this->id]);

		if($query->getRowCount() != 1) {

			$this->closeSession();
			$this->newSession();

		}
		else {

			$data = $query->fetchArray();

			$this->userId = $data['sessionUserId'];
			$this->long = ($data['sessionLong'] == 1);
			$this->oldIp = $data['sessionIp'];

			// Check session's IP
			//if($this->oldIP == $this->ip) {

			// Check if the session timed out
			if($this->long) {
				$length = (365 * 86400);
			}
			else {
				$length = (30 * 60);
			}

			// Session's dead
			if($data['sessionLastActivity'] + $length < NOW) {

				$this->closeSession();
				$this->newSession();

			}
			else {

				/*
				 * Update DB
				 */

				$query = \Skies::getDb()->prepare('UPDATE `session` SET `sessionLastActivity` = :lastActivity WHERE `sessionID` = :id');

				$query->execute([':lastActivity' => NOW, ':id' => $this->id]);

				if(!$this->getUser()->isGuest()) {
					$this->getUser()->setLastActivity(NOW);
					$this->getUser()->update();
				}

			}

			/*}
			else {

				// Damn, session's invalid :/
				$this->closeSession();
				$this->newSession();

			}*/

		}

	}

	/**
	 * Change the user ID of this session
	 *
	 * @param int  $userId User ID
	 * @param bool $long   Long session?
	 * @return bool Success?
	 */
	public function login($userId, $long = false) {

		// Write it into the DB
		$query = \Skies::getDb()->prepare('UPDATE `session` SET `sessionLong` = :long, `sessionUserId` = :userId WHERE `sessionId` = :id');

		$query->execute([
			':long' => $long == true,
			':userId' => $userId,
			':id' => $this->id
		]);

		$this->user = null;
		$this->userId = $userId;

		$this->getUser()->setLastActivity(NOW);
		$this->getUser()->update();

		\Skies::updateUser();

		return true;

	}

	public function logout() {

		// Are we even logged in?
		if($this->userId == GUEST_ID) {
			return false;
		}

		// Write it into the DB
		$query = \Skies::getDb()->prepare('UPDATE `session` SET `sessionUserId` = NULL WHERE `sessionId` = :id');

		$query->execute([':id' => $this->id]);

		$this->userId = GUEST_ID;

		return true;

	}

	/**
	 * @return User User of this session
	 */
	public function getUser() {

		if($this->user === null) {
			$this->user = new User($this->userId);
		}

		return $this->user;

	}

	/**
	 * Closes the current session
	 */
	protected function closeSession() {

		UserUtil::deleteCookie(COOKIE_PRE.'sessionId');

		$query = \Skies::getDb()->prepare('DELETE FROM `session` WHERE `sessionId` = :id');

		$query->execute([':id' => $this->id]);

	}

}

?>
