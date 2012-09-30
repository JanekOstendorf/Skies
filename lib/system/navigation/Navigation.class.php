<?php

namespace skies\system\navigation;

use skies\system\navigation\EntryTypes;

/**
 * @author    Janek Ostendorf (ozzy) <ozzy2345de@gmail.com>
 * @copyright Copyright (c) Janek Ostendorf
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU General Public License, version 3
 * @package   skies.system.navigation
 */
class Navigation {

    /**
     * Entries of this navigation
     *
     * @var array
     */
    protected $entries = [];

    /**
     * ID of this nav
     *
     * @var int
     */
    protected $id = 0;

    /**
     * Title of this navigation
     *
     * @var string
     */
    protected $title = '';


    /**
     * Init the nav and fetch all data
     *
     * @param int $id ID of the navigation
     */
    public function __construct($id) {

        $this->id = $id;

        // Data about this nav
        $query = 'SELECT * FROM '.TBL_PRE.'nav WHERE `navID` = '.\escape($this->id);

        $navResult = \Skies::$db->query($query);

        if($navResult->num_rows != 1) {

            return false;

        }

        $this->title = $navResult->fetch_array(MYSQLI_ASSOC)['navTitle'];


        // Entries
        $query = 'SELECT * FROM `'.TBL_PRE.'nav-entry` WHERE `navID` = '.\escape($this->id).' ORDER BY `entryOrder` ASC';

        $result = \Skies::$db->query($query);

        while($line = $result->fetch_array(MYSQLI_ASSOC)) {

            $this->entries[] = [

                'id' => $line['entryID'],
                'order' => $line['entryOrder'],
                'pageID' => $line['entryPageID'],
                'link' => $line['entryLink'],
                'title' => $line['entryTitle'],
                'type' => $line['entryType'],
                'pageName' => $line['entryPageName']

            ];

        }

    }

    public function printNav() {

        $buffer = '
<nav id="nav'.$this->id.'">

    <ul>';

        $i = 0;

        $entryCount = count($this->entries);

        foreach($this->entries as $entry) {

            // CSS classes
            $classes = '';

            // Link
            $link = '';

            // Determine css classes of this entry
            if($i == 0) {
                $classes .= "first ";
            }

            if($i == $entryCount - 1) {
                $classes .= "last ";
            }

            // Type (internal/external link) dependant stuff
            switch($entry['type']) {

                case EntryTypes::PAGE_ID:

                    // Is this the current page?
                    if($entry['pageID'] == \Skies::$page->getId()) {
                        $classes .= "active ";
                    }

                    // Make the link
                    $link = SUBDIR.'/'.\skies\util\PageUtil::gerNameFromID($entry['pageID']);

                    break;

                case EntryTypes::PAGE_NAME:

                    // Is this the current page?
                    if($entry['pageName'] == \Skies::$page->getName()) {
                        $classes .= "active ";
                    }

                    // Make the link
                    $link = SUBDIR.'/'.$entry['pageName'];

                    break;

                case EntryTypes::EXTERNAL_LINK:

                    // Make the link
                    $link = $entry['link'];

                    break;

            }

            // Remove last space
            $classes = trim($classes);

            // Output
            $buffer .= "
        <li";
            if(!empty($classes)) {
                $buffer .= " class=\"$classes\"";
            }
            $buffer .= ">\n
            <a href=\"$link\">".\Skies::$language->replaceVars($entry['title'])."</a>\n
        </li>\n
";

            $i++;

        }


        $buffer .= '
    </ul>

    <div class="clear"></div>

</nav>';

        echo $buffer;


    }

}

?>