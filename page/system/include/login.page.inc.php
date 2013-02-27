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
$page->store['loginForm']->addInput('longLogin', 'Angemeldet bleiben', false, 'checkbox');
$page->store['loginForm']->addInput('login', \Skies::$language->get('system.page.login.login'), false, 'submit');

/** @var $loginFormHandler \skies\form\FormHandler */
$loginFormHandler = $page->store['loginForm']->getHandler();

// Logout
$page->store['logoutForm'] = new skies\form\Form();

$page->store['logoutForm']->addInput('logout', \Skies::$language->get('system.page.login.logout'), false, 'submit');

/** @var $logoutFormHandler \skies\form\FormHandler */
$logoutFormHandler = $page->store['logoutForm']->getHandler();

// Change password
$page->store['changePassword'] = new skies\form\Form();

$page->store['changePassword']->addInput('password1', 'Passwort (zweimal)', true, 'password');
$page->store['changePassword']->addInput('password2', null, true, 'password');
$page->store['changePassword']->addInput('changePassword', 'Passwort ändern', false, 'submit');

/** @var $changePasswordHandler \skies\form\FormHandler */
$changePasswordHandler = $page->store['changePassword']->getHandler();


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
			if($user->isAdmin() || !\Skies::$config['allowNormalLogin'] == false) {

				if(\Skies::$session->login($user->getId(), isset($data['longLogin'])) !== false) {

					\Skies::$message['success']->add('{{system.page.login.login.success}}', ['userName' => $user->getName()]);

					// For the LAN, redirect to the accept page
					header('Location: '.SUBDIR.'/zusagen');
					exit;

				}
				else {
					\Skies::$message['error']->add('{{system.page.login.error}}');
				}

			}
			else {

				\Skies::$message['notice']->add('Der Login für normale Benutzer ist aktuell gesperrt. So kurz vor dem LAN müssen wir mit den vorhandenen Daten planen und können keine Änderungen mehr zulassen.');

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
elseif($logoutFormHandler->isSubmitted() || (isset($_GET['_1']) && $_GET['_1'] == 'logout')) {

	if(!\Skies::$session->logout()) {

		\Skies::$message['error']->add('{{system.page.login.error}}');

	}
	else {
		\Skies::$message['success']->add('{{system.page.login.logout.success}}');
	}

}

// Sign up
if(isset($_POST['sign_up'])) {


	// Do the passwords match?
	if($_POST['password1'] == $_POST['password2']) {

		// Check token
		$query = 'SELECT * FROM `'.TBL_PRE.'user-data` INNER JOIN `'.TBL_PRE.'user-fields` ON `dataFieldID` = `fieldID` INNER JOIN `'.TBL_PRE.'user` ON `dataUserID` = `userID` WHERE `fieldName` = \'regToken\' AND `dataValue` = \''.escape($_POST['token']).'\'';

		$result = \Skies::$db->query($query);

		if($result->num_rows == 1) {

			$data = $result->fetch_array(MYSQLI_ASSOC);

			// Set PW
			$user = new \skies\system\user\User($data['userID']);

			if($user->setPassword($_POST['password1']) !== false) {

				// Start session
				if(\Skies::$session->login($user->getId())) {

					\Skies::$message['success']->add('Dein Passwort wurde erfolgreich gesetzt und du bist jetzt angemeldet.<br />
                    Dein Benutzername ist <em>'.$user->getName().'</em>. Merke ihn dir!');

				}
				else {

					\Skies::$message['error']->add('{{system.page.login.error}}');

				}

			}
			else {

				\Skies::$message['error']->add('Fehler beim Setzen des Passwortes!');

			}

		}
		else {

			\Skies::$message['error']->add('Fehler beim setzen des Passwortes!');

		}

	}
	else {

		\Skies::$message['error']->add('{{system.page.login.sign-up.error.passwords-match}}');

	}

}

// Change password
if($changePasswordHandler->isSubmitted()) {

	if($changePasswordHandler->isCompleted()) {

		$data = $changePasswordHandler->getData();

		// Do the passwords match?
		if($data['password1'] == $data['password2']) {

			if(\Skies::$user->setPassword($data['password1']) !== false) {

				\Skies::$message['success']->add('Dein Passwort wurde erfolgreich geändert!');
			}
			else {

				\Skies::$message['error']->add('Fehler beim setzen des Passwortes!');

			}

		}
		else {

			\Skies::$message['error']->add('Fehler beim setzen des Passwortes!');

		}

	}
	else {

		\Skies::$message['error']->add('{{system.page.login.sign-up.error.passwords-match}}');

	}

}

?>
