<?php

namespace skies\system\page;

/**
 * @author    Janek Ostendorf (ozzy) <ozzy2345de@gmail.com>
 * @copyright Copyright (c) Janek Ostendorf
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU General Public License, version 3
 * @package
 */
abstract class Page {

    /**
     * ID of this page
     * @var int
     */
    protected $id;

    /**
     * Title of this page
     * @var string
     */
    protected $title;

    /**
     * Short name of this page
     * @var string
     */
    protected $name;

    /**
     * Is the execution of PHP enabled on this page?
     * @var bool
     */
    protected $php;

    /**
     * Data directly fetched from the DB
     * @var array
     */
    protected $data;

    /**
     * Fetch data from the DB about this page
     * @param int $id Page ID
     */
    public function __construct($id) {

        $query = 'SELECT * FROM '.TBL_PRE.'page WHERE pageID = '.\escape($id);

        $result = \Skies::$db->query($query);

        if($result->num_rows != 1 || $result === false) {
            // TODO: Add 404 error
            return false;
        }

        $this->data = $result->fetch_array(MYSQLI_ASSOC);

        // Set stuff
        $this->id = $id;
        $this->title = $this->data['pageTitle'];
        $this->name = $this->data['pageName'];
        $this->php = ($this->data['pagePHP'] == 1);

        $this->onInit();

    }

    /**
     * Shows the page content
     *
     * @abstract
     * @return void
     */
    abstract public function show();

    /**
     * Stuff to do after __construct and init
     *
     * @abstract
     * @return void
     */
    abstract protected function onInit();

    /**
     * @return string
     */
    public function getName() {

        return $this->name;
    }

    /**
     * @return string
     */
    public function getTitle() {

        return $this->title;
    }

    /**
     * @return int
     */
    public function getId() {

        return $this->id;
    }


}

?>