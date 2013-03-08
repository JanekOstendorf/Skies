<?php
/**
 * @author    Janek Ostendorf (ozzy) <ozzy2345de@gmail.com>
 * @copyright Copyright (c) 2013 Janek Ostendorf
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU General Public License, version 3
 */
 
namespace skies\model\page;

/**
 * Login page
 */
use skies\model\Page;
use skies\model\template\Notification;
use skies\system\user\User;
use skies\util\UserUtil;

class LoginPage extends Page {

	/**
	 * Prepare the output
	 * @return void
	 */
	public function prepare() {

		/*
		 * Check forms
		 */

		// Login
		if(isset($_POST['login'])) {

			$userId = UserUtil::usernameToID($_POST['username']);

			if($userId !== false) {

				$user = new User($userId);

				// Check password
				if($user->checkPassword($_POST['password'])) {

					if(!\Skies::$session->login($user->getId())) {

						\Skies::$notification->add(Notification::ERROR, '{{system.page.login.error}}');

					}
					else {
						\Skies::$notification->add(Notification::SUCCESS, '{{system.page.login.login.success}}', ['userName' => $user->getName()]);
					}

				}
				else {

					\Skies::$notification->add(Notification::ERROR, '{{system.page.login.error.user-pw}}');

				}

			}
			else {

				\Skies::$notification->add(Notification::ERROR, '{{system.page.login.error.user-pw}}');

			}

		}


		// Mail and username pattern
		\Skies::$template->assign([
			'loginPage' => [
				'mailPattern' => UserUtil::MAIL_PATTERN,
				'usernamePattern' => UserUtil::USERNAME_PATTERN
			]
		]);

	}

	/**
	 * What's our style name?
	 *
	 * @return string
	 */
	public function getTemplateName() {
		return 'loginPage.tpl';
	}

	/**
	 * Get the name of this page (short form for the URL)
	 *
	 * @return string
	 */
	public function getName() {
		return 'login';
	}

	/**
	 * Get the title of this page.
	 *
	 * @return string
	 */
	public function getTitle() {

		return \Skies::$language->get('system.page.login.title');

	}
}
