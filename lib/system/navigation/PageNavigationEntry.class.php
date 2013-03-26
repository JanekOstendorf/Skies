<?php
/**
 * @author    Janek Ostendorf (ozzy) <ozzy2345de@gmail.com>
 * @copyright Copyright (c) 2013 Janek Ostendorf
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU General Public License, version 3
 */

namespace skies\system\navigation;

use skies\util\PageUtil;

/**
 * Navigation entry referring to a page
 */
class PageNavigationEntry extends NavigationEntry {

	/**
	 * @var \skies\model\Page
	 */
	protected $page = null;

	/**
	 * @return \skies\model\Page
	 */
	protected function getPage() {

		if($this->page === null) {
			$this->page = PageUtil::getPage($this->data['entryPageName']);
		}

		return $this->page;

	}

	/**
	 * @return string
	 */
	public function getLink() {

		return '/'.SUBDIR.$this->data['entryPageName'];

	}

	/**
	 * @return bool
	 */
	public function isActive() {

		return $this->getPage()->isActive();

	}

	/**
	 * @return array
	 */
	public function getTemplateArray() {
		return [
			'id' => $this->id,
			'order' => $this->order,
			'title' => $this->title,
			'parent' => ($this->parent !== null ? $this->parent->getTemplateArray() : null),
			'isActive' => $this->isActive(),
			'link' => $this->getLink(),
			'isFirst' => $this->first,
			'isLast' => $this->last,
			'page' => $this->getPage()->getTemplateArray(),
			'object' => $this
		];
	}
}
