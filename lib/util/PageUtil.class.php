<?php

namespace skies\util;

/**
 * @author    Janek Ostendorf (ozzy) <ozzy2345de@gmail.com>
 * @copyright Copyright (c) Janek Ostendorf
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU General Public License, version 3
 * @package   skies.util
 */
class PageUtil {

    /**
     * Gets the ID of the page with the given short name
     *
     * @static
     *
     * @param string $page_name Short name of the page
     *
     * @return int ID of the page. `-1` for system pages
     */
    public static function getIDFromName($page_name) {

        if(\skies\system\page\SystemPages::isSystemPage($page_name))
            return -1;

        $query = 'SELECT * FROM '.TBL_PRE.'page WHERE `pageName` = \''.\escape($page_name).'\'';

        $result = \Skies::$db->query($query);

        if($result === false) {
            return false;
        }

        return $result->fetch_array(MYSQL_ASSOC)['pageID'];

    }

    /**
     * Gets the name of the page with the given ID
     *
     * @static
     *
     * @param int $page_id ID of the page
     *
     * @return string Short name of the page
     */
    public static function gerNameFromID($page_id) {

        $query = 'SELECT * FROM '.TBL_PRE.'page WHERE `pageID` = \''.\escape($page_id).'\'';

        $result = \Skies::$db->query($query);

        if($result === false) {
            return false;
        }

        return $result->fetch_array(MYSQL_ASSOC)['pageName'];

    }

    /**
     * Gets the type of the page with the given ID
     *
     * @static
     *
     * @param int $id ID of the page
     *
     * @return int Type of the page
     * @see \skies\system\page\PageTypes
     */
    public static function getTypeFromID($id) {


        $query = 'SELECT * FROM '.TBL_PRE.'page WHERE `pageID` = \''.\escape($id).'\'';

        $result = \Skies::$db->query($query);

        if($result === false) {
            return false;
        }

        return $result->fetch_array(MYSQL_ASSOC)['pageType'];

    }

}

?>