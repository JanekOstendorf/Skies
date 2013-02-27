<?php

namespace skies\system\page;

/**
 * @author    Janek Ostendorf (ozzy) <ozzy2345de@gmail.com>
 * @copyright Copyright (c) Janek Ostendorf
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU General Public License, version 3
 * @package   skies.system.page
 */

class SystemPages {

	protected static $systemPages = [

		'login',
		'register'

	];

	/**
	 * Check if this page is a system page
	 *
	 * @static
	 *
	 * @param string $page_name Page name
	 *
	 * @return bool Is this page a system page?
	 */
	public static function isSystemPage($page_name) {

		return in_array($page_name, self::$systemPages);

	}

	/**
	 * Get the list of system pages
	 *
	 * @static
	 * @return array List of system pages
	 */
	public static function getSystemPages() {

		return self::$systemPages;

	}


}

?>