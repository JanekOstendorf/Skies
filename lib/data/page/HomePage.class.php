<?php
/**
 * @author    Janek Ostendorf (ozzy) <ozzy2345de@gmail.com>
 * @copyright Copyright (c) 2013 Janek Ostendorf
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU General Public License, version 3
 */
 
namespace skies\data\page;

/**
 * Main page
 */
use skies\data\Page;

class HomePage extends Page {


	/**
	 * Prepare the output
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

		return 'homePage.tpl';

	}

	/**
	 * Get the name of this page (short form for the URL)
	 *
	 * @return string
	 */
	public function getName() {

		return $this->data['pageName'];

	}

	/**
	 * Get the title of this page.
	 *
	 * @return string
	 */
	public function getTitle() {

		return $this->data['pageTitle'];

	}
}
