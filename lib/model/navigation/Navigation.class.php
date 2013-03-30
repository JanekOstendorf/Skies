<?php

namespace skies\model\navigation;

use skies\model\navigation\EntryTypes;
use skies\system\template\ITemplateArray;

/**
 * @author    Janek Ostendorf (ozzy) <ozzy2345de@gmail.com>
 * @copyright Copyright (c) Janek Ostendorf
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU General Public License, version 3
 * @package   skies.system.navigation
 */
class Navigation implements ITemplateArray {

	/**
	 * Entries of this navigation
	 *
	 * @var array
	 */
	protected $entries = [];

	/**
	 * ID of this nav
	 *
	 * @var int
	 */
	protected $id = 0;

	/**
	 * Title of this navigation
	 *
	 * @var string
	 */
	protected $title = '';

	/**
	 * Does this nav include the LoginForm?
	 *
	 * @var bool
	 */
	protected $loginForm = false;

	/**
	 * Init the nav and fetch all model
	 *
	 * @param int $id ID of the navigation
	 */
	public function __construct($id) {

		$this->id = $id;

		// Data about this nav
		$query = \Skies::getDb()->prepare('SELECT * FROM `nav` WHERE `navId` = :id');
		$query->execute([':id' => $this->id]);

		if($query->getRowCount() != 1) {

			return false;

		}

		$this->title = $query->fetchArray()['navTitle'];

	}

	protected function getEntries($parentId = 0, $depth = 0) {

		$query = \Skies::getDb()->prepare('SELECT * FROM `nav-entry` WHERE `entryNavId` = :navId AND `entryParentEntryId` = :parentId ORDER BY `entryOrder`');
		$query->execute([':navId' => $this->id, ':parentId' => $parentId]);
		$data = $query->fetchAllObject();

		$entries = [];

		$i = 0;

		$entryCount = count($data);

		foreach($data as $entry) {

			$curEntry = null;
			$first = false;
			$last = false;

			// Determine css classes of this entry
			if($i == 0) {
				$first = true;
			}

			if($i == $entryCount - 1) {
				$last = true;
			}

			switch($entry->entryType) {

				case EntryTypes::EXTERNAL_LINK:
					$curEntry = new LinkNavigationEntry($this, (array)$entry, $first, $last);
					break;

				case EntryTypes::PAGE:
					$curEntry = new PageNavigationEntry($this, (array)$entry, $first, $last);
					break;

			}

			if(!($curEntry instanceof NavigationEntry)) {
				continue;
			}

			$entries[] = [
				'entry' => $curEntry->getTemplateArray(),
				'subEntries' => $depth < 3 ? $this->getEntries($entry->entryId, $depth++) : null
			];

			$i++;

		}

		return $entries;

	}

	/**
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @return string
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * Get an array suitable for assignment
	 *
	 * @return array
	 */
	public function getTemplateArray() {
		return [
			'id' => $this->id,
			'title' => $this->title,
			'entries' => $this->getEntries()
		];
	}

}

?>
