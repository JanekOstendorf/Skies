<?php

namespace skies\util;

use skies\model\Page;
use skies\system\exception\PageNotFoundException;
use skies\system\protocol\Uri;

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

	public static function getPageFromUrl(Uri $uri) {

		$pageName = '';

		switch($uri->getMethod()) {

			case Uri::METHOD_REWRITE:

				if($uri->getArgument(0, '') == null) {
					$pageName = \Skies::getConfig()['defaultPage'];
				}
				elseif(!isset(self::$pageObjects[$uri->getArgument(0, '')])) {
					throw new PageNotFoundException($uri->getArgument(0, ''));
				}
				else {

					// Look for the last argument being a page name
					$i = 0;

					while($uri->getArgument($i, '') !== false && isset(self::$pageObjects[$uri->getArgument($i, '')])) {
						$pageName = $uri->getArgument($i++, '');
					}

				}

				break;

			case Uri::METHOD_ARGUMENT:

				$arguments = explode('/', trim($uri->getArgument(0, 'page', '/')));

				// Default page
				if(empty($arguments[0]) || !isset($arguments[0])) {
					$pageName = \Skies::getConfig()['defaultPage'];
				}
				elseif(!isset(self::$pageObjects[$arguments[0]])) {
					throw new PageNotFoundException($arguments[0]);
				}
				else {

					$i = 0;

					while(isset($arguments[$i]) && isset(self::$pageObjects[$arguments[$i]]))
						$pageName = $arguments[$i++];

				}

				break;

		}

		return self::getPage($pageName);

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
