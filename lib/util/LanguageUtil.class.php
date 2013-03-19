<?php
/**
 * @author    Janek Ostendorf (ozzy) <ozzy2345de@gmail.com>
 * @copyright Copyright (c) 2013 Janek Ostendorf
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU General Public License, version 3
 */
 
namespace skies\util;

/**
 * Language utilities
 */
use skies\system\language\Language;

class LanguageUtil {

	/**
	 * @return null|\skies\system\language\Language Default language or null
	 */
	public static function getDefaultLanguage() {

		$language = new Language(\Skies::getConfig()['defaultLanguage'], true, true);

		if($language instanceof Language)
			return $language;

		return null;

	}

	/**
	 * Get all available languages
	 *
	 * @return \skies\system\language\Language[]
	 */
	public static function getAllLanguages() {

		// Scan directory
		$dirs = scandir(ROOT_DIR.DIR_LANGUAGE);

		$languages = [];

		foreach($dirs as $curDir) {

			if(in_array($curDir, ['.', '..']))
				continue;

			// If there's and language.yml it should be a valid language
			if(is_dir(ROOT_DIR.DIR_LANGUAGE.$curDir) && file_exists(ROOT_DIR.DIR_LANGUAGE.$curDir.'/language.yml')) {
				if($curDir == \Skies::getConfig()['defaultLanguage'])
					$languages[] = new Language($curDir, false, true);
				else
					$languages[] = new Language($curDir, false, false);
			}

		}

		return $languages;

	}

}
