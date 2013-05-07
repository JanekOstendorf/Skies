<?php
/**
 * @author    Janek Ostendorf (ozzy) <ozzy2345de@gmail.com>
 * @copyright Copyright (c) 2013 Janek Ostendorf
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU General Public License, version 3
 */

namespace skies\system\user\authentication;

use skies\system\protocol\ClientSslCertificate;
use skies\system\user\User;

/**
 *
 */
class SslCertificateAuthentication implements IAuthentication {

	/**
	 * @var bool
	 */
	protected $isValid = false;

	/**
	 * @var ClientSslCertificate
	 */
	protected $certificate = null;

	/**
	 * @var \skies\system\user\User
	 */
	protected $user = null;

	/**
	 * Gets SSL info from global variables and checks them
	 */
	public function __construct(ClientSslCertificate $certificate) {

		$this->certificate = $certificate;

		// Check for user
		if($this->certificate->isValid()) {

			$query = \Skies::getDb()->prepare('SELECT * FROM `user` WHERE `userCertSerial` = :serial');
			$query->execute([':serial' => $this->certificate->getSerial()]);

			if($query->getRowCount() == 1) {

				$this->isValid = true;

				// Create user object
				$this->user = new User($query->fetchArray()['userId']);

			}

		}

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
	 * @return \skies\system\user\User
	 */
	public function getUser() {
		return $this->user;
	}

}
