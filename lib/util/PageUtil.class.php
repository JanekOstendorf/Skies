<?php

namespace skies\util;

use skies\model\Page;

/**
 * @author    Janek Ostendorf (ozzy) <ozzy2345de@gmail.com>
 * @copyright Copyright (c) Janek Ostendorf
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU General Public License, version 3
 * @package   skies.util
 */
class PageUtil {

	public static function getPage($pageName) {

		// Fetch from the DB
		$query = \Skies::getDb()->prepare('SELECT * FROM `page` WHERE `pageName` = :name');
		$query->execute([':name' => $pageName]);

		if($query->rowCount() == 1) {

			$data = $query->fetchArray();

			// Build the class name
			$pageClass = 'skies\model\page\\'.$data['pageClass'];

			$page = new $pageClass($data);

			if($page instanceof Page) {
				return $page;
			}

		}

	}

}

?>
