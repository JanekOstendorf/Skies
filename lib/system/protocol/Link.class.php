<?php
/**
 * @author    Janek Ostendorf (ozzy) <ozzy2345de@gmail.com>
 * @copyright Copyright (c) 2013 Janek Ostendorf
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU General Public License, version 3
 */

namespace skies\system\protocol;

use skies\util\StringUtil;

/**
 * Class representing a link
 */
class Link {

	/**
	 * @var Uri
	 */
	protected $uri = null;

	/**
	 * @var array
	 */
	protected $pagePath = [];

	/**
	 * @var array
	 */
	protected $arguments = [];

	/**
	 * @var bool
	 */
	protected $keepArguments = false;

	/**
	 * @var string
	 */
	protected $linkString = '';

	/**
	 * @param Uri   $uri
	 * @param array $pagePath
	 * @param array $arguments
	 * @param bool  $keepArguments
	 */
	public function __construct(Uri $uri, array $pagePath, array $arguments = [], $keepArguments = false) {

		$this->uri = $uri;
		$this->pagePath = $pagePath;
		$this->arguments = $arguments;
		$this->keepArguments = $keepArguments;

		$this->generateLink();

	}

	/**
	 * Yea, make dat link
	 */
	protected function generateLink() {

		// Page path
		$pagePath = implode('/', $this->pagePath);
		$pageString = '';

		// Other arguments
		$arguments = '';

		switch($this->uri->getMethod()) {

			case Uri::METHOD_REWRITE:

				$pageString = $pagePath;

				foreach($this->arguments as $value) {
					$arguments .= '/'.StringUtil::encodeUri($value);
				}

				break;

			case Uri::METHOD_ARGUMENT:

				$pageString = '?page='.$pagePath;

				foreach($this->arguments as $key => $value) {
					if(is_int($key)) {
						$arguments .= '&amp;'.StringUtil::encodeUri($value);
					}
					else {
						$arguments .= '&amp;'.StringUtil::encodeUri($key).'='.StringUtil::encodeUri($value);
					}
				}

				break;
		}

		if($this->keepArguments) {

			$i = 0;
			foreach($_GET as $key => $value) {

				if($i++ == 0 && $this->uri->getMethod() == Uri::METHOD_REWRITE) {
					$arguments .= '?';
				}
				else {
					$arguments .= '&amp;';
				}

				$arguments .= StringUtil::encodeUri($key).'='.StringUtil::encodeUri($value);

			}

		}

		$this->linkString = '/'.SUBDIR.$pageString.$arguments;

	}

	/**
	 * @return string
	 */
	public function getRelativeUri() {
		return $this->linkString;
	}

}
