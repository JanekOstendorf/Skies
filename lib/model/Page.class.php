<?php
/**
 * @author    Janek Ostendorf (ozzy) <ozzy2345de@gmail.com>
 * @copyright Copyright (c) 2013 Janek Ostendorf
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU General Public License, version 3
 */

namespace skies\model;

use skies\system\template\ITemplateArray;

/**
 * Super page class
 */
abstract class Page implements ITemplateArray {

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
	 * @return array
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

		return \Skies::getPage() == $this;

	}

	/**
	 * @return array
	 */
	public function getData() {

		return $this->data;

	}

	/**
	 * Get an array suitable for assignment
	 *
	 * @return array
	 */
	public function getTemplateArray() {

		return [
			'name' => $this->getName(),
			'title' => $this->getTitle(),
			'isActive' => $this->isActive(),
			'data' => $this->getData(),
			'templateName' => $this->getTemplateName(),
			'parent' => null,
			'object' => $this
		];

	}

}
