<?php

namespace skies\system\language;

/**
 * @author    Janek Ostendorf (ozzy) <ozzy2345de@gmail.com>
 * @copyright Copyright (c) Janek Ostendorf
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU General Public License, version 3
 * @package   skies.system.language
 */
class Language {

    /**
     * Language ID
     *
     * @var int
     */
    protected $id = 0;

    /**
     * Short language identifier
     *
     * @var string
     */
    protected $name = '';

    /**
     * Detailed name
     *
     * @var string
     */
    protected $title = '';

    /**
     * Array holding all the data
     *
     * @var array<mixed>
     */
    protected $data = [];

    /**
     * Default language?
     *
     * @var bool
     */
    protected $default = false;

    /**
     * Hm, what do you think this __construct does ... coffee?
     *
     * @param int  $id      Language ID
     * @param bool $default Is this the default language?
     */
    public function __construct($id, $default = false) {

        // Fetch info
        $query = 'SELECT * FROM '.TBL_PRE.'language WHERE `langID` = '.\escape($id);

        $data = \Skies::$db->query($query)->fetch_array(MYSQLI_ASSOC);

        $this->name  = $data['langName'];
        $this->title = $data['langTitle'];

        // Fetch data
        $query = 'SELECT * FROM '.TBL_PRE.'languagedata WHERE `langID` = '.\escape($id);

        $this->data = \Skies::$db->query($query)->fetch_array(MYSQLI_ASSOC);

        // Blah blah
        $this->default = $default;
        $this->id      = $id;

    }

    /**
     * Fetch the language variable
     *
     * @param string $var language variable
     *
     * @return string Content of the language variable
     */
    public function get($var) {

        $query = 'SELECT * FROM '.TBL_PRE.'languagedata WHERE langID = '.\escape($this->id).' AND varName = \''.\escape($var).'\'';

        $result = \Skies::$db->query($query);

        if($result->num_rows == 0 && !$this->default) {

            return \Skies::$defLanguage->get($var);

        }
        elseif($result->num_rows == 1) {

            return $result->fetch_array(MYSQLI_ASSOC)['varData'];

        }
        else {

            return $var;

        }


    }

}

?>