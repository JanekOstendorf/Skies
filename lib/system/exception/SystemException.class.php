<?php
/**
 * @author    Janek Ostendorf (ozzy) <ozzy2345de@gmail.com>
 * @copyright Copyright (c) 2013 Janek Ostendorf
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU General Public License, version 3
 */

namespace skies\system\exception;

use skies\util\StringUtil;

/**
 * Yeah, we got some heavy problems here!
 */
class SystemException extends LoggedException implements IPrintableException {

	/**
	 * Throw the exception
	 *
	 * @param string     $message     Error message
	 * @param int        $code        Error code
	 * @param string     $description Error description
	 * @param \Exception $previous    Repacked Exception
	 */
	public function __construct($message = '', $code = 0, $description = '', \Exception $previous = null) {

		parent::__construct((string)$message, (int)$code, $previous);
		$this->description = $description;
	}

	/**
	 * Get the description of this error.
	 *
	 * @return string
	 */
	public function getDescription() {

		return $this->description;

	}

	/**
	 * Removes the DB password from the stack trace
	 *
	 * @see \Exception::getTraceAsString()
	 * @return string
	 */
	public function __getTraceAsString() {

		$e = ($this->getPrevious() ? : $this);

		$string = preg_replace('/Database->__construct\(.*\)/', 'Database->__construct(...)', $e->getTraceAsString());
		$string = preg_replace('/mysqli->mysqli\(.*\)/', 'mysqli->mysqli(...)', $string);

		return $string;

	}

	/**
	 * Prints the exception.
	 */
	public function show() {

		// Try to log this shit
		$id = $this->logError();

		/* Print HTML message */
		@header('HTTP/1.1 503 Service Unavailable');

		echo '<?xml version="1.0" encoding="UTF-8"?>';
		$e = ($this->getPrevious() ? : $this);
		?>

	<!DOCTYPE html>
	<html>
		<head>
			<title>Fatal error: <?php echo StringUtil::encodeHtml($this->_getMessage()); ?></title>

		</head>
		<body>
			<div class="systemException">
				<h1>Fatal error: <?php echo StringUtil::encodeHtml($this->_getMessage()); ?></h1>
				<!-- TODO: Maybe a nice style? -->
				<?php if(\Skies::isDebugMode()) { ?>
				<div>
					<p><?php echo $this->getDescription(); ?></p>

					<h2>Information:</h2>

					<p>
						<b>ID:</b> <code><?php echo $id; ?></code><br>
						<b>Error message:</b> <?php echo StringUtil::encodeHtml($this->_getMessage()); ?><br>
						<b>Error code:</b> <?php echo intval($e->getCode()); ?><br>
						<?php echo $this->information; ?>
						<b>File:</b> <?php echo StringUtil::encodeHTML($e->getFile()); ?> (<?php echo $e->getLine(); ?>)<br>
						<b>Date:</b> <?php echo gmdate('r'); ?><br>
						<b>Request:</b> <?php if(isset($_SERVER['REQUEST_URI']))
						echo StringUtil::encodeHtml($_SERVER['REQUEST_URI']); ?><br>
						<b>Referer:</b> <?php if(isset($_SERVER['HTTP_REFERER']))
						echo StringUtil::encodeHtml($_SERVER['HTTP_REFERER']); ?><br>
					</p>

					<h2>Stacktrace:</h2>
					<pre><?php echo StringUtil::encodeHtml($this->__getTraceAsString()); ?></pre>
				</div>
				<?php
			}
			else {
				?>
				<div>
					<h2>Information:</h2>

					<p>
						<b>ID:</b> <code><?php echo $id; ?></code><br>
						Send this ID to the administrator of this website to report this issue.
					</p>
				</div>
				<?php } ?>
			</div>
		</body>
	</html>

	<?php

	}

}
