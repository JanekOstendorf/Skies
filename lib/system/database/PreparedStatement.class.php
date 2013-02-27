<?php
/**
 * @author    Janek Ostendorf (ozzy) <ozzy2345de@gmail.com>
 * @copyright Copyright (c) 2013 Janek Ostendorf
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU General Public License, version 3
 */

namespace skies\system\database;

use skies\system\exception\SystemException;

/**
 * PDO wrapper for prepared statements
 */
class PreparedStatement {

	/**
	 * Database of this statement
	 *
	 * @var Database
	 */
	protected $database = null;

	/**
	 * PDO statement
	 *
	 * @var \PDOStatement
	 */
	protected $pdoStatement = null;

	/**
	 * Query of this statement
	 *
	 * @var string
	 */
	protected $query = '';

	/**
	 * Parameters to execute with
	 *
	 * @var array
	 */
	protected $parameters = [];

	/**
	 * @param Database      $database     Database to execute this query in
	 * @param \PDOStatement $pdoStatement PDO statement object
	 * @param string        $query        Query of this statement
	 */
	public function __construct(Database $database, \PDOStatement $pdoStatement, $query = '') {

		$this->database     = $database;
		$this->pdoStatement = $pdoStatement;
		$this->query        = $query;

	}

	/**
	 * Delegates inaccessible methods calls to the decorated object.
	 *
	 * @param string $name
	 * @param array  $arguments
	 * @throws SystemException
	 * @throws DatabaseException
	 * @return mixed
	 */
	public function __call($name, $arguments) {

		if(!method_exists($this->pdoStatement, $name)) {
			throw new SystemException('unknown method \''.$name.'\'');
		}

		try {
			return call_user_func_array(array($this->pdoStatement, $name), $arguments);
		}
		catch(\PDOException $e) {
			throw new DatabaseException('Could not handle prepared statement: '.$e->getMessage(), $this->database, $this);
		}

	}

	/**
	 * Executes the prepared statement
	 *
	 * @param array $parameters Parameters for the execution
	 * @throws DatabaseException
	 */
	public function execute($parameters = []) {

		$this->parameters = $parameters;
		$this->database->incrementQueryCount();

		try {

			if(empty($parameters))
				$this->pdoStatement->execute();
			else
				$this->pdoStatement->execute($parameters);

		}
		catch(\PDOException $e) {
			throw new DatabaseException('Could not execute prepared statement: '.$e->getMessage(), $this->database, $this);
		}

	}

	/**
	 * Fetch the nex row in a result in an array.
	 *
	 * @param int $type Fetch type (See PDO constants)
	 * @return mixed
	 */
	public function fetchArray($type = null) {

		if($type === null)
			$type = \PDO::FETCH_ASSOC;

		return $this->fetch($type);

	}

	/**
	 * Fetch an iteratable object from the next row.
     *
     * @return \stdClass
	 */
	public function fetchObject() {

		$row = $this->fetchArray();

		if($row !== false) {
			return (object) $row;
		}

		return null;

	}

	/**
	 * Fetch all objects in an array
	 *
	 * @return \stdClass[]
	 */
	public function fetchAllObject() {

		$objects = [];

		while($row = $this->fetchObject()) {

			$objects[] = $row;

		}

		return $objects;

	}

	/**
	 * @return array
	 */
	public function getParameters() {

		return $this->parameters;
	}

	/**
	 * @return string
	 */
	public function getQuery() {

		return $this->query;
	}

	/**
	 * Get the error code/number.
	 *
	 * @return int
	 */
	public function getErrorNumber() {

		if($this->pdoStatement !== null) {

			return $this->pdoStatement->errorCode();

		}

		return 0;

	}

	/**
	 * Get the error message.
	 *
	 * @return string
	 */
	public function getErrorDesc() {

		if($this->pdoStatement !== null) {

			if(isset($this->pdoStatement->errorInfo()[2]))
				return $this->pdoStatement->errorInfo()[2];

		}

		return '';

	}


}
