<?php
/**
 * @author    Janek Ostendorf (ozzy) <ozzy2345de@gmail.com>
 * @copyright Copyright (c) 2013 Janek Ostendorf
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU General Public License, version 3
 */

namespace skies\model\page;

use skies\model\SubPage;

/**
 * Testing subpages
 */
class SubHomePage extends SubPage {

	/**
	 * Prepare the output
	 *
	 * @return void
	 */
	public function prepare() {

	}

	/**
	 * What's our style name?
	 *
	 * @return string
	 */
	public function getTemplateName() {
		return 'subHomePage.tpl';
	}

	/**
	 * Get the name of this page (short form for the URL)
	 *
	 * @return array
	 */
	public function getPath() {
		$name = $this->getParent()->getPath();
		$name[] = $this->getName();

		return $name;
	}

	/**
	 * Get the title of this page.
	 *
	 * @return string
	 */
	public function getTitle() {
		return 'SubHome';
	}

	/**
	 * Get the name of the parent
	 *
	 * @return string
	 */
	public function getParentName() {
		return 'home';
	}

	/**
	 * Get the name of the page
	 *
	 * @return string
	 */
	public function getName() {
		return 'subHome';
	}

}
