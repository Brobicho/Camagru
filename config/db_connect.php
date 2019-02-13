<?php
require_once("database.php");
$db = db_connect($DB_dsn, $DB_user, $DB_password);

function db_connect($DB_dsn, $DB_user, $DB_password) {
try {
    $db = new PDO($DB_dsn, $DB_user, $DB_password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return($db);
}
catch(Exception $e) {
    echo "Error : " . $e . PHP_EOL;
	}
}
?>
