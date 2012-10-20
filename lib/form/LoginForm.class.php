<?php

namespace skies\form;

/**
 * @author    Janek Ostendorf (ozzy) <ozzy2345de@gmail.com>
 * @copyright Copyright (c) Janek Ostendorf
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU General Public License, version 3
 * @package   skies.form
 */
class LoginForm {

    /**
     * Language variable for the login message
     */
    const LANGVAR_LOGIN = 'system.loginform.login';

    /**
     * Language variable for the logout message
     */
    const LANGVAR_LOGOUT = 'system.loginform.logout';

    /**
     * The user, dammit
     *
     * @var \skies\system\user\User
     */
    protected $user = null;

    /**
     * Buffer
     *
     * @var string
     */
    protected $output;

    /**
     * Do it! Print the LoginForm
     */
    public function __construct() {

        // Fetch user
        $this->user =& \Skies::$user;

        // Buffer
        $this->output = '';

        /*
         * User is NOT logged in
         */
        if($this->user->isGuest()) {

            $loginLink = '<a id="link" href="'.SUBDIR.'/login" id="link-login">{{system.page.login.login}}</a>';

            $this->output .= \Skies::$language->get(self::LANGVAR_LOGIN, ['link-login' => $loginLink]);

        }
        /*
         * User IS logged in
         */
        else {

            $logoutLink = '<a id="link" href="'.SUBDIR.'/login/logout">{{system.page.login.logout}}</a>';

            $this->output .= \Skies::$language->get(self::LANGVAR_LOGOUT, ['userName' => $this->user->getName(), 'link-logout' => $logoutLink]);

        }

    }

    /**
     * Prints the form
     *
     * @return int
     */
    public function show() {

        return print($this->output);

    }

    /**
     * Returns the HTML of the form
     *
     * @return string
     */
    public function returnHtml() {

        return $this->output;

    }

}

?>