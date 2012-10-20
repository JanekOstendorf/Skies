<?php

namespace skies\form;

/**
 * @author    Janek Ostendorf (ozzy) <ozzy2345de@gmail.com>
 * @copyright Copyright (c) Janek Ostendorf
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU General Public License, version 3
 * @package   skies.form
 */
class Form {

    /**
     * URL to send the data to. Leave empty for the sender URL.
     *
     * @var string
     */
    protected $action = '';

    /**
     * HTTP method. `get` or `post`
     *
     * @var string
     */
    protected $method = '';

    /**
     * ID of the form
     *
     * @var string
     */
    protected $id = '';

    /**
     * Array holding the data about all form elements
     *
     * @var array
     */
    protected $data = [];

    /**
     * Used IDs for input fields
     *
     * @var array
     */
    protected static $usedInputIDs = [];


    /**
     * @param string $action URL to send the data to. Leave empty for the sender URL.
     * @param string $method HTTP method. `get` or `post`
     */
    public function __construct($action = '', $method = 'post') {

        $this->action = $action;
        $this->method = $method;

    }

    /**
     * Add an input element
     *
     * @param string $name
     * @param string $description
     * @param bool   $required
     * @param string $type
     * @param string $pattern
     */
    public function addInput($name, $description, $required = false, $type = 'text', $pattern = '') {

        $this->data[] = [

            'type' => 'input',

            'name' => $name,
            'description' => $description,
            'htmlType' => $type,
            'pattern' => $pattern,
            'required' => $required

        ];

    }

    /**
     * Prints the form
     */
    public function printForm($indent = 0) {

        // Define the ID - if not already done
        // This is done here, because the object is done when this function is called
        if(empty($this->id)) {
            $this->id = \skies\util\StringUtil::getHash(var_export($this, true));
        }

        $indent = \skies\util\StringUtil::getIndent($indent);

        $buffer = $indent.'<form action="'.$this->action.'" method="'.$this->method.(empty($this->id) ? '">' : '" id="'.$this->id.'">')."\n";

        // Identifier
        $buffer .= $indent.'    <input type="hidden" name="formID" value="'.$this->id.'" />'."\n";

        $buffer .= $indent.'    <table>'."\n";

        foreach($this->data as $element) {

            if($element['type'] == 'input') {

                $buffer .= $this->buildInput($element, $indent);

            }

        }

        $buffer .= $indent.'    </table>'."\n";
        $buffer .= '</form>'."\n";

        return print $buffer;

    }

    protected function buildInput($array, $indent = 0) {

        $indent = \skies\util\StringUtil::getIndent($indent + 8);

        $buffer = '';

        $id = $array['name'];

        if(in_array($array['name'], self::$usedInputIDs)) {

            $i = 0;

            while(in_array($array['name'].$i, self::$usedInputIDs)) {

                $i++;

            }

            $id = $array['name'].$i;

        }

        // Normal input
        if($array['htmlType'] != 'submit') {

            $buffer .= $indent.'<tr class="nohover">'."\n";
            $buffer .= $indent.'    <td><label for="'.$id.'">'.$array['description'].(!empty($array['description']) ? ':' : '').'</label></td>'."\n";

            $buffer .= $indent.'    <td><input '.($array['htmlType'] != 'class="checkbox" ' ? 'full-width' : '');

            // Attributes
            $buffer .= ' type="'.$array['htmlType'].'"';
            $buffer .= ' name="'.$array['name'].'"';
            $buffer .= ' id="'.$id.'"';

            if(!empty($array['pattern'])) {
                $buffer .= ' pattern="'.$array['pattern'].'"';
            }

            if($array['required']) {
                $buffer .= ' required="required"';
            }

            // Closing

            $buffer .= ' /></td>'."\n";

            $buffer .= $indent.'</tr>'."\n";

        }
        else {

            $buffer .= $indent.'<tr class="nohover">'."\n";

            $buffer .= $indent.'    <td colspan="2"><input type="'.$array['htmlType'].'" name="'.$array['name'].'" id="'.$id.'" value="'.$array['description'].'" /></td>'."\n";

            $buffer .= $indent.'</tr>'."\n";

        }

        $usedInputIDs[] = $id;

        return $buffer;

    }

    /**
     * @return string
     */
    public function getMethod() {

        return $this->method;
    }

    /**
     * @return string
     */
    public function getId() {

        return $this->id;
    }

    /**
     * @return array
     */
    public function getData() {

        return $this->data;
    }

    /**
     * @return string
     */
    public function getAction() {

        return $this->action;
    }

    /**
     * Gets the FormHandler for this form
     *
     * @return FormHandler
     */
    public function getHandler() {

        // Define the ID - if not already done
        // This is done here, because the object is done when this function is called
        if(empty($this->id)) {
            $this->id = \skies\util\StringUtil::getHash(var_export($this, true));
        }

        return new \skies\form\FormHandler($this);

    }

}

?>