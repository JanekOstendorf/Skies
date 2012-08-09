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
    //protected $data = [];

    /**
     * Default language?
     *
     * @var bool
     */
    protected $default = false;

    /**
     * Buffer for already fetched language vars
     *
     * @var array
     */
    protected $buffer = [];

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

        /*// Fetch data
        $query = 'SELECT * FROM `'.TBL_PRE.'language-data` WHERE `langID` = '.\escape($id);

        $this->data = \Skies::$db->query($query)->fetch_array(MYSQLI_ASSOC);*/

        // Blah blah
        $this->default = $default;
        $this->id      = $id;

    }

    public function get($var, $userVars = [], $nl2br = false) {

        if(isset($this->buffer[$var])) {

            return ($nl2br ? nl2br($this->buffer[$var], true) : $this->buffer[$var]);

        }
        else {

            if(explode('.', $var)[0] == 'config')
                $varData = $this->replaceVars($this->getConfig($var), $userVars);
            else
                $varData = $this->replaceVars($this->getDB($var), $userVars);

            // Save to the buffer
            $this->buffer[$var] = $varData;

            if($varData == $var)
                $varData = '{{'.$varData.'}}';

            return ($nl2br ? nl2br($varData, true) : $varData);

        }

    }

    /**
     * Fetch the language variable form the DB
     *
     * @param string $var language variable
     *
     * @return string Content of the language variable
     */
    protected function getDB($var) {

        $query = 'SELECT * FROM `'.TBL_PRE.'language-data` WHERE langID = '.\escape($this->id).' AND varName = \''.\escape($var).'\'';

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

    protected function getConfig($var) {

        // Explode
        $var_arr = explode('.', $var);

        // Remove the 'config' from the start
        $var_arr = array_slice($var_arr, 1);

        // temporary array
        $tmp = \Skies::$config;

        // Try to get the string, recurse deeper and deeper ...
        foreach($var_arr as $cur) {

            if(isset($tmp[$cur])) {
                $tmp = $tmp[$cur];
            }
            else {
                $tmp = null;
                break;
            }
        }

        return $tmp;

    }


    public function replaceVars($varData, $userVars = []) {

        $matches = [];

        // Language vars (lower case)
        if(preg_match_all('/\{\{[a-z0-9\.-_]+\}\}/', $varData, $matches) > 0) {

            foreach($matches[0] as $tag) {

                $varName = substr($tag, 2, strlen($tag) - 4);

                $varData = str_replace($tag, $this->get($varName), $varData);

            }

        }

        $matches = [];

        // Constants (upper case)
        if(preg_match_all('/\{\{[A-Z0-9\.-_]+\}\}/', $varData, $matches) > 0) {

            foreach($matches[0] as $tag) {

                $constName = substr($tag, 2, strlen($tag) - 4);

                if(defined($constName))
                    $varData = str_replace($tag, constant($constName), $varData);

            }

        }

        if(!empty($userVars)) {

            $matches = [];

            if(preg_match_all('/\[\[[a-z0-9\.-_]+\]\]/', $varData, $matches) > 0) {

                foreach($matches[0] as $tag) {

                    $varName = substr($tag, 2, strlen($tag) - 4);

                    if(isset($userVars[$varName]))
                        $varData = str_replace($tag, $userVars[$varName], $varData);

                }

            }

        }

        return $varData;

    }

}

?>