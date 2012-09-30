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

                \Skies::$message['error']->add(\Skies::$language->get('system.page.login.error'));

            }
            else {
                \Skies::$message['success']->add(\Skies::$language->get('system.page.login.login.success'), ['userName' => $user->getName()]);
            }

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
if($logoutFormHandler->isSubmitted()) {

    if(!\Skies::$session->logout()) {

        \Skies::$message['error']->add(\Skies::$language->get('system.page.login.error'));

    }
    else {
        \Skies::$message['success']->add(\Skies::$language->get('system.page.login.logout.success'));
    }

}

?>