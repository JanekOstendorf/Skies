<?php
/**
 * @author    Janek Ostendorf (ozzy) <ozzy2345de@gmail.com>
 * @copyright Copyright (c) 2013 Janek Ostendorf
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU General Public License, version 3
 */

namespace skies\system\protocol;

/**
 * Class handling different types of URIs
 * Inspired by SilexBoard by Patrick Kleinschmidt (NoxNebula)
 */
class Uri {

	const METHOD_ARGUMENT = 1;

	const METHOD_REWRITE = 2;

	/**
	 * URI method used (constants)
	 *
	 * @var int
	 */
	protected $method = 0;

	/**
	 * Normal GET arguments
	 *
	 * @var array
	 */
	protected $getArguments = [];

	/**
	 * Arguments passed by URI/URL rewriting
	 *
	 * @var array
	 */
	protected $rewriteArguments = [];

	/**
	 * POST arguments
	 *
	 * @var array
	 */
	protected $postArguments = [];

	/**
	 * @param int $method URI method used
	 */
	public function __construct($method) {

		// Always argument mode in API mode
		if(\Skies::isApiMode()) {
			$this->method = self::METHOD_ARGUMENT;
		}
		else {
			$this->method = $method;
		}

		$this->readGetArguments();
		$this->readPostArguments();
		$this->readRewriteArguments();

	}

	/**
	 * Read the GET arguments from $_GET
	 */
	protected function readGetArguments() {

		if(isset($_GET) && !empty($_GET)) {

			foreach($_GET as $key => $curArg) {

				// Rewrite arguments
				if($key == '__0') {
					continue;
				}

				// TODO: Escape maybe?
				$this->getArguments[$key] = (empty($curArg) ? true : $curArg);

			}

		}

	}

	/**
	 * Read arguments passed by rewriting the URL
	 */
	protected function readRewriteArguments() {

		if(isset($_GET) && isset($_GET['__0'])) {

			$argString = $_GET['__0'];

			// Remove slashes from start and end
			$argString = trim($argString, '/');

			// Split
			$args = explode('/', $argString);

			$i = 0;

			// Store
			foreach($args as $argument) {
				$this->rewriteArguments[$i++] = $argument;
			}

			$_GET['__0'] = null;
			unset($_GET['__0']);

		}

	}

	/**
	 * Read POST arguments
	 */
	protected function readPostArguments() {

		if(isset($_POST) && !empty($_POST)) {

			foreach($_POST as $key => $curArg) {

				// TODO: Escape maybe?
				$this->postArguments[$key] = $curArg;

			}

		}

	}

	/**
	 * Get argument dependant on used method
	 *
	 * @param int    $argumentPosition Position of the argument in the rewrite URL
	 * @param string $argumentName     Name of the argument when using the ARG method
	 * @return mixed|null NULL if not existant
	 */
	public function getArgument($argumentPosition, $argumentName) {

		switch($this->method) {

			case self::METHOD_ARGUMENT:

				if(isset($this->getArguments[$argumentName])) {
					return ($this->getArguments[$argumentName] === true ? $argumentName : $this->getArguments[$argumentName]);
				}

				break;

			case self::METHOD_REWRITE:

				if(isset($this->rewriteArguments[$argumentPosition])) {
					return $this->rewriteArguments[$argumentPosition];
				}
				elseif(isset($this->getArguments[$argumentName])) {
					return $this->getArguments[$argumentName];
				}

				break;

		}

		return false;

	}

	/**
	 * Get a post argument
	 *
	 * @param string $argumentName POST argument name
	 * @return mixed Value
	 */
	public function getPost($argumentName) {
		return (isset($this->postArguments[$argumentName]) ? $this->postArguments[$argumentName] : false);
	}

	/**
	 * @return int
	 */
	public function getMethod() {
		return $this->method;
	}

	/**
	 * Generate a link
	 *
	 * @param array $pagePath
	 * @param array $arguments
	 * @param bool  $keepGetArguments
	 * @return Link
	 */
	public function getLink(array $pagePath, array $arguments = [], $keepGetArguments = false) {
		return new Link($this, $pagePath, $arguments, $keepGetArguments);
	}

}
