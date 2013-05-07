<?php
/**
 * @author    Janek Ostendorf (ozzy) <ozzy2345de@gmail.com>
 * @copyright Copyright (c) 2013 Janek Ostendorf
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU General Public License, version 3
 */

namespace skies\system\protocol;

/**
 *
 */
class ClientSslCertificate {

	/**
	 * Is there a certificate?
	 *
	 * @var bool
	 */
	protected $isExisting = false;

	/**
	 * Name of the owner
	 *
	 * @var string
	 */
	protected $subjectName = '';

	/**
	 * End time of certificate validity
	 *
	 * @var string
	 */
	protected $validityEndTime = 0;

	/**
	 * Serial number of this certificate
	 *
	 * @var string
	 */
	protected $serial = '';

	public function __construct() {

		// Read from $_SERVER
		if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {

			// Subject's name
			if(isset($_SERVER['SSL_CLIENT_S_DN'])) {

				$this->isExisting = true;

				// Look for the name
				foreach(explode(',', $_SERVER['SSL_CLIENT_S_DN']) as $value) {

					if(substr($value, 0, 3) == 'CN=') {
						$this->subjectName = substr($value, 3);
					}

				}

			}

			// Validity end time
			if(isset($_SERVER['SSL_CLIENT_V_END'])) {

				$this->isExisting = true;

				// Parse time
				$this->validityEndTime = strtotime($_SERVER['SSL_CLIENT_V_END']);

			}

			// Serial
			if(isset($_SERVER['SSL_CLIENT_M_SERIAL'])) {

				$this->isExisting = true;
				$this->serial = $_SERVER['SSL_CLIENT_M_SERIAL'];

			}

		}

	}

	/**
	 * Is this cert valid?
	 *
	 * @return bool
	 */
	public function isValid() {
		return !empty($this->serial) && $this->isExisting && $this->validityEndTime > NOW;
	}

	/**
	 * @return string
	 */
	public function getSerial() {
		return $this->serial;

	}

	/**
	 * @return string
	 */
	public function getSubjectName() {
		return $this->subjectName;

	}

	/**
	 * @return string
	 */
	public function getValidityEndTime() {
		return $this->validityEndTime;

	}

}
