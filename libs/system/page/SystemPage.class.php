<?php

namespace skies\system\page;

/**
 * @author    Janek Ostendorf (ozzy) <ozzy2345de@gmail.com>
 * @copyright Copyright (c) Janek Ostendorf
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU General Public License, version 3
 * @package   skies.system.page
 */
class SystemPage extends FilePage {

    /**
     * Init the system page
     *
     * @param string $name Page name
     */
    public function __construct($name) {

        $this->data = [];

        $this->title = \Skies::$language->get('system.page.'.$name.'.title');
        $this->name  = $name;
        $this->php   = true;

        $this->onInit();

    }

    /**
     * Stuff to do after __construct and init
     *
     * @return void
     */
    protected function onInit() {

        $this->file    = $this->name.'.page.php';
        $this->incFile = $this->name.'.page.inc.php';

    }

    /**
     * Shows the page content
     *
     * @return void
     */
    public function show() {

        include ROOT_DIR.'/page/system/'.$this->file;

    }

    /**
     * Gets the full path to the include file
     *
     * @return string|bool Full path to the include file or false if there is no include file
     */
    public function getIncFile() {

        return ROOT_DIR.'/page/system/include/'.$this->incFile;

    }


}

?>