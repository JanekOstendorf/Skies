<?php
/**
 * @author    Janek Ostendorf (ozzy) <ozzy2345de@gmail.com>
 * @copyright Copyright (c) 2013 Janek Ostendorf
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU General Public License, version 3
 */

namespace skies\model\style;

/**
 * Class for custom style code
 */
abstract class StyleScript {

	/**
	 * The style this script belongs to
	 *
	 * @var Style
	 */
	protected $style = null;

	/**
	 * @param Style $style
	 */
	public function __construct(Style &$style) {

		$this->style = $style;

	}

	/**
	 * Do dem prepare things
	 *
	 * @return void
	 */
	abstract public function prepare();

}
