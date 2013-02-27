<?php
/**
 * @author    Janek Ostendorf (ozzy) <ozzy2345de@gmail.com>
 * @copyright Copyright (c) 2013 Janek Ostendorf
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU General Public License, version 3
 */

namespace skies\system\database;

use skies\system\exception\SystemException;
use skies\util\StringUtil;

/**
 * Database exceptions
 */
class DatabaseException extends SystemException {

	/**
	 * Error number
	 *
	 * @var int
	 */
	protected $errorNumber = 0;

	/**
	 * Error message
	 *
	 * @var string
	 */
	protected $errorDescription = '';

	/**
	 * SQL version
	 *
	 * @var string
	 */
	protected $sqlVersion = '';

	/**
	 * @var Database
	 */
	protected $db = null;

	/**
	 * Type of the database
	 *
	 * @var string
	 */
	protected $dbType = '';

	/**
	 * @var PreparedStatement
	 */
	protected $preparedStatement = null;

	/**
	 * @param string            $message   Error message
	 * @param Database          $db        Database in which the error appears
	 * @param PreparedStatement $statement Prepared statement in which the error appears
	 */
	public function __construct($message, Database $db, PreparedStatement $statement = null) {

		$this->db                = $db;
		$this->preparedStatement = $statement;
		$this->dbType            = $this->db->getDbType();

		// Prefer statements
		if($this->preparedStatement !== null && $this->preparedStatement->getErrorNumber()) {

			$this->errorNumber      = $this->preparedStatement->getErrorNumber();
			$this->errorDescription = $this->preparedStatement->getErrorDesc();

		}
		else {

			$this->errorNumber      = $this->db->getErrorNumber();
			$this->errorDescription = $this->db->getErrorDesc();

		}

		parent::__construct($message, intval($this->errorNumber));

	}

	/**
	 * @return string
	 */
	public function getDbType() {

		return $this->dbType;

	}

	/**
	 * @return string
	 */
	public function getErrorDescription() {

		return $this->errorDescription;

	}

	/**
	 * @return int
	 */
	public function getErrorNumber() {

		return $this->errorNumber;

	}

	/**
	 * @return string
	 */
	public function getSqlVersion() {

		if($this->sqlVersion === '') {

			try {

				$this->sqlVersion = $this->db->getVersion();

			}
			catch(DatabaseException $e) {
				$this->sqlVersion = 'unknown';
			}

		}

		return $this->sqlVersion;

	}

	/**
	 * Show the complete error
	 */
	public function show() {

		// Put it into the information var
		$this->information .= '<strong>SQL type:</strong> '.StringUtil::encodeHtml($this->dbType).'<br />'.EOL;
		$this->information .= '<strong>SQL error number:</strong> '.StringUtil::encodeHtml($this->errorNumber).'<br />'.EOL;
		$this->information .= '<strong>SQL error message:</strong> '.StringUtil::encodeHtml($this->errorDescription).'<br />'.EOL;
		$this->information .= '<strong>SQL version:</strong> '.StringUtil::encodeHtml($this->getSqlVersion()).'<br />'.EOL;

		// Do we have some additional query stuff?
		if($this->preparedStatement !== null) {

			$this->information .= '<strong>SQL query:</strong> '.StringUtil::encodeHtml($this->preparedStatement->getQuery()).'<br />'.EOL;

			// Parameters?
			$parameters = $this->preparedStatement->getParameters();

			if(!empty($parameters)) {

				foreach($parameters as $key => $value) {

					$this->information .= '<strong>SQL parameter '.$key.':</strong> '.StringUtil::encodeHtml($value).'<br />'.EOL;

				}

			}

		}

		parent::show();

	}


}
