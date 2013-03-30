<?php
/**
 * @author    Janek Ostendorf (ozzy) <ozzy2345de@gmail.com>
 * @copyright Copyright (c) 2013 Janek Ostendorf
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU General Public License, version 3
 */

namespace skies\model\navigation;

/**
 *
 */
class LinkNavigationEntry extends NavigationEntry {

	/**
	 * Is this entry active?
	 *
	 * @return bool
	 */
	public function isActive() {
		// Links are never active
		return false;
	}

	/**
	 * Get the link of this entry
	 *
	 * @return string
	 */
	public function getLink() {
		return $this->data['entryLink'];
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
			'isFirst' => $this->first,
			'isLast' => $this->last,
			'link' => $this->getLink(),
			'object' => $this
		];
	}

}
