<?php

namespace skies\system\user;

/**
 * @author    Janek Ostendorf (ozzy) <ozzy2345de@gmail.com>
 * @copyright Copyright (c) Janek Ostendorf
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU General Public License, version 3
 * @package skies.user
 */
class Session {

    public function __construct() {

        // Fetch session ID
        if(isset($_COOKIE[COOKIE_PRE.'sessionID']) && !empty($_COOKIE[COOKIE_PRE.'sessionID'])) {



        }

    }

}

?>