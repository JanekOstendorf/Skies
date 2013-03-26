<?php
/**
 * @author    Janek Ostendorf (ozzy) <ozzy2345de@gmail.com>
 * @copyright Copyright (c) 2013 Janek Ostendorf
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU General Public License, version 3
 */

namespace skies\system\database;

/**
 * PDO wrapper for MySQL connections
 */
class MysqlDatabase extends Database {

	/**
	 * Connect to the SQL server
	 */
	public function connect() {

		// Determine port number
		$port = ((!$this->port || $this->port == 0) ? 3306 : $this->port);

		//
		$dsn = 'mysql:host='.$this->host.';port='.$port.';dbname='.$this->database;

		$options = [
			\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
		];

		try {
			$this->pdo = new \PDO($dsn, $this->user, $this->password, $options);
		}
		catch(\PDOException $e) {
			throw new DatabaseException("Connecting to MySQL server '".$this->host."' failed:\n".$e->getMessage(), $this);
		}

	}

	/**
	 * Is this type of database supported
	 *
	 * @return bool
	 */
	public function isSupported() {

		return (extension_loaded('PDO') && extension_loaded('pdo_mysql'));

	}

}
