<?php

namespace skies\system\user;

/**
 * @author    Janek Ostendorf (ozzy) <ozzy2345de@gmail.com>
 * @copyright Copyright (c) Janek Ostendorf
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU General Public License, version 3
 * @package   skies.user
 */
class User {

    /**
     * User ID
     *
     * @var int
     */
    protected $id;

    /**
     * User name
     *
     * @var string
     */
    protected $name;

    /**
     * User's mail address
     *
     * @var string
     */
    protected $mail;

    public function __construct($userID) {

        // Normal users
        if ($userID != GUEST_ID) {

            // Fetch info
            $result = \Skies::$db->query("SELECT * FROM " . TBL_PRE . 'users WHERE userID = ' . escape($userID));

            $data = $result->fetch_array();

            // Write into our vars
            $this->id   = $data['userID'];
            $this->name = $data['userName'];
            $this->mail = $data['userMail'];
        }

        // Guests
        else {

            $this->id   = GUEST_ID;
            $this->name = null;
            $this->mail = null;

        }

    }

    /**
     * Update user's info. Writes to DB first and then fetches new stuff
     */
    public function update() {

        // No need for this if we're a guest
        if($this->isGuest())
            return;

        // Write stuff into DB
        $query = 'UPDATE ' . TBL_PRE . 'user
            SET `userMail` = \'' . escape($this->mail) . '\',
            `userName` = \'' . escape($this->name) . '\'
            WHERE `userID` = ' . escape($this->id);

        \Skies::$db->query($query);

        // Fetch stuff again
        $this->__construct($this->id);


    }

    /**
     * Is this user a guest?
     *
     * @return bool
     */
    public function isGuest() {

        return $this->id == GUEST_ID;

    }

    /**
     * @return string User name
     */
    public function getName() {

        return $this->name;

    }

    /**
     * @return int User ID
     */
    public function getId() {

        return $this->id;

    }

    /**
     * @return string User's mail address
     */
    public function getMail() {

        return $this->mail;

    }

    /**
     * Changes user's user name
     *
     * @param string $name User name
     */
    public function setName($name) {

        $this->name = $name;

    }

    /**
     * Changes user's mail address
     *
     * @param string $mail User's mail address
     */
    public function setMail($mail) {

        $this->mail = $mail;

    }


}

?>