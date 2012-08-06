<?php

namespace skies\utils;

/**
 * @author    Janek Ostendorf (ozzy) <ozzy2345de@gmail.com>
 * @copyright Copyright (c) Janek Ostendorf
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU General Public License, version 3
 * @package   skies.utils
 */
class Form {

    /**
     * URL to send the data to. Leave empty for the sender URL.
     * @var string
     */
    protected $action = '';

    /**
     * HTTP method. `get` or `post`
     * @var string
     */
    protected $method = '';

    /**
     * ID of the HTML form object
     * @var string
     */
    protected $id = '';

    /**
     * Array holding the data about all form elements
     * @var array
     */
    protected $data = [];

    protected static $usedIDs = [];


    /**
     * @param string $action URL to send the data to. Leave empty for the sender URL.
     * @param string $method HTTP method. `get` or `post`
     * @param string $id     ID of the HTML form object
     */
    public function __construct($action = '', $method = 'post', $id = '') {

        $this->action = $action;
        $this->method = $method;
        $this->id = $id;

    }

    /**
     * Add an input element
     *
     * @param string $name
     * @param string $description
     * @param string $type
     * @param string $pattern
     */
    public function addInput($name, $description, $type = 'text', $pattern = '') {

        $this->data[] = [

            'type' => 'input',

            'name' => $name,
            'description' => $description,
            'html_type' => $type,
            'pattern' => $pattern

        ];

    }

    /**
     * Prints the form
     */
    public function printForm() {

        $buffer = '';

        $buffer .= '<table>'."\n";

        foreach($this->data as $element) {

            if($element['type'] == 'input') {

               $buffer .= $this->buildInput($element);

            }

        }

        $buffer .= '</table>'."\n";

        return print $buffer;

    }

    protected function buildInput($array) {

        $buffer = '';

        $id = $array['name'];

        if(in_array($array['name'], self::$usedIDs)) {

            $i = 0;

            while(in_array($array['name'].$i, self::$usedIDs)) {

                $i++;

            }

            $id = $array['name'].$i;

        }

        // Normal input
        if($array['html_type'] != 'submit') {

            $buffer .= '<tr class="nohover">'."\n";
            $buffer .= '    <td><label for="'.$id.'">'.$array['description'].(!empty($array['description']) ? ':' : '').'</label></td>'."\n";

            $buffer .= '    <td><input class="full-width" type="'.$array['html_type'].'" name="'.$array['name'].'" id="'.$id.'"'."\n";
            $buffer .= (empty($array['pattern']) ? ' /></td>' : ' pattern="'.$array['pattern'].'" /></td>')."\n";

            $buffer .= '</tr>'."\n";

        }
        else {

            $buffer .= '<tr class="nohover">'."\n";

            $buffer .= '    <td colspan="2"><input type="'.$array['html_type'].'" name="'.$array['name'].'" id="'.$id.'" value="'.$array['description'].'" /></td>'."\n";

            $buffer .= '</tr>'."\n";

        }

        self::$usedIDs[] = $id;

        return $buffer;

    }

}

?>