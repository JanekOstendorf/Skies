<?php
/**
 * @author    Janek Ostendorf (ozzy) <ozzy2345de@gmail.com>
 * @copyright Copyright (c) 2013 Janek Ostendorf
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU General Public License, version 3
 */

namespace skies\model\navigation;

use skies\system\template\ITemplateArray;

/**
 * A navigation entry
 */
abstract class NavigationEntry implements ITemplateArray {

	/**
	 * ID of this entry
	 *
	 * @var int
	 */
	protected $id = 0;

	/**
	 * Order number
	 *
	 * @var int
	 */
	protected $order = 0;

	/**
	 * Title of this entry
	 *
	 * @var string
	 */
	protected $title = '';

	/**
	 * Parent, if existent
	 *
	 * @var NavigationEntry|null
	 */
	protected $parent = null;

	/**
	 * Navigation this entry belongs to
	 *
	 * @var Navigation
	 */
	protected $navigation = null;

	/**
	 * First entry?
	 *
	 * @var bool
	 */
	protected $first = false;

	/**
	 * Last entry?
	 *
	 * @var bool
	 */
	protected $last = false;

	/**
	 * Data (row from the DB)
	 *
	 * @var array
	 */
	protected $data = [];

	public function __construct(Navigation $navigation, $data, $first = false, $last = false, NavigationEntry $parent = null) {

		$this->navigation =& $navigation;
		$this->data = $data;
		$this->parent = $parent;

		$this->first = $first;
		$this->last = $last;

		$this->id = $this->data['entryId'];
		$this->title = $this->data['entryTitle'];

	}

	/**
	 * Is this entry active?
	 *
	 * @return bool
	 */
	public abstract function isActive();

	/**
	 * Get the link of this entry
	 *
	 * @return string
	 */
	public abstract function getLink();

	/**
	 * @return array
	 */
	public abstract function getTemplateArray();

	/**
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @return \skies\model\navigation\Navigation
	 */
	public function getNavigation() {
		return $this->navigation;
	}

	/**
	 * @return int
	 */
	public function getOrder() {
		return $this->order;
	}

	/**
	 * @return null|\skies\model\navigation\NavigationEntry
	 */
	public function getParent() {
		return $this->parent;
	}

	/**
	 * @return string
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * @return bool
	 */
	public function isFirst() {
		return $this->first;
	}

	/**
	 * @return bool
	 */
	public function isLast() {
		return $this->last;
	}

}
