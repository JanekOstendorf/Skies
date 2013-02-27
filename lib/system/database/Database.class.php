<?php
/**
 * @author    Janek Ostendorf (ozzy) <ozzy2345de@gmail.com>
 * @copyright Copyright (c) 2013 Janek Ostendorf
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU General Public License, version 3
 */

namespace skies\system\database;

use PDOStatement;

/**
 * PDO wrapper for our project
 */
abstract class Database {

	/**
	 * SQL server's hostname
	 *
	 * @var string
	 */
	protected $host = '';

	/**
	 * SQL server's port
	 *
	 * @var int
	 */
	protected $port = 0;

	/**
	 * User name for login at the SQL server
	 *
	 * @var string
	 */
	protected $user = '';

	/**
	 * Password for login at the SQL server
	 *
	 * @var string
	 */
	protected $password = '';

	/**
	 * Database name
	 *
	 * @var string
	 */
	protected $database = '';


	/**
	 * Number of executed queries
	 *
	 * @var int
	 */
	protected $queryCount = 0;


	/**
	 * PDO object
	 *
	 * @var \PDO
	 */
	protected $pdo = null;


	/**
	 * Create a database object
	 *
	 * @param string  $host     SQL server host name
	 * @param string  $user     User name for SQL login
	 * @param string  $password Password for SQL login
	 * @param string  $database Database name
	 * @param integer $port     Port number
	 */
	public function __construct($host, $user, $password, $database, $port) {

		// Save specifiers
		$this->host     = $host;
		$this->user     = $user;
		$this->password = $password;
		$this->database = $database;
		$this->port     = $port;

		// Connect!
		$this->connect();

	}

	/**
	 * Connect to the SQL server
	 *
	 * @throws DatabaseException
	 */
	abstract public function connect();

    /**
     * Is this type of database supported
     *
     * @return bool
     */
    abstract public function isSupported();

	/**
	 * Get the ID of the last inserted row
	 *
	 * @return integer Last insert ID
	 * @throws DatabaseException
	 */
	public function getInsertId() {

		try {

			return $this->pdo->lastInsertId();

		}
		catch(\PDOException $e) {
			throw new DatabaseException('Failed to fetch last insert id.', $this);
		}

	}

	/**
	 * @param string $query Query to be prepared
	 * @return PreparedStatement
	 * @throws DatabaseException
	 */
	public function prepare($query) {

		try {

			$pdoStatement = $this->pdo->prepare($query);

			if($pdoStatement instanceof PDOStatement) {

				return new PreparedStatement($this, $pdoStatement, $query);

			}

		}
		catch(\PDOException $e) {
			throw new DatabaseException('Failed to prepare statement.', $this);
		}

	}

	/**
	 * Execute a query directly
	 *
	 * @param string $query Query
	 * @return PreparedStatement
	 * @throws DatabaseException
	 */
	public function query($query) {

		try {

			$pdoStatement = $this->pdo->query($query);

			if($pdoStatement instanceof PDOStatement) {

				return new PreparedStatement($this, $pdoStatement, $query);

			}

		}
		catch(\PDOException $e) {
			throw new DatabaseException('Failed to execute query.', $this);
		}

	}

	/**
	 * Of which type is this DB connection
	 *
	 * @return string
	 */
	public function getDbType() {

		return get_class($this);

	}

	/**
	 * Get the SQL version
	 *
	 * @return string
	 */
	public function getVersion() {

		try {

			if($this->pdo !== null) {
				return $this->pdo->getAttribute(\PDO::ATTR_SERVER_VERSION);
			}

		}
		catch(\PDOException $e) {
			// Just return 'unknown'
		}

		return 'unknown';

	}

	/**
	 * Returns the number of the last error.
	 *
	 * @return int
	 */
	public function getErrorNumber() {

		if($this->pdo !== null)
			return $this->pdo->errorCode();

		return 0;

	}

	/**
	 * Returns the description of the last error.
	 *
	 * @return string
	 */
	public function getErrorDesc() {

		if($this->pdo !== null) {

			if(isset($this->pdo->errorInfo()[2]))
				return $this->pdo->errorInfo()[2];

		}

		return '';

	}

	/**
	 * Get the database name.
	 *
	 * @return string
	 */
	public function getDatabase() {

		return $this->database;

	}

	/**
	 * Get the number of executed queries.
	 *
	 * @return int
	 */
	public function getQueryCount() {

		return $this->queryCount;

	}

	/**
	 * Get the name of the DB user.
	 *
	 * @return string
	 */
	public function getUser() {

		return $this->user;

	}

	/**
	 * Add one to the query count.
	 */
	public function incrementQueryCount() {

		$this->queryCount++;

	}

	/**
	 * Escape a string for use in a SQL query.
	 *
	 * @param $string
	 * @return string
	 */
	public function escapeString($string) {

		return addslashes($string);

	}


}
