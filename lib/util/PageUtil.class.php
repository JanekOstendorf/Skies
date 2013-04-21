<?php

namespace skies\util;

use skies\model\Page;
use skies\system\exception\PageNotFoundException;

/**
 * @author    Janek Ostendorf (ozzy) <ozzy2345de@gmail.com>
 * @copyright Copyright (c) Janek Ostendorf
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU General Public License, version 3
 * @package   skies.util
 */
class PageUtil {

	/**
	 * @var array
	 */
	protected static $pageClasses = [
		'HomePage',
		'LoginPage',
		'SubHomePage'
	];

	/**
	 * Page objects. Key is the name
	 *
	 * @var Page[]
	 */
	protected static $pageObjects = [];

	/**
	 * Initialize and instance all pages
	 */
	public static function init() {

		// Create each object
		foreach(self::$pageClasses as $pageClass) {

			$pageClass = PAGE_NAMESPACE.$pageClass;

			$page = new $pageClass();

			if($page instanceof Page) {
				self::$pageObjects[$page->getName()] = $page;
			}

		}

	}

	public static function getPageFromUrl(array $arguments) {

		// Look for the last argument being a page name
		$lastPageName = '';
		$i = 0;

		while(isset($arguments[$i]) && isset(self::$pageObjects[$arguments[$i]]))
			$lastPageName = $arguments[$i++];

		// Default page
		if(empty($arguments[0])) {
			$lastPageName = \Skies::getConfig()['defaultPage'];
		}
		elseif(!isset(self::$pageObjects[$arguments[0]])) {
			throw new PageNotFoundException($arguments[0]);
		}

		return self::getPage($lastPageName);

	}

	/**
	 * Get a page by page name
	 *
	 * @param string $pageName
	 * @return Page|null Null if not exists
	 */
	public static function getPage($pageName) {

		return (isset(self::$pageObjects[$pageName]) ? self::$pageObjects[$pageName] : null);

	}

}

?>
