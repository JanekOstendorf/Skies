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

		if($this->data['pageParentId'] != 0 && $this->parent === null) {
			$this->parent = PageUtil::getPageById($this->data['pageParentId']);
		}

		return $this->parent;

	}

	/**
	 * @return array
	 */
	public function getTemplateArray() {

		return [
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
