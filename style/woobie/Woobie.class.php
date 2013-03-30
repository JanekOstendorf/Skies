<?php
/**
 * @author    Janek Ostendorf (ozzy) <ozzy2345de@gmail.com>
 * @copyright Copyright (c) 2013 Janek Ostendorf
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU General Public License, version 3
 */

namespace skies\style\woobie;

use skies\model\style\StyleScript;

/**
 * Woobie!
 */
class Woobie extends StyleScript {

	/**
	 * Do dem prepare things
	 *
	 * @return void
	 */
	public function prepare() {

		// Fetch Git version
		\Skies::getTemplate()->assign(['gitHash' => $this->getGitHash()]);

	}

	/**
	 * SHA hash of the current commit. Null if there is no repo
	 *
	 * @return string|null
	 */
	private function getGitHash() {

		$fileName = ROOT_DIR.'.git/logs/HEAD';

		if(!file_exists($fileName)) {
			return null;
		}

		$file = file($fileName);
		$line = explode(' ', $file[sizeof($file) - 1]);
		$hash = $line[1];

		return $hash;

	}

}
