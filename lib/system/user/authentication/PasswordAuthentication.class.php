<?php
/**
 * @author    Janek Ostendorf (ozzy) <ozzy2345de@gmail.com>
 * @copyright Copyright (c) 2013 Janek Ostendorf
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU General Public License, version 3
 */

namespace skies\system\user\authentication;

use skies\system\user\User;
use skies\util\SecureUtil;

/**
 *
 */
class PasswordAuthentication implements IAuthentication {

	/**
	 * User
	 *
	 * @var User
	 */
	protected $user = null;

	/**
	 * User's given password
	 *
	 * @var string
	 */
	protected $password = '';

	/**
	 * @var bool
	 */
	protected $isValid = false;

	/**
	 * Initialize the authentication with the arguments needed
	 *
	 * @param User   $user     User to authenticate
	 * @param string $password User's given password
	 */
	public function __construct(User $user, $password) {
		$this->user = $user;
		$this->isValid = SecureUtil::checkPassword($this->password, $this->user->getMail(), $this->user->getPasswordHash());
	}

	/**
	 * Are the arguments passed for authentication valid?
	 *
	 * @return bool
	 */
	public function isValid() {
		return $this->isValid;
	}

	/**
	 * @return User
	 */
	public function getUser() {
		return $this->user;
	}

}
