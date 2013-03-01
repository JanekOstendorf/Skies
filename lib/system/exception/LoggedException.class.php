<?php
/**
 * @author    Janek Ostendorf (ozzy) <ozzy2345de@gmail.com>
 * @copyright Copyright (c) 2013 Janek Ostendorf
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU General Public License, version 3
 */

namespace skies\system\exception;

use skies\util\StringUtil;

/**
 * Simple parent class for logging exceptions.
 */
class LoggedException extends \Exception {

	/**
	 * Error description
	 *
	 * @var string
	 */
	protected $description = '';

	/**
	 * Additional information
	 *
	 * @var string
	 */
	protected $information = '';

	/**
	 * Hide the real message when we're not in debug mode
	 *
	 * @see \Exception::getMessage()
	 * @return string
	 */
	public function _getMessage() {

		// You're not supposed to see this, when not debugging
		if(!\Skies::isDebugMode()) {
			return 'This is an error. Is it not supposed to be here, neither are you.';
		}

		// Exception to use
		$e = ($this->getPrevious() ? : $this);

		return $e->getMessage();

	}

	/**
	 * Writes the exception to a log file.
	 *
	 * @return string Error ID
	 */
	protected function logError() {

		// Logfile with complete path
		$logFile = ROOT_DIR.'logs/'.date('d-m-Y', NOW).'.txt';

		// Create the file
		@touch($logFile);

		// Does it exist and is writable?
		if(!file_exists($logFile) || !is_writable($logFile)) {

			// SERVER ADMIN, Y U DO SUCH SHIT?!
			return null;

		}

		// Exception to use
		$e = ($this->getPrevious() ? : $this);

		// Build the message
		$text = date('r', NOW).EOL.
		        'Message: '.$e->getMessage().EOL.
		        'Description: '.$this->description.EOL.
		        'File: '.$e->getFile().':'.$e->getLine().EOL.
		        'Request URI: '.(isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '').EOL.
		        'Referrer: '.(isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '').EOL.
		        'Additional information: '.EOL.$this->information.EOL.EOL.
		        'Stacktrace: '.EOL.implode(EOL.'  ', explode("\n", $e->getTraceAsString())).EOL;

		// Get the ID for this exception
		$id      = StringUtil::getHash($text);
		$message = '++++++ '.$id.' ++++++'.EOL.$text.'++++++'.EOL.EOL;

		// Finally, write it to the log file
		@file_put_contents($logFile, $message, FILE_APPEND);

		// Give them our ID
		return $id;

	}

}
