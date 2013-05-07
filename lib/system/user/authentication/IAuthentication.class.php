<?php
/**
 * @author    Janek Ostendorf (ozzy) <ozzy2345de@gmail.com>
 * @copyright Copyright (c) 2013 Janek Ostendorf
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU General Public License, version 3
 */

namespace skies\system\user\authentication;

/**
 *
 */
interface IAuthentication {

	/**
	 * Are the arguments passed for authentication valid?
	 *
	 * @return bool
	 */
	public function isValid();

	/**
	 * @return \skies\system\user\User
	 */
	public function getUser();

}
