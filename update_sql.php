<?php
/* Update MySQL DB */

// Load driver
require_once 'lib/system/database/DatabaseException.class.php';
require_once 'lib/system/database/PreparedStatement.class.php';
require_once 'lib/system/database/Database.class.php';
require_once 'lib/system/database/MysqlDatabase.class.php';

// NULL values
$dbHost = $dbUser = $dbPassword = $dbName = '';
$dbPort = 0;

// Fetch configuration
require_once ROOT_DIR.'/lib/config.inc.php';

$db = new \skies\system\database\MysqlDatabase($dbHost, $dbUser, $dbPassword, $dbName, $dbPort);

if(!$db instanceof \skies\system\database\Database || !$db->isSupported())
	die('Error connecting to the SQL server');

// Execute query
$db->query(file_get_contents('docs/default.sql'));
echo 'Database updated to '.date('d.m.Y H:i:s', filemtime('docs/default.sql')).'.';
