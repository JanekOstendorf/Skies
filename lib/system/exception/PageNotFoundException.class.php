<?php
/**
 * @author    Janek Ostendorf (ozzy) <ozzy2345de@gmail.com>
 * @copyright Copyright (c) 2013 Janek Ostendorf
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU General Public License, version 3
 */

namespace skies\system\exception;

/**
 *
 */
class PageNotFoundException extends ErrorException {

	/**
	 * The name of the page not found
	 *
	 * @var string
	 */
	protected $pageName = '';

	/**
	 * @param string $pageName Name of the page not found
	 */
	public function __construct($pageName) {

		$this->pageName = $pageName;
		$message = \Skies::getLanguage()->get('system.error.'.$this->getType().'.message', ['pageName' => $pageName]);

		parent::__construct($message);

	}

	/**
	 * Get template file for this error
	 *
	 * @return string
	 */
	public function getTemplateName() {
		return 'genericError.tpl';
	}

	/**
	 * Error title. E.g. notFound: Page not found
	 *
	 * @return string
	 */
	public function getTitle() {
		return \Skies::getLanguage()->get('system.error.'.$this->getType().'.title');
	}

	/**
	 * Get error type, e.g. notFound
	 *
	 * @return string
	 */
	public function getType() {
		return 'pageNotFound';
	}

	/**
	 * Get an array suitable for assignment
	 *
	 * @return array
	 */
	public function getTemplateArray() {
		return [
			'type' => $this->getType(),
			'title' => $this->getTitle(),
			'pageName' => $this->pageName,
			'message' => $this->getMessage(),
			'templateName' => $this->getTemplateName(),
			'object' => $this
		];
	}

}
