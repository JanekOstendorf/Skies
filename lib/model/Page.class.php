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
	 * Currently does nothing
	 */
	public function __construct() {

	}

	/**
	 * Prepare the output
	 *
	 * @return void
	 */
	abstract public function prepare();

	/**
	 * What's our style name?
	 *
	 * @return string
	 */
	abstract public function getTemplateName();

	/**
	 * Get the path to this page (short form for the URL)
	 *
	 * @return string[]
	 */
	abstract public function getPath();

	/**
	 * Get the title of this page.
	 *
	 * @return string
	 */
	abstract public function getTitle();

	/**
	 * Get the name of the page
	 *
	 * @return string
	 */
	abstract public function getName();

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
			'path' => $this->getPath(),
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
