<?php

namespace skies\utils;

/**
 * @author Janek Ostendorf (ozzy) <ozzy2345de@gmail.com>
 * @copyright Copyright (c) Janek Ostendorf
 * @license http://opensource.org/licenses/gpl-3.0.html GNU General Public License, version 3
 * @package
 */
class StringUtils {

    /**
     * Converts special HTML characters
     *
     * @static
     * @param $string
     * @return string
     */
    public static function encodeHTML($string) {

        if(is_object($string))
            $string = $string->__toString();

        return @htmlspecialchars($string, ENT_COMPAT, 'UTF-8');

    }

    /**
     * Alias to php sha1() function
     * @static
     * @param $string
     * @return string
     */
    public static function getHash($string) {

        return sha1($string);

    }
}

?>