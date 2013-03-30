<?php
/**
 * @author    Janek Ostendorf (ozzy) <ozzy2345de@gmail.com>
 * @copyright Copyright (c) 2013 Janek Ostendorf
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU General Public License, version 3
 */

namespace skies\system\template;

/**
 * Classes with the getTemplateArray() method
 */
interface ITemplateArray {

	/**
	 * Get an array suitable for assignment
	 *
	 * @return array
	 */
	public function getTemplateArray();

}
