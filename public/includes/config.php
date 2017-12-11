<?php
date_default_timezone_set("EST");

//DB Config
if (!defined('DB_HOST')) define('DB_HOST', 'localhost');
if (!defined('DB_USER')) define('DB_USER', '**USER**');
if (!defined('DB_NAME')) define('DB_NAME', '7daysManager');
if (!defined('DB_PASSWORD')) define('DB_PASSWORD', '**PASS**');
if (!defined('TABLE_PREFIX')) define('TABLE_PREFIX', ''); // Leave blank if none

///////////////////////////////////////////////////////////
//MySQL Connection String
$link = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
if (!$link) {
	die('Could not connect: ' .mysql_error());
}
$db_selected = mysql_select_db(DB_NAME, $link);
if (!$db_selected) {
	die('Can\'t use ' . DB_NAME . ': ' . mysql_error());
}
///////////////////////////////////////////////////////////

//Define the config from the DB!
$result = (mysql_query("SELECT configName, configValue FROM siteConfig"));
while ($row = mysql_Fetch_assoc($result)) {
 	if (!defined($row['configName'])) define($row['configName'], $row['configValue']);
}
