<?php
/**
 * @author    Janek Ostendorf (ozzy) <ozzy2345de@gmail.com>
 * @copyright Copyright (c) 2013 Janek Ostendorf
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU General Public License, version 3
 */

namespace skies\system\database;

/**
 *
 */
class SqliteDatabase extends Database {

	/**
	 * Connect to the SQL server
	 *
	 * @throws DatabaseException
	 */
	public function connect() {

		// We only need the host, which is the path to the db-file
		$dsn = 'sqlite:'.$this->host;

		$options = [
			\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
		];

		$this->pdo = new \PDO($dsn, null, null, $options);

	}

	/**
	 * Is this type of database supported
	 *
	 * @return bool
	 */
	public function isSupported() {

		return (extension_loaded('PDO') && extension_loaded('pdo_sqlite'));

	}
}
