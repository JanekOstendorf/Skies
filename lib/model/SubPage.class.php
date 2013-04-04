<?php
/**
 * @author    Janek Ostendorf (ozzy) <ozzy2345de@gmail.com>
 * @copyright Copyright (c) 2013 Janek Ostendorf
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU General Public License, version 3
 */

namespace skies\model;

use skies\system\template\ITemplateArray;
use skies\util\PageUtil;

/**
 * Sub pages
 */
abstract class SubPage extends Page implements ITemplateArray {

	/**
	 * Parent page
	 *
	 * @var Page
	 */
	private $parent = null;

	/**
	 * @return Page
	 */
	public function getParent() {

		if($this->getParentName() != null) {
			$this->parent = PageUtil::getPage($this->getParentName());
		}

		return $this->parent;

	}

	/**
	 * Get the name of the parent
	 *
	 * @return string
	 */
	abstract public function getParentName();

	/**
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
			'parent' => $this->getParent()->getTemplateArray(),
			'object' => $this
		];

	}

}
