<?php
/**
 * @author    Janek Ostendorf (ozzy) <ozzy2345de@gmail.com>
 * @copyright Copyright (c) 2013 Janek Ostendorf
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU General Public License, version 3
 */

namespace skies\util;

use skies\system\template\ITemplateArray;

/**
 * Template utilities
 */
class TemplateUtil {

	/**
	 * Convert an array of objects to an array suitable for template assignment
	 *
	 * @param ITemplateArray[] $objectArray Array of objects
	 * @return array Array suitable for assignment
	 */
	public static function convertArray($objectArray) {

		$return = [];

		foreach($objectArray as $object) {

			if($object instanceof ITemplateArray) {
				$return[] = $object->getTemplateArray();
			}

		}

		return $return;

	}

}
