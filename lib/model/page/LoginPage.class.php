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
use skies\util\LanguageUtil;
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

					if(\Skies::getSession()->login($user->getId()) !== false) {

						\Skies::updateUser();
						\Skies::getNotification()->add(Notification::SUCCESS, '{{system.page.login.login.success}}', ['userName' => $user->getName()]);

					}
					else {

						\Skies::getNotification()->add(Notification::ERROR, '{{system.page.login.login.error.generic}}');

					}

				}
				else {

					\Skies::getNotification()->add(Notification::ERROR, '{{system.page.login.login.error.userPassword}}');

				}

			}
			else {

				\Skies::getNotification()->add(Notification::ERROR, '{{system.page.login.login.error.userPassword}}');

			}

		}

		// Logout
		if((isset($_GET['_1']) && $_GET['_1'] == 'logout') || isset($_GET['logout'])) {

			if(!\Skies::getUser()->isGuest()) {

				\Skies::getSession()->logout();
				\Skies::updateUser();
				header('Location: /'.SUBDIR);

			}
			else {

				\Skies::getNotification()->add(Notification::ERROR, '{{system.page.login.logout.error.guest}}');

			}

		}

		// Change email
		if(isset($_POST['changeMailSubmit'])) {

			// Everything set?
			if(isset($_POST['changeMail']) && isset($_POST['changeMailPassword'])) {

				// Check mail pattern
				if(UserUtil::checkMail($_POST['changeMail'])) {

					// Check password
					if(\Skies::getUser()->checkPassword($_POST['changeMailPassword'])) {

						// Everything's right, change the mail
						\Skies::getUser()->setMail($_POST['changeMail'], $_POST['changeMailPassword']);
						\Skies::getUser()->update();

						\Skies::getNotification()->add(Notification::SUCCESS, '{{system.page.login.changeMail.success}}', ['newMail' => \Skies::getUser()->getMail()]);

					}
					else {

						\Skies::getNotification()->add(Notification::ERROR, '{{system.page.login.changeMail.error.wrongPassword}}');

					}

				}
				else {

					\Skies::getNotification()->add(Notification::ERROR, '{{system.page.login.changeMail.error.mailPattern}}');

				}

			}
			else {

				\Skies::getNotification()->add(Notification::ERROR, '{{system.page.login.changeMail.error.missing}}');

			}

		}

		// Change Password
		if(isset($_POST['changePasswordSubmit'])) {

			// Check for passwords
			if(isset($_POST['changePassword1']) && isset($_POST['changePassword2'])) {

				if($_POST['changePassword1'] == $_POST['changePassword2']) {

					\Skies::getUser()->setPassword($_POST['changePassword1']);
					\Skies::updateUser();

					\Skies::getNotification()->add(Notification::SUCCESS, '{{system.page.login.change.password.success}}');

				}
				else {

					\Skies::getNotification()->add(Notification::ERROR, '{{system.page.login.changePassword.error.mismatch}}');

				}

			}
			else {

				\Skies::getNotification()->add(Notification::ERROR, '{{system.page.login.changePassword.error.missing}}');

			}

		}

		$availableLanguages = [];

		foreach(LanguageUtil::getAllLanguages() as $language)
			$availableLanguages[] = $language->getTemplateArray();

		// Mail and username pattern
		\Skies::getTemplate()->assign([
			'loginPage' => [
				'mailPattern' => UserUtil::MAIL_PATTERN,
				'usernamePattern' => UserUtil::USERNAME_PATTERN,
				'availableLanguages' => $availableLanguages
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

		return \Skies::getLanguage()->get('system.page.login.title');

	}
}
