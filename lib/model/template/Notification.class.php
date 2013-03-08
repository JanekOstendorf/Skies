<?php

namespace skies\model\template;

/**
 * @author    Janek Ostendorf (ozzy) <ozzy2345de@gmail.com>
 * @copyright Copyright (c) Janek Ostendorf
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU General Public License, version 3
 * @package
 */
class Notification {

	/**#@+
	 * Constants for identifying
	 */
	const NOTICE = 1;

	const WARNING = 2;

	const SUCCESS = 3;

	const ERROR = 4;

	/**#@-*/

	/**
	 * Storage
	 *
	 * @var array
	 */
	protected $storage = [];

	/**
	 * Which template belongs to which type?
	 *
	 * @var array
	 */
	protected $templates = [];

	/**
	 * Init the notifications
	 *
	 * @param array $templates Type => template file
	 */
	public function __construct($templates) {

		$this->templates = $templates;

	}

	/**
	 * @param int    $type     Type of this notification (constant)
	 * @param string $message  Message
	 * @param array  $userVars Additional user variables
	 */
	public function add($type, $message, $userVars = []) {

		if(!empty($userVars))
			$message = \Skies::$language->replaceVars($message, $userVars);

		$this->storage[$type][] = $message;

	}

	/**
	 * Send the notifications to the template engine
	 */
	public function assign() {

		\Skies::$template->assign(['notifications' => $this->storage, 'notificationTemplates' => $this->templates]);

	}


}

?>
