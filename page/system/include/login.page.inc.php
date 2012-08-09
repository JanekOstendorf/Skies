<?php
/**
 * @author    Janek Ostendorf (ozzy) <ozzy2345de@gmail.com>
 * @copyright Copyright (c) Janek Ostendorf
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU General Public License, version 3
 */

use skies\utils\UserUtils;
use skies\system\user\User;

/*
 * Which form has been submitted?
 */

// Login
if(isset($_POST['login'])) {

    // Check user for existence
    $userID = \skies\utils\UserUtils::usernameToID($_POST['username']);

    if($userID !== false) {

        // Check password
        if(\skies\utils\UserUtils::checkPassword($_POST['password'], $userID)) {

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



}

?>