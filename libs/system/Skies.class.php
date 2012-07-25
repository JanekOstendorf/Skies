<?php

// Options
require_once ROOT_DIR.'/options.inc.php';

define('NOW', time());

// set exception handler
set_exception_handler(['\Skies', 'handleException']);

// set php error handler
if(DEBUG)
    error_reporting(E_ALL);
else
    error_reporting(E_ALL ^ E_NOTICE);

set_error_handler(['\Skies', 'handleError'], E_ALL);

// set autoload handler
spl_autoload_register(['\Skies', 'autoload']);

use skies\system\database\MySQL;

/**
 * @author    Janek Ostendorf (ozzy) <ozzy2345de@gmail.com>
 * @copyright Copyright (c) Janek Ostendorf
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU General Public License, version 3
 * @package   skies.system
 */
class Skies {

    /**
     * MySQL handler
     * @var MySQL
     */
    public static $db;

    public function __construct() {

        $this->initMysql();

    }

    /*
     * Init functions
     */

    private function initMysql() {

        // NULL values
        $dbHost = $dbUser = $dbPassword = $dbName = '';

        // Fetch configuration
        require_once ROOT_DIR . '/libs/config.inc.php';

        self::$db = new skies\system\database\MySQL($dbHost, $dbUser, $dbPassword, $dbName);

    }

    /**
     * Handle our Exceptions
     *
     * @static
     * @param \Exception $e
     */
    public static final function handleException(\Exception $e) {

        if ($e instanceof skies\system\exception\SystemException) {

            $e->show();
            exit;

        }

        // repack Exception
        self::handleException(new skies\system\exception\SystemException($e->getMessage(), $e->getCode(), '', $e));
    }

    /**
     * Catches php errors and throws instead a system exception.
     *
     * @param    integer        $errorNo
     * @param    string         $message
     * @param    string         $filename
     * @param    integer        $lineNo
     * @throws skies\system\exception\SystemException
     */
    public static final function handleError($errorNo, $message, $filename, $lineNo) {

        if (error_reporting() != 0) {
            $type = 'error';
            switch ($errorNo) {
                case 2:
                    $type = 'warning';
                    break;
                case 8:
                    $type = 'notice';
                    break;
            }

            throw new skies\system\exception\SystemException('PHP ' . $type . ' in file ' . $filename . ' (' . $lineNo . '): ' . $message, 0);
        }
    }

    /**
     * Includes the required util or exception classes automatically.
     *
     * @param     string        $className
     * @see        spl_autoload_register()
     */
    public static final function autoload($className) {

        $namespaces = explode('\\', $className);

        // Is it a valid import?
        if (array_shift($namespaces) == 'skies') {

            $classPath = ROOT_DIR . '/libs/' . implode('/', $namespaces) . '.class.php';

            if (file_exists($classPath)) {
                require_once($classPath);
            }

        }

    }

}

?>