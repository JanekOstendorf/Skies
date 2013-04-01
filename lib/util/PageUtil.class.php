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
	 * Get the current page
	 *
	 * @param array $arguments Array of URL arguments
	 * @return null|\skies\model\Page
	 */
	public static function getPageFromUrl(array $arguments) {

		// Fetch all page names
		$query = \Skies::getDb()->query('SELECT `pageName` FROM `page` WHERE 1');

		$pageNames = [];

		while($pageName = $query->fetchArray())
			$pageNames[] = $pageName['pageName'];

		// Look for the last argument being a page name
		$lastPageName = '';
		$i = 0;

		while(isset($arguments[$i]) && in_array($arguments[$i], $pageNames))
			$lastPageName = $arguments[$i++];

		// Default page
		if(empty($arguments[0])) {
			$lastPageName = \Skies::getConfig()['defaultPage'];
		}
		elseif(!in_array($arguments[0], $pageNames)) {
			throw new PageNotFoundException($arguments[0]);
		}

		return self::getPage($lastPageName);

	}

	public static function getPage($pageName) {

		// Fetch from the DB
		$query = \Skies::getDb()->prepare('SELECT * FROM `page` WHERE `pageName` = :name');
		$query->execute([':name' => $pageName]);

		if($query->getRowCount() == 1) {

			$data = $query->fetchArray();

			// Build the class name
			$pageClass = PAGE_NAMESPACE.$data['pageClass'];

			$page = new $pageClass($data);

			if($page instanceof Page) {
				return $page;
			}

		}

		return null;

	}

	public static function getPageById($pageId) {

		// Fetch from the DB
		$query = \Skies::getDb()->prepare('SELECT * FROM `page` WHERE `pageId` = :id');
		$query->execute([':id' => $pageId]);

		if($query->getRowCount() == 1) {

			$data = $query->fetchArray();

			// Build the class name
			$pageClass = PAGE_NAMESPACE.$data['pageClass'];

			$page = new $pageClass($data);

			if($page instanceof Page) {
				return $page;
			}

		}

		return null;

	}

}

?>
