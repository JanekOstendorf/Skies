<?php
/**
 * @author    Janek Ostendorf (ozzy) <ozzy2345de@gmail.com>
 * @copyright Copyright (c) 2013 Janek Ostendorf
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU General Public License, version 3
 */
 
namespace skies\util;

/**
 * Language utilites
 */
use skies\system\language\Language;

class LanguageUtil {

	/**
	 * @return null|\skies\system\language\Language Default language or null
	 */
	public static function getDefaultLanguage() {

		$query = \Skies::getDb()->prepare('SELECT * FROM `language` WHERE `langName` = :name');
		$query->execute([':name' => \Skies::getConfig()['defaultLanguage']]);
		$langId = $query->fetchArray()['langId'];

		$language = new Language($langId, true);

		if($language instanceof Language)
			return $language;

		return null;

	}

}
