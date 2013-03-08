<?php
/**
 * @author    Janek Ostendorf (ozzy) <ozzy2345de@gmail.com>
 * @copyright Copyright (c) 2013 Janek Ostendorf
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU General Public License, version 3
 */
 
namespace skies\model;

/**
 * Super page class
 */
abstract class Page {

	protected $templateName = '';

	protected $data = [];

	/**
	 * @param array $data Data of this page (row from the DB)
	 */
	public function __construct(array $data) {

		$this->data = $data;

	}

	/**
	 * Prepare the output
	 *
	 * @return void
	 */
	abstract function prepare();

	/**
	 * What's our style name?
	 *
	 * @return string
	 */
	abstract function getTemplateName();

	/**
	 * Get the name of this page (short form for the URL)
	 *
	 * @return string
	 */
	abstract function getName();

	/**
	 * Get the title of this page.
	 *
	 * @return string
	 */
	abstract function getTitle();

	/**
	 * Is this page active/shown?
	 *
	 * @return bool
	 */
	public function isActive() {

		return \Skies::$page == $this;

	}

	/**
	 * @return array
	 */
	public function getData() {

		return $this->data;

	}

}
