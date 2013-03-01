<?php

namespace skies\util;

/**
 * @author    Janek Ostendorf (ozzy) <ozzy2345de@gmail.com>
 * @copyright Copyright (c) Janek Ostendorf
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU General Public License, version 3
 * @package   skies.util
 */
use skies\system\page\SystemPages;

class PageUtil {

	/**
	 * Gets the ID of the page with the given short name
	 *
	 * @param string $pageName Short name of the page
	 *
	 * @return int ID of the page. `-1` for system pages
	 */
	public static function getIdFromName($pageName) {

		if(SystemPages::isSystemPage($pageName)) {
			return -1;
		}

		$query = \Skies::$db->prepare('SELECT * FROM `page` WHERE `pageName` = :name');
		$query->execute([':name' => $pageName]);

		if($query->rowCount() != 1) {
			return false;
		}

		return $query->fetchArray()['pageID'];

	}

	/**
	 * Gets the name of the page with the given ID
	 *
	 * @param int $pageId ID of the page
	 *
	 * @return string Short name of the page
	 */
	public static function getNameFromId($pageId) {

		$query = \Skies::$db->prepare('SELECT * FROM `page` WHERE `pageID` = :pageId');
		$query->execute([':pageId' => $pageId]);

		if($query->rowCount() != 1) {
			return false;
		}

		return $query->fetchArray()['pageName'];

	}

	/**
	 * Gets the type of the page with the given ID
	 *
	 * @param int $id ID of the page
	 *
	 * @return int Type of the page
	 * @see \skies\system\page\PageTypes
	 */
	public static function getTypeFromId($id) {

		$query = \Skies::$db->prepare('SELECT * FROM `page` WHERE `pageID` = :id');
		$query->execute([':id' => $id]);

		if($query->rowCount() != 1) {
			return false;
		}

		return $query->fetchArray()['pageType'];

	}


}

?>
