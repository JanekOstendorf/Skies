<?php
/**
 * @author    Janek Ostendorf (ozzy) <ozzy2345de@gmail.com>
 * @copyright Copyright (c) 2013 Janek Ostendorf
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU General Public License, version 3
 */
 
namespace skies\model\page;

/**
 * Login page
 */
use skies\model\Page;

class LoginPage extends Page {

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
		return 'loginPage.tpl';
	}

	/**
	 * Get the name of this page (short form for the URL)
	 *
	 * @return string
	 */
	public function getName() {
		return 'login';
	}

	/**
	 * Get the title of this page.
	 *
	 * @return string
	 */
	public function getTitle() {

		return \Skies::$language->get('system.page.login.title');

	}
}
