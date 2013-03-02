<?php
/**
 * @author    Janek Ostendorf (ozzy) <ozzy2345de@gmail.com>
 * @copyright Copyright (c) Janek Ostendorf
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU General Public License, version 3
 */

/** @var $page \skies\system\page\FilePage */

use skies\util\UserUtil;
use skies\system\user\User;
use skies\form\Form;

/*
 * Forms
 */

// Login
$page->store['loginForm'] = new skies\form\Form();

$page->store['loginForm']->addInput('username', \Skies::$language->get('system.page.login.username'), true);
$page->store['loginForm']->addInput('password', \Skies::$language->get('system.page.login.password'), true, 'password');
$page->store['loginForm']->addInput('login', \Skies::$language->get('system.page.login.login'), false, 'submit');

/** @var $loginFormHandler \skies\form\FormHandler */
$loginFormHandler = $page->store['loginForm']->getHandler();

// Sing up
$page->store['signUpForm'] = new skies\form\Form();

$page->store['signUpForm']->addInput('username_sign-up', \Skies::$language->get('system.page.login.username'), true);
$page->store['signUpForm']->addInput('mail', \Skies::$language->get('system.page.login.register.mail'), true, 'text', \skies\util\UserUtil::MAIL_PATTERN);
$page->store['signUpForm']->addInput('password1', \Skies::$language->get('system.page.login.register.password-twice'), true, 'password');
$page->store['signUpForm']->addInput('password2', '', true, 'password');
$page->store['signUpForm']->addInput('sign-up', \Skies::$language->get('system.page.login.sign-up'), false, 'submit');

/** @var $signUpFormHandler \skies\form\FormHandler */
$signUpFormHandler = $page->store['signUpForm']->getHandler();

// Logout
$page->store['logoutForm'] = new skies\form\Form();

$page->store['logoutForm']->addInput('logout', \Skies::$language->get('system.page.login.logout'), false, 'submit');

/** @var $logoutFormHandler \skies\form\FormHandler */
$logoutFormHandler = $page->store['logoutForm']->getHandler();


\Skies::$message['notice']->add('Please don\'t try to register or login, this page is not finished yet and you might mess up the whole system :O');

/*
 * Which form has been submitted?
 */

// Login
if($loginFormHandler->isSubmitted()) {

	$data = $loginFormHandler->getData();

	// Check user for existence
	$userID = \skies\util\UserUtil::usernameToID($data['username']);

	if($userID !== false) {

		// Check password
		if(\skies\util\UserUtil::checkPassword($data['password'], $userID)) {

			$user = new \skies\system\user\User($userID);

			if(!\Skies::$session->login($user->getId())) {

				\Skies::$message['error']->add('{{system.page.login.error}}');

			}
			else {
				\Skies::$message['success']->add('{{system.page.login.login.success}}', ['userName' => $user->getName()]);
			}

		}
		else {

			\Skies::$message['error']->add('{{system.page.login.error.user-pw}}');

		}

	}
	else {

		\Skies::$message['error']->add('{{system.page.login.error.user-pw}}');

	}

}

// Logout
if($logoutFormHandler->isSubmitted() || (isset($_GET['_1']) && $_GET['_1'] == 'logout')) {

	if(!\Skies::$session->logout()) {

		\Skies::$message['error']->add('{{system.page.login.error}}');

	}
	else {
		\Skies::$message['success']->add('{{system.page.login.logout.success}}');
	}

}

// Sign up
if($signUpFormHandler->isSubmitted()) {

	$data = $signUpFormHandler->getData();

	// Completed?
	if($signUpFormHandler->isCompleted()) {

		// Pattern?
		if($signUpFormHandler->checkPatterns()) {

			// Do the passwords match?
			if($data['password1'] == $data['password2']) {

				// Is the username taken already?
				if(UserUtil::usernameToID($data['username_sign-up']) === false) {

					// Does the username match the pattern?
					if(\skies\util\UserUtil::checkUsername($data['username_sign-up'])) {

						// Does the mail match the pattern?
						if(\skies\util\UserUtil::checkMail($data['mail'])) {

							$newUser = \skies\util\UserUtil::createUser($data['username_sign-up'], $data['mail'], $data['password1']);

							if($newUser !== false) {

								// Aaaaand start the session!
								if(!\Skies::$session->login($newUser->getId())) {

									\Skies::$message['error']->add(\Skies::$language->get('system.page.login.error'));

								}
								else {

									// Strike
									\Skies::$message['success']->add('{{system.page.login.sign-up.success}}', ['userName' => $newUser->getName()]);

								}

							}
							else {

								\Skies::$message['success']->add('{{system.page.login.sign-up.error}}');

							}

						}
						else {

							\Skies::$message['error']->add('{{system.page.login.sign-up.error.mail-pattern}}');

						}

					}
					else {

						\Skies::$message['error']->add('{{system.page.login.sign-up.error.username-pattern}}');

					}

				}
				else {

					\Skies::$message['error']->add('{{system.page.login.sign-up.error.username-taken}}');

				}

			}
			else {

				\Skies::$message['error']->add('{{system.page.login.sign-up.error.passwords-match}}');

			}

		}
		else {

			\Skies::$message['error']->add('{{system.page.login.sign-up.error.pattern}}');

		}

	}
	else {

		\Skies::$message['error']->add('{{system.page.login.sign-up.error.missing}}');

	}
}

?>
