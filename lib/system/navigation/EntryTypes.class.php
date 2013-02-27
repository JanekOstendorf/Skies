<?php

namespace skies\system\navigation;

/**
 * @author    Janek Ostendorf (ozzy) <ozzy2345de@gmail.com>
 * @copyright Copyright (c) Janek Ostendorf
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU General Public License, version 3
 * @package
 */
class EntryTypes {

	/**
	 * Entry with given pageID
	 */
	const PAGE_ID = 1;

	/**
	 * Entry with given pageName. E.g. for system pages
	 */
	const PAGE_NAME = 2;

	/**
	 * Entry with given link URL
	 */
	const EXTERNAL_LINK = 3;

}

?>