<?php

namespace skies\form;

/**
 * @author    Janek Ostendorf (ozzy) <ozzy2345de@gmail.com>
 * @copyright Copyright (c) Janek Ostendorf
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU General Public License, version 3
 * @package   skies.form
 */
class FormHandler {

    /**
     * Form object
     *
     * @var \skies\form\Form
     */
    protected $form = null;

    /**
     * Is the form submitted?
     *
     * @var bool
     */
    protected $submitted = false;

    /**
     * HTTP method
     *
     * @var string
     */
    protected $method = '';

    /**
     * ID-string of the form
     *
     * @var string
     */
    protected $id = '';

    /**
     * Array holding all submitted data, either from $_POST or $_GET
     *
     * @var array
     */
    protected $request = [];

    /**
     * Handles a form
     *
     * @param \skies\form\Form $form Form to handle
     */
    public function __construct($form) {

        if(!($form instanceof \skies\form\Form)) {
            return false;
        }

        $this->form = $form;

        $this->id = $this->form->getId();
        $this->method = strtoupper($this->form->getMethod());

        // Chose $_GET or $_POST
        switch($this->method) {

            case 'POST':
                $this->request = $_POST;
                break;

            case 'GET':
                $this->request = $_GET;
                break;

            default:
                $this->request = $_REQUEST;

        }


        // Is the form submitted?
        if(isset($this->request['formID']) && $this->request['formID'] == $this->id) {
            $this->submitted = true;
        }

    }

    /**
     * Is the form submitted?
     *
     * @return bool
     */
    public function isSubmitted() {

        return $this->submitted;
    }

    /**
     * Are all required fields filled?
     *
     * @return bool
     */
    public function isCompleted() {

        $completed = true;

        foreach($this->form->getData() as $key => $current) {

            if($current['required'] === true && !isset($this->request[$current['name']])) {
                $completed = false;
            }

        }

        return $completed;

    }

    /**
     * Do all fields match the required pattern?
     *
     * @return bool
     */
    public function checkPatterns() {

        $matches = true;

        foreach($this->form->getData() as $key => $current) {

            if(!empty($current['pattern']) && preg_match('/^'.$current['pattern'].'$/', $this->request[$current['name']]) == 0) {
                $matches = false;
            }

        }

        return $matches;

    }

    /**
     * Gets the specifications of the fields with the submitted value
     *
     * @return array
     */
    public function getData() {

        $data = $this->form->getData();

        foreach($data as $current) {

            $data[$current['name']] = $this->request[$current['name']];

        }

        return $data;

    }

    /**
     * Returns an array containing information about all form fields
     *
     * @return array
     */
    public function getFields() {

        return $this->form->getData();

    }

}

?>