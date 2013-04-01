<?php
/**
 * @author    Janek Ostendorf (ozzy) <ozzy2345de@gmail.com>
 * @copyright Copyright (c) 2013 Janek Ostendorf
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU General Public License, version 3
 */

namespace skies\system\exception;

use skies\system\template\ITemplateArray;

/**
 * HTTP errros, e.g. 404
 */
abstract class ErrorException extends LoggedException implements IPrintableException, ITemplateArray {

	/**
	 * Show the error page
	 */
	public function show() {

		$this->logError();
		\Skies::getTemplate()->assign(['error' => $this->getTemplateArray()]);
		\Skies::getTemplate()->show('errors/index.tpl');

	}

	/**
	 * Get template file for this error
	 *
	 * @return string
	 */
	abstract public function getTemplateName();

	/**
	 * Error title. E.g. notFound: Page not found
	 *
	 * @return string
	 */
	abstract public function getTitle();

	/**
	 * Get error type, e.g. notFound
	 *
	 * @return string
	 */
	abstract public function getType();

}
