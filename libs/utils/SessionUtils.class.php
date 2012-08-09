<?php

namespace skies\utils;

/**
 * @author    Janek Ostendorf (ozzy) <ozzy2345de@gmail.com>
 * @copyright Copyright (c) Janek Ostendorf
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU General Public License, version 3
 * @package   skies.utils
 */
class SessionUtils {


    public static function cleanUp() {

        // Normal sessions
        $length = (30 * 60);
        $timeout = $length + NOW;

        $query = 'DELETE FROM `'.TBL_PRE.'session` WHERE `sessionLong` = 0 AND (`sessionLastActivity` + '.$length.') < '.$timeout.';';

        // Long sessions
        $length = (365 * 86400);
        $timeout = $length + NOW;

        $query = 'DELETE FROM `'.TBL_PRE.'session` WHERE `sessionLong` = 1 AND (`sessionLastActivity` + '.$length.') < '.$timeout.';';

    }

}

?>