<?php
function __autoload($class) {
  require APP_ROOT . '/lib/' . strtolower($class) . '.php';
}
function echos($text, $color="normal") {
  static $colors = array(
    'light_red' => "[1;31m",
		'light_green' => "[1;32m",
		'yellow' => "[1;33m",
		'light_blue' => "[1;34m",
		'magenta' => "[1;35m",
		'light_cyan' => "[1;36m",
		'white' => "[1;37m",
		'normal' => "[0m",
		'black' => "[0;30m",
		'red' => "[0;31m",
		'green' => "[0;32m",
		'brown' => "[0;33m",
		'blue' => "[0;34m",
		'cyan' => "[0;36m",
		'bold' => "[1m",
		'underscore' => "[4m",
		'reverse' => "[7m"
	);
	$str = chr(27) . $colors[$color] . $text . chr(27) . "[0m";
	if (false === FORKED) {
    echo $str;
		return;
	}
  if (Main::$screen === null) {
    return;
	}
  if (false === @fwrite(Main::$screen, $str)) {
    Main::$screen = null;
  }
}
//Method for displaying the help and default variables.
function displayUsage() {
  echo '\r\n';
  echo 'Starting up 7daysManager';
  echo '\r\n';
  echo 'Usage:\r\n';
  echo '\t7daysManager.php [options]\r\n';
  echo '\r\n';
  echo '\toptions:\r\n';
  echo '\t\t--help display this help message\r\n';
  echo '\t\t--log=<filename> The location of the log file (default $log)\r\n';
  echo '\r\n';
}
function syncGameTime() {
  global $API_HOST;
  global $API_PORT;
  global $API_USER;
  global $API_PASS;
  global $syncGameTime;
  global $APP_LOG;
  global $DEBUG_LOGGING;
  //API Call to get game status
  $urlOut = file_get_contents('http://' . $API_HOST . ':' . $API_PORT . '/api/getstats?adminuser=' . $API_USER . '&admintoken=' . $API_PASS);
  $remove = array('{', '}', '"', ':', 'a', 'd', 'e', 'g', 'h', 'i', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'y');
  $queryAPI = explode(',', (str_replace($remove, '', $urlOut)));
  //Check to see if anyone is online
  if($queryAPI[3] >= 0) {
    if($DEBUG_LOGGING == 1){
      $fh = fopen($APP_LOG, 'a') or die("Can't open file");
      fwrite($fh, "DEBUG " . date('Y-m-d H:i:s') . " - URLOUT VAR: " . $urlOut . "\n");
      fwrite($fh, "DEBUG " . date('Y-m-d H:i:s') . " - REMOVE VAR: ");
      fwrite($fh, "DEBUG " . implode(',', $remove) . "\n");
      fwrite($fh, "DEBUG " . date('Y-m-d H:i:s') . " - QUERYAPI VAR: ");
      fwrite($fh, "DEBUG " . implode(',', $queryAPI) . "\n");
    }
    $sql = "UPDATE gameTime SET currentDay='" . $queryAPI[0] . "', currentTime='" . sprintf("%02d", $queryAPI[1]) . ":" . sprintf("%02d", $queryAPI[2]) . "' WHERE id=1";
    if($DEBUG_LOGGING == 1) {
      $fh = fopen($APP_LOG, 'a') or die("Can't open file");
      fwrite($fh, "DEBUG " . date('Y-m-d H:i:s') . " - SQL VAR: " . $sql . "\n");
    }
    if (mysql_query($sql)) {
      // This is the code you want to loop during the service...
      $fh = fopen($APP_LOG, 'a') or die("Can't open file");
      $stringData = date('Y-m-d H:i:s') . " - DB/Server time synced.\n";
      fwrite($fh, $stringData);
    } else {
      die('Error: ' . mysql_error());
      // This is the code you want to loop during the service...
      $fh = fopen($APP_LOG, 'a') or die("Can't open file");
      $stringData = date('Y-m-d H:i:s') . " - ERROR: COULD NOT CONNECT TO DB\n";
      fwrite($fh, $stringData);
    }
  } elseif($DEBUG_LOGGING == 1) {
    $fh = fopen($APP_LOG, 'a') or die("Can't open file");
    $stringData = date('Y-m-d H:i:s') . " - NO PLAYERS -- Recheck in ".$seconds." seconds...\n";
    fwrite($fh, $stringData);
  }
}
function syncOnlinePlayers() {
  global $API_HOST;
  global $API_PORT;
  global $API_USER;
  global $API_PASS;
  global $syncOnlinePlayers;
  global $APP_LOG;
  global $DEBUG_LOGGING;
  //API Call to get game status
  $data = json_decode(file_get_contents('http://' . $API_HOST . ':' . $API_PORT . '/api/executeconsolecommand?adminuser=' . $API_USER . '&admintoken=' . $API_PASS . '&command=lkp%20-online'));
  //{"command":"lkp","parameters":"-online","result":"1. NuTcAsE, id=190, steamid=76561197976204251, online=True, ip=216.143.242.112, playtime=1591 m, seen=2017-09-10 17:06\nTotal of 269 known\n"}
  if($DEBUG_LOGGING == 2){
    foreach($data as $steamid -> steamid) {
      $fh = fopen($APP_LOG, 'a') or die("Can't open file");
      fwrite($fh, "DEBUG " . date('Y-m-d H:i:s') . " - PLAYER OUT: " . $steamid['score'] . "\n");
    }
  }
}
?>
