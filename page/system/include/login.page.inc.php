<?php
/**
 * @author    Janek Ostendorf (ozzy) <ozzy2345de@gmail.com>
 * @copyright Copyright (c) Janek Ostendorf
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU General Public License, version 3
 */

/* @var $page \skies\system\page\FilePage */

use skies\util\UserUtils;
use skies\system\user\User;

/*
 * Forms
 */

// Login
$page->store->loginForm = new skies\form\Form();

$loginForm->addInput('username', \Skies::$language->get('system.page.login.username'), true);
$loginForm->addInput('password', \Skies::$language->get('system.page.login.password'), true, 'password');
$loginForm->addInput('login', \Skies::$language->get('system.page.login.login'), 'submit');

$loginFormHandler = $loginForm->getHandler();

// Sing up
$signUpForm = new skies\form\Form();

$signUpForm->addInput('username_sign-up', \Skies::$language->get('system.page.login.username'), true);
$signUpForm->addInput('mail', \Skies::$language->get('system.page.login.register.mail'), true, 'text', \skies\util\UserUtils::MAIL_PATTERN);
$signUpForm->addInput('password1', \Skies::$language->get('system.page.login.register.password-twice'), true, 'password');
$signUpForm->addInput('password2', '', true, 'password');
$signUpForm->addInput('sign-up', \Skies::$language->get('system.page.login.sign-up'), 'submit');


$signUpFormHandler = $signUpForm->getHandler();

// Logout
$logoutForm = new skies\form\Form();

$logoutForm->addInput('logout', \Skies::$language->get('system.page.login.logout'), 'submit');

$logoutFormHandler = $logoutForm->getHandler();



\Skies::$message['notice']->add('Please don\'t try to register or login, this page is not finished yet and you might mess up the whole system :O');

/*
 * Which form has been submitted?
 */

// Login
if(isset($_POST['login'])) {

    // Check user for existence
    $userID = \skies\util\UserUtils::usernameToID($_POST['username']);

    if($userID !== false) {

        // Check password
        if(\skies\util\UserUtils::checkPassword($_POST['password'], $userID)) {

            $user = new \skies\system\user\User($userID);

            if(!\Skies::$session->login($user->getId())) {

                \Skies::$message['error']->add(\Skies::$language->get('system.page.login.error'));

            }
            else
                \Skies::$message['success']->add(\Skies::$language->get('system.page.login.login.success'), ['userName' => $user->getName()]);

        }
        else {

            \Skies::$message['error']->add(\Skies::$language->get('system.page.login.error.user-pw'));

        }

    }
    else {

        \Skies::$message['error']->add(\Skies::$language->get('system.page.login.error.user-pw'));

    }

}

// Logout
if(isset($_POST['logout'])) {

    if(!\Skies::$session->logout()) {

        \Skies::$message['error']->add(\Skies::$language->get('system.page.login.error'));

    }
    else
        \Skies::$message['success']->add(\Skies::$language->get('system.page.login.logout.success'));

}

?>