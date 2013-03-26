<?php

namespace skies\system\navigation;

use skies\system\navigation\EntryTypes;
use skies\util\PageUtil;

/**
 * @author    Janek Ostendorf (ozzy) <ozzy2345de@gmail.com>
 * @copyright Copyright (c) Janek Ostendorf
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU General Public License, version 3
 * @package   skies.system.navigation
 */
class Navigation {

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

		if($query->rowCount() != 1) {

			return false;

		}

		$this->title = $query->fetchArray()['navTitle'];

		// Entries
		$entryQuery = \Skies::getDb()->prepare('SELECT * FROM `nav-entry` WHERE `navId` = :id ORDER BY `entryOrder` ASC');

		$entryQuery->execute([':id' => $this->id]);

		while($line = $entryQuery->fetchArray()) {

			$this->entries[] = [

				'id' => $line['entryId'],
				'order' => $line['entryOrder'],
				'link' => $line['entryLink'],
				'title' => $line['entryTitle'],
				'type' => $line['entryType'],
				'pageName' => $line['entryPageName']

			];

		}

	}

	public function prepareNav() {

		$i = 0;

		$entryCount = count($this->entries);

		$entries = [];

		foreach($this->entries as $entry) {

			// Tpl variables
			$vars = [];

			// Determine css classes of this entry
			if($i == 0) {
				$vars['first'] = true;
			}

			if($i == $entryCount - 1) {
				$vars['last'] = false;
			}

			// Type (internal/external link) dependant stuff
			switch($entry['type']) {

				case EntryTypes::PAGE:

					// Is this the current page?
					if(PageUtil::getPage($entry['pageName'])->isActive()) {
						$vars['active'] = true;
					}

					// Make the link
					$vars['link'] = '/'.SUBDIR.$entry['pageName'];

					break;

				case EntryTypes::EXTERNAL_LINK:

					// Make the link
					$vars['link'] = $entry['link'];

					break;

			}
			$vars['title'] = $entry['title'];

			$entries[$i++] = $vars;

		}

		\Skies::getTemplate()->assign(['nav' => ['entries' => $entries]]);

	}

}

?>
