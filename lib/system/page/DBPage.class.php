<?php

namespace skies\system\page;

/**
 * @author    Janek Ostendorf (ozzy) <ozzy2345de@gmail.com>
 * @copyright Copyright (c) Janek Ostendorf
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU General Public License, version 3
 * @package   skies.system.page
 */
class DBPage extends Page {

	/**
	 * Content of the page
	 *
	 * @var string
	 */
	protected $content;

	protected function onInit() {

		$this->content = $this->data['pageContent'];

	}


	/**
	 * Shows the page content
	 *
	 * @throws \skies\system\exception\SystemException
	 * @return void
	 */
	public function show() {

		if($this->php) {

			if(@eval($this->content) === false) {

				throw new \skies\system\exception\SystemException('Error in the page "'.$this->name.'"', 0, 'PHP error in the content of the page "'.$this->name.'" (ID: '.$this->id.').');

			}

		}
		else {

			return print($this->content);

		}

	}

}

?>
