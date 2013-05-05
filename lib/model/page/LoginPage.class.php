<?php
/**
 * @author    Janek Ostendorf (ozzy) <ozzy2345de@gmail.com>
 * @copyright Copyright (c) 2013 Janek Ostendorf
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU General Public License, version 3
 */

namespace skies\model\page;

use skies\model\Page;
use skies\model\template\Notification;
use skies\system\language\Language;
use skies\system\protocol\Uri;
use skies\system\user\User;
use skies\util\LanguageUtil;
use skies\util\StringUtil;
use skies\util\UserUtil;

/**
 * Login page
 */
class LoginPage extends Page {

	/**
	 * Prepare the output
	 *
	 * @return void
	 */
	public function prepare() {

		/*
		 * Check forms
		 */

		// Login
		if(\Skies::getUri()->getPost('login')) {

			$userId = UserUtil::usernameToID(\Skies::getUri()->getPost('username'));

			if($userId !== false) {

				$user = new User($userId);

				// Check password
				if($user->checkPassword(\Skies::getUri()->getPost('password'))) {

					if(\Skies::getSession()->login($user->getId(), \Skies::getUri()->getPost('longSession') !== false)) {

						\Skies::updateUser();
						\Skies::getNotification()->add(Notification::SUCCESS, '{{system.page.login.login.success}}', ['userName' => $user->getName()]);

						if(\Skies::getUri()->getArgument(1, 'refer') == 'refer') {

							$referTo = '';

							// Redirect back to the HTTP_REFERER if none is given
							if(\Skies::getUri()->getArgument(2, 'referTo') === false) {
								$referTo = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
							}
							else {

								// Build url
								$referTo = '/'.SUBDIR;

								switch(\Skies::getUri()->getMethod()) {

									case Uri::METHOD_ARGUMENT:
										$referTo .= \Skies::getUri()->getArgument(2, 'referTo');
										break;
									case Uri::METHOD_REWRITE:
										$i = 2;

										while(\Skies::getUri()->getArgument($i, '')) {
											$referTo .= \Skies::getUri()->getArgument($i++, '').'/';
										}
										break;

								}

							}

							\Skies::getNotification()->addSession(Notification::SUCCESS, '{{system.page.login.login.success}}', ['userName' => $user->getName()]);
							header('Location: '.$referTo);
							exit;
						}

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
		if(\Skies::getUri()->getArgument(1, 'logout') == 'logout') {

			if(!\Skies::getUser()->isGuest()) {

				\Skies::getSession()->logout();
				\Skies::updateUser();
				\Skies::getNotification()->addSession(Notification::SUCCESS, '{{system.page.login.logout.success}}');
				header('Location: /'.SUBDIR);

			}
			else {

				\Skies::getNotification()->add(Notification::ERROR, '{{system.page.login.logout.error.guest}}');

			}

		}

		// Change email
		if(\Skies::getUri()->getPost('changeMailSubmit') !== false) {

			// Everything set?
			if(\Skies::getUri()->getPost('changeMail') !== false && \Skies::getUri()->getPost('changeMailPassword') !== false) {

				// Check mail pattern
				if(UserUtil::checkMail(\Skies::getUri()->getPost('changeMail'))) {

					// Check password
					if(\Skies::getUser()->checkPassword(\Skies::getUri()->getPost('changeMailPassword'))) {

						// Everything's right, change the mail
						\Skies::getUser()->setMail(\Skies::getUri()->getPost('changeMail'), \Skies::getUri()->getPost('changeMailPassword'));
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
		if(\Skies::getUri()->getPost('changePasswordSubmit') !== false) {

			// Check for passwords
			if(\Skies::getUri()->getPost('changePassword1') !== false && \Skies::getUri()->getPost('changePassword2') !== false) {

				if(\Skies::getUri()->getPost('changePassword1') == \Skies::getUri()->getPost('changePassword2')) {

					\Skies::getUser()->setPassword(\Skies::getUri()->getPost('changePassword1'));
					\Skies::updateUser();

					\Skies::getNotification()->add(Notification::SUCCESS, '{{system.page.login.changePassword.success}}');

				}
				else {

					\Skies::getNotification()->add(Notification::ERROR, '{{system.page.login.changePassword.error.mismatch}}');

				}

			}
			else {

				\Skies::getNotification()->add(Notification::ERROR, '{{system.page.login.changePassword.error.missing}}');

			}

		}

		// Choose language
		$availableLanguages = [];
		$languageIds = [];

		foreach(LanguageUtil::getAllLanguages() as $language) {
			$availableLanguages[] = $language->getTemplateArray();
			$languageIds[] = $language->getId();
		}

		if(\Skies::getUri()->getPost('chooseLanguageSubmit') !== false) {

			// Is the language valid?
			if(in_array(\Skies::getUri()->getPost('chooseLanguage'), $languageIds)) {

				\Skies::getUser()->setData('language', \Skies::getUri()->getPost('chooseLanguage'));
				\Skies::getUser()->update();

				// Some language vars are fetched before this is changed. Therefore there might be some text in the old language
				// To avoid this, we use this very ugly method called redirecting.
				// TODO: Look for a better solution
				header('Location: '.\Skies::getPage()->getRelativeLink());
				exit;

			}
			else {

				\Skies::getNotification()->add(Notification::ERROR, '{{system.page.login.chooseLanguage.error.notExists}}');

			}

		}

		// Mail and username pattern
		\Skies::getTemplate()->assign([
			'loginPage' => [
				'mailPattern' => UserUtil::MAIL_PATTERN,
				'usernamePattern' => UserUtil::USERNAME_PATTERN,
				'availableLanguages' => $availableLanguages,
				'changeMail' => (\Skies::getUri()->getPost('changeMail') !== false ? \Skies::getUri()->getPost('changeMail') : null)
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
	 * @return array
	 */
	public function getPath() {
		return ['login'];
	}

	/**
	 * Get the title of this page.
	 *
	 * @return string
	 */
	public function getTitle() {

		return \Skies::getLanguage()->get('system.page.login.title');

	}

	/**
	 * Get the name of the page
	 *
	 * @return string
	 */
	public function getName() {
		return 'login';
	}

}
