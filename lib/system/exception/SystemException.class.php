<?php

namespace skies\system\exception;

use skies\util\StringUtil;

date_default_timezone_set('Europe/Berlin');

/**
 * @author    Janek Ostendorf (ozzy) <ozzy2345de@gmail.com>
 * @copyright Copyright (c) Janek Ostendorf
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU General Public License, version 3
 * @package   skies.system.exception
 */
class SystemException extends \Exception {

	/**
	 * Exception message
	 *
	 * @var string
	 */
	protected $message = '';

	/**
	 * Exception code
	 *
	 * @var int
	 */
	protected $code = 0;

	/**
	 * Filename where the exception was thrown
	 *
	 * @var string
	 */
	protected $file = '';

	/**
	 * Line where the exception was thrown
	 *
	 * @var int
	 */
	protected $line = 0;

	/**
	 * Exception description
	 *
	 * @var string
	 */
	protected $description = '';


	/**
	 * @param string     $message     Error message
	 * @param int        $code        Error code
	 * @param string     $description Error description
	 * @param \Exception $previous    Repacked error
	 */
	public function __construct($message = '', $code = 0, $description = '', \Exception $previous = null) {

		parent::__construct($message, $code, $previous);
		$this->description = $description;

	}

	/**
	 * Removes database password from stack trace.
	 *
	 * @see    \Exception::getTraceAsString()
	 */
	public function __getTraceAsString() {

		$e      = ($this->getPrevious() ? : $this);
		$string = preg_replace('/MySQL->\_\_construct\(.*\)/', 'MySQL->__construct(...)', $e->getTraceAsString());
		$string = preg_replace('/mysqli->mysqli\(.*\)/', 'mysqli->mysqli(...)', $string);

		return $string;
	}

	public function show() {

		// Log it!
		$exceptionID = $this->logError();

		// Status code
		@header('HTTP/1.1 503 Services Unavailable');

		// Print report
		echo '<?xml version="1.0" encoding="UTF-8"?>';
		$e = ($this->getPrevious() ? : $this);
		?>

	<!DOCTYPE html>
	<html>
		<head>
			<title>Fatal error: <?=StringUtil::encodeHTML($this->getMessage())?></title>
			<style>
				.systemException {
					font-family: 'Trebuchet MS', Arial, sans-serif !important;
					font-size: 80% !important;
					text-align: left !important;
					border: 1px solid #036;
					border-radius: 7px;
					background-color: #eee !important;
					overflow: auto !important;
				}

				.systemException h1 {
					font-size: 130% !important;
					font-weight: bold !important;
					line-height: 1.1 !important;
					text-decoration: none !important;
					text-shadow: 0 -1px 0 #003 !important;
					color: #fff !important;
					word-wrap: break-word !important;
					border-bottom: 1px solid #036;
					border-top-right-radius: 6px;
					border-top-left-radius: 6px;
					background-color: #369 !important;
					margin: 0 !important;
					padding: 5px 10px !important;
				}

				.systemException div {
					border-top: 1px solid #fff;
					border-bottom-right-radius: 6px;
					border-bottom-left-radius: 6px;
					padding: 10px !important;
				}

				.systemException h2 {
					font-size: 130% !important;
					font-weight: bold !important;
					color: #369 !important;
					text-shadow: 0 1px 0 #fff !important;
					margin: 5px 0 !important;
				}

				.systemException pre, .systemException p {
					text-shadow: none !important;
					color: #555 !important;
					margin: 0 !important;
				}

				.systemException pre {
					font-size: .85em !important;
					font-family: "Courier New", monospace !important;
					text-overflow: ellipsis;
					padding-bottom: 1px;
					overflow: hidden !important;
				}

				.systemException pre:hover {
					text-overflow: clip;
					overflow: auto !important;
				}
			</style>
		</head>
		<body>
			<div class="systemException">
				<h1>Fatal error: <?=StringUtil::encodeHTML($this->_getMessage())?></h1>

				<?php if(DEBUG) { ?>

				<div>
					<p><?=StringUtil::encodeHTML($this->getDescription())?></p>

					<h2>Information:</h2>

					<p>
						<b>id:</b> <code><?=$exceptionID?></code><br>
						<b>error message:</b> <?=StringUtil::encodeHTML($this->_getMessage())?><br>
						<b>error code:</b> <?= intval($e->getCode())?><br>
						<b>file:</b> <?= StringUtil::encodeHTML($e->getFile())?> (<?= $e->getLine(); ?>)<br>
						<b>php version:</b> <?=StringUtil::encodeHTML(phpversion())?><br>
						<b>skies version:</b> <?=VERSION?><br>
						<b>date:</b> <?=gmdate('r'); ?><br>
						<b>request:</b> <?php if(isset($_SERVER['REQUEST_URI']))
						echo StringUtil::encodeHTML($_SERVER['REQUEST_URI']); ?>
						<br>
						<b>referrer:</b> <?php if(isset($_SERVER['HTTP_REFERER']))
						echo StringUtil::encodeHTML($_SERVER['HTTP_REFERER']); ?>
						<br>
					</p>

					<h2>Stacktrace:</h2>
					<pre><?=StringUtil::encodeHTML($this->__getTraceAsString()); ?></pre>
				</div>

				<?php
			}
			else {
				?>

				<div>
					<h2>Information:</h2>

					<p>
						<b>id:</b> <code><?=$exceptionID?></code><br>
						Send this ID to the administrator of this website to report this issue.
					</p>
				</div>

				<?php } ?>

			</div>
		</body>
	</html>
	<?php

	}

	protected function getDescription() {

		return $this->description;

	}

	/**
	 * Suppresses the original error message.
	 *
	 * @see        \Exception::getMessage()
	 */
	public function _getMessage() {

		if(!DEBUG) {
			return 'An error occurred. Sorry.';
		}

		$e = ($this->getPrevious() ? : $this);

		return $e->getMessage();
	}

	protected function logError() {

		$logFile = ROOT_DIR.'/log/'.date('Y-m-d', NOW).'.txt';

		// try to create file
		@touch($logFile);

		// validate if file exists and is accessible for us
		if(!file_exists($logFile) || !is_writable($logFile)) {
			/*
				   We cannot recover if we reached this point, the server admin
				   is urged to fix his pretty much broken configuration.

				   GLaDOS: Look at you, sailing through the air majestically, like an eagle... piloting a blimp.
			   */
			return;
		}

		$e = ($this->getPrevious() ? : $this);

		$message = date('r', NOW)."\n".
		           'Message: '.$e->getMessage()."\n".
		           'File: '.$e->getFile().' ('.$e->getLine().")\n".
		           'PHP version: '.phpversion()."\n".
		           'Skies version: '.VERSION."\n".
		           'Request URI: '.(isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '')."\n".
		           'Referrer: '.(isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '')."\n".
		           "Stacktrace: \n  ".implode("\n  ", explode("\n", $e->getTraceAsString()))."\n";

		// calculate Exception-ID
		$id      = \skies\util\StringUtil::getHash($message);
		$message = "<<<<<<<<".$id."<<<<\n".$message."<<<<\n\n";

		// append
		@file_put_contents($logFile, $message, FILE_APPEND);

		return $id;
	}

}

?>
