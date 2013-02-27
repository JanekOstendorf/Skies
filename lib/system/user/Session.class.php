<?php

namespace skies\system\user;

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
	protected $userID = 0;

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
	protected $oldIP;

	/**
	 * Starts session and handles login
	 */
	public function __construct() {

		$this->ip = \skies\util\UserUtil::getIpAddress();

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
		$this->id = \skies\util\StringUtil::getRandomHash();

		$query = 'INSERT INTO '.TBL_PRE.'session
            (`sessionID`, `sessionIP`, `sessionLastActivity`, `sessionUserID`)
            VALUES(\''.\escape($this->id).'\', \''.\escape(\skies\util\UserUtil::getIpAddress()).'\', '.\escape(NOW).', NULL)';


		return !(!\Skies::$db->query($query) || !setcookie(COOKIE_PRE.'sessionID', $this->id, NOW + (365 * 86400), '/'));


	}

	/**
	 * Do all stuff for ongoing sessions
	 */
	protected function continueSession() {

		$res = \Skies::$db->query('SELECT * FROM `'.TBL_PRE.'session` WHERE `sessionID` = \''.\escape($this->id).'\'');

		if($res->num_rows != 1) {

			$this->closeSession();
			$this->newSession();

		}
		else {

			$data = $res->fetch_array();

			$this->userID = $data['sessionUserID'];
			$this->long   = ($data['sessionLong'] == 1);
			$this->oldIP  = $data['sessionIP'];

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

					$query = 'UPDATE `'.TBL_PRE.'session` SET `sessionLastActivity` = '.NOW.' WHERE `sessionID` = \''.\escape($this->id).'\'';

					\Skies::$db->query($query);

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
	 * @param int  $userID User ID
	 * @param bool $long   Long session?
	 *
	 * @return bool Success?
	 */
	public function login($userID, $long = false) {

		// Write it into the DB
		$query = 'UPDATE '.TBL_PRE.'session SET `sessionLong` = '.($long == true ? '1' : '0').', `sessionUserID` = '.\escape($userID).' WHERE `sessionID` = \''.\escape($this->id).'\'';

		// Some checks
		if(\Skies::$db->query($query) === false) {
			return false;
		}
		else {

			$this->userID = $userID;

			// Update the global user object
			$this->rehashUser();

			\Skies::$user->setLastActivity(NOW);
			\Skies::$user->update();

			return true;

		}

	}

	public function logout() {

		// Are we even logged in?
		if($this->userID == GUEST_ID) {
			return false;
		}

		// Write it into the DB
		$query = 'UPDATE '.TBL_PRE.'session SET `sessionUserID` = NULL WHERE `sessionID` = \''.\escape($this->id).'\'';

		// Some checks
		if(\Skies::$db->query($query) === false) {
			return false;
		}
		else {
			$this->userID = GUEST_ID;

			// Update the global user object
			$this->rehashUser();

			return true;
		}

	}

	/**
	 * @return User User of this session
	 */
	public function getUser() {

		return new User($this->userID);

	}

	/**
	 * Closes the current session
	 */
	protected function closeSession() {

		\skies\util\UserUtil::deleteCookie(COOKIE_PRE.'sessionID');

		\Skies::$db->query('DELETE FROM '.TBL_PRE.'session WHERE sessionID = \''.\escape($this->id).'\'');

	}

	/**
	 * Updates the global user object
	 */
	public function rehashUser() {

		\Skies::$user = $this->getUser();

	}

}

?>
