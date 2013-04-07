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
	const ERROR = 1;

	const WARNING = 2;

	const NOTICE = 3;

	const SUCCESS = 4;

	/**#@-*/

	/**
	 * Storage
	 *
	 * @var array
	 */
	protected $storage = [];

	/**
	 * Buffer for session notifications
	 *
	 * @var array
	 */
	protected $sessionStorage = [];

	/**
	 * Which css class belongs to which type?
	 *
	 * @var array
	 */
	protected $templates = [];

	/**
	 * Init the notifications
	 *
	 * @param array $templates Type => css class
	 */
	public function __construct($templates) {

		$this->templates = $templates;

		// Read from possible session notifications
		if(!\Skies::getSession()->getData('notifications') == null) {

			foreach(\Skies::getSession()->getData('notifications') as $notification) {

				$this->add($notification['type'], $notification['message'], $notification['userVars']);

			}

		}

	}

	/**
	 * @param int    $type     Type of this notification (constant)
	 * @param string $message  Message
	 * @param array  $userVars Additional user variables
	 */
	public function add($type, $message, $userVars = []) {

		$this->storage[$type][] = \Skies::getLanguage()->replaceVars($message, $userVars);

	}

	/**
	 * Adds notification to the session storage so it will be displayed even on page reload
	 *
	 * @param int    $type     Type of this notification (constant)
	 * @param string $message  Message
	 * @param array  $userVars Additional user variables
	 */
	public function addSession($type, $message, $userVars = []) {

		$this->sessionStorage[] = ['type' => $type, 'message' => $message, 'userVars' => $userVars];
		\Skies::getSession()->setData('notifications', $this->sessionStorage);

	}

	/**
	 * Send the notifications to the template engine
	 */
	public function assign() {

		\Skies::getTemplate()->assign(['notifications' => $this->storage, 'notificationTemplates' => $this->templates]);

	}

}

?>
