<?php
/**
 * @author    Janek Ostendorf (ozzy) <ozzy2345de@gmail.com>
 * @copyright Copyright (c) 2013 Janek Ostendorf
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU General Public License, version 3
 */

namespace skies\system\exception;

/**
 * This is needed by the exception handler of WIP to display the message
 */
interface IPrintableException {

	/**
	 * Prints the exception.
	 */
	public function show();

}
