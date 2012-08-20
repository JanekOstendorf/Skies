<?php

namespace skies\system\page;

/**
 * @author    Janek Ostendorf (ozzy) <ozzy2345de@gmail.com>
 * @copyright Copyright (c) Janek Ostendorf
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU General Public License, version 3
 * @package   skies.system.page
 */
class FilePage extends Page {

    /**
     * File to get the content from
     *
     * @var string
     */
    protected $file;

    /**
     * Store object for globally accessible variables
     * @var \stdClass
     */
    public $store = null;

    /**
     * File to include before showing anything
     *
     * @var string
     */
    protected $incFile;

    protected function onInit() {

        $this->file    = $this->data['pageFile'];
        $this->incFile = $this->data['pageIncFile'];

        $this->store = new \stdClass();

    }


    /**
     * Shows the page content
     *
     * @return void
     */
    public function show() {

        if($this->php) {

            // Make things easier in the page file
            $page = null;
            $page &= $this;

            include ROOT_DIR.'/page/'.$this->file;

        }
        else {

            print file_get_contents(ROOT_DIR.'/page/'.$this->file);

        }

    }

    /**
     * Gets the full path to the include file
     *
     * @return string|bool Full path to the include file or false if there is no include file
     */
    public function getIncFile() {

        return (empty($this->incFile) ? false : ROOT_DIR.'/page/include/'.$this->incFile);

    }
}

?>