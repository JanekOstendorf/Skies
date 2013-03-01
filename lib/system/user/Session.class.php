<?php

namespace skies\system\user;

/**
 * @author    Janek Ostendorf (ozzy) <ozzy2345de@gmail.com>
 * @copyright Copyright (c) Janek Ostendorf
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU General Public License, version 3
 * @package   skies.user
 */
use skies\util\StringUtil;
use skies\util\UserUtil;

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
		if(isset($_COOKIE[COOKIE_PRE.'sessionID']) && !empty($_COOKIE[COOKIE_PRE.'sessionID']) && preg_match("/[0-9a-f]{40}/", $_COOKIE[COOKIE_PRE.'sessionID'])) {


			$this->id = $_COOKIE[COOKIE_PRE.'sessionID'];
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

		$query = \Skies::$db->prepare('INSERT INTO `session`
			(`sessionId`, `sessionIp`, `sessionLastActivity`, `sessionUserId`)
			VALUES(:id, :ip, :lastActivity, :userId)');

		$query->execute([
			':id' => $this->id,
			':ip' => UserUtil::getIpAddress(),
			':lastActivity' => NOW,
			':userId' => null
		]);


		return setcookie(COOKIE_PRE.'sessionId', $this->id, NOW + (365 * 86400), '/') !== false;


	}

	/**
	 * Do all stuff for ongoing sessions
	 */
	protected function continueSession() {

		$query = \Skies::$db->prepare('SELECT * FROM `session` WHERE `sessionId` = :id');
		$query->execute([':id' => $this->id]);

		if($query->rowCount() != 1) {

			$this->closeSession();
			$this->newSession();

		}
		else {

			$data = $query->fetchArray();

			$this->userId = $data['sessionUserID'];
			$this->long   = ($data['sessionLong'] == 1);
			$this->oldIp  = $data['sessionIP'];

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

					$query = \Skies::$db->prepare('UPDATE `session` SET `sessionLastActivity` = :lastActivity WHERE `sessionID` = :id');

					$query->execute([':lastActivity' => NOW, ':sessionId' => $this->id]);

					$this->rehashUser();

					if(!\Skies::$user->isGuest()) {
						\Skies::$user->setLastActivity(NOW);
						\Skies::$user->update();
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
	 *
	 * @return bool Success?
	 */
	public function login($userId, $long = false) {

		// Write it into the DB
		$query = 'UPDATE `session` SET `sessionLong` = :long, `sessionUserId` = :userId WHERE `sessionID` = :id';

		$params = [
			':long' => $long == true,
			':userId' => $userId,
			':id' => $this->id
		];

		// Some checks
		if(\Skies::$db->query($query) === false) {
			return false;
		}
		else {

			$this->userId = $userId;

			// Update the global user object
			$this->rehashUser();

			\Skies::$user->setLastActivity(NOW);
			\Skies::$user->update();

			return true;

		}

	}

	public function logout() {

		// Are we even logged in?
		if($this->userId == GUEST_ID) {
			return false;
		}

		// Write it into the DB
		$query = \Skies::$db->prepare('UPDATE `session` SET `sessionUserID` = NULL WHERE `sessionID` = :id');

		$query->execute([':id' => $this->id]);

		$this->userId = GUEST_ID;

		// Update the global user object
		$this->rehashUser();

		return true;

	}

	/**
	 * @return User User of this session
	 */
	public function getUser() {

		return new User($this->userId);

	}

	/**
	 * Closes the current session
	 */
	protected function closeSession() {

		UserUtil::deleteCookie(COOKIE_PRE.'sessionID');

		$query = \Skies::$db->prepare('DELETE FROM `session` WHERE `sessionID` = :id');

		$query->execute([':id' => $this->id]);

	}

	/**
	 * Updates the global user object
	 */
	public function rehashUser() {

		\Skies::$user = $this->getUser();

	}

}

?>
