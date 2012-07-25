<?php

namespace skies\system\database;

/**
 * @author    Janek Ostendorf (ozzy) <ozzy2345de@gmail.com>
 * @copyright Copyright (c) Janek Ostendorf
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU General Public License, version 3
 * @package skies.system.database
 */
class MySQL extends \mysqli {

    /**
     * Hostname of the SQL server
     * @var string
     */
    protected $host = '';

    /**
     * Username for logging into the SQL server
     * @var string
     */
    protected $user = '';

    /**
     * Password for login
     * @var string
     */
    protected $password = '';

    /**
     * Database name
     * @var string
     */
    protected $database = '';

    /**
     * Number of executed queries
     * @var int
     */
    protected $queryCount = 0;

    /**
     * @param string    $host       Hostname of the SQL server
     * @param string    $user       Username for logging into the SQL server
     * @param string    $password   Password for login
     * @param string    $database   Database name
     */
    public function __construct($host, $user, $password, $database) {

        parent::__construct($host, $user, $password, $database, 3306);

        $this->database = $database;
        $this->host = $host;
        $this->password = $password;
        $this->user = $user;

    }

    public function query($query, $resultmode = MYSQLI_STORE_RESULT) {

        $result = parent::query($query, $resultmode);

        if($this->errno != 0) {

            throw new \skies\system\exception\SystemException('MySQL error: '.$this->error, $this->errno, $this->error);

        }

        return $result;

    }


}

?>