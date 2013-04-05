<?php
/**
 * @author    Janek Ostendorf (ozzy) <ozzy2345de@gmail.com>
 * @copyright Copyright (c) Janek Ostendorf
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU General Public License, version 3
 * @package   skies
 */

/*
 * Init
 */

// Performance reasons ...
define('NOW', time());

// Set exception handler
set_exception_handler(['\Skies', 'handleException']);

set_error_handler(['\Skies', 'handleError'], E_ALL);

// set autoload handler
spl_autoload_register(['\Skies', 'autoload']);

/**
 * Alias for Skies::getDb()->escape_string($string)
 *
 * @param $string
 * @return string
 * @see skies\system\database\MySQL::escape_string()
 */
function escape($string) {

	return Skies::getDb()->escapeString($string);

}
