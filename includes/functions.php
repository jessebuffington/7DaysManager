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
  global $APP_LOG;
  printf(
    "\n7daysManager\n
    Usage:
      7daysManager.php [options]
      options:
      --help      display this help message
      --log=<filename>  The location of the log file (default: " . APP_LOG . ")\n\n");
}


function syncGameTime() {
  global $API_HOST;
  global $API_PORT;
  global $API_USER;
  global $API_PASS;
  global $interval_syncGameTime;
  global $APP_LOG;
  global $DEBUG_LOGGING;

  $url = 'http://' . API_HOST . ':' . API_PORT . '/api/getstats?adminuser=' . API_USER . '&admintoken=' . API_PASS . '';

  $queryAPI = file_get_contents($url);
  $jsonObject = json_decode($queryAPI, true);

  //var_dump(json_decode($queryAPI, true));

  if($jsonObject['players'] >= 1) {
    if(APP_LOG_LEVEL >= 4) {
      //$fh = fopen(APP_LOG, 'a') or die("Can't open file");
      //fwrite($fh, "DEBUG " . date('Y-m-d H:i:s') . " - URLOUT VAR: " . $url . "\n");
      $log = "insert into app_log (datetime, logLevel, message) values ('" . date('Y-m-d H:i:s') . "', 'DEBUG', 'URLOUT VAR: " . $url . "')";
    }
    $sql = "UPDATE server_gameTime SET currentDay='" . sprintf("%02d", $jsonObject['gametime']['days']). "', currentTime='" . sprintf("%02d", $jsonObject['gametime']['hours']) . ":" . sprintf("%02d", $jsonObject['gametime']['minutes']) . "' WHERE serverID=1";
    if (!mysql_query($sql)) {
      die('Error: ' . mysql_error());
      if(APP_LOG_LEVEL >= 1) {
        //$fh = fopen(APP_LOG, 'a') or die("Can't open file\n" . APP_LOG . "\n");
        //$stringData = date('Y-m-d H:i:s') . " - ERROR: COULD NOT CONNECT TO DB\n";
        //fwrite($fh, $stringData);
        $log = "insert into app_log (datetime, logLevel, message) values ('" . date('Y-m-d H:i:s') . "', 'CRIT', 'ERROR: COULD NOT CONNECT TO DB')";
        if (!mysql_query($log)) {
          die('Error: ' . mysql_error());
        }
      }
    }
    if(APP_LOG_LEVEL >= 3) {
      $log = "insert into app_log (datetime, logLevel, message) values ('" . date('Y-m-d H:i:s') . "', 'INFO', 'DB/Server time synced.')";
      if(!mysql_query($log)) {
        die('Error: ' . mysql_error());
        if(APP_LOG_LEVEL >= 1) {
          //$fh = fopen(APP_LOG, 'a') or die("Can't open file\n" . APP_LOG . "\n");
          //$stringData = date('Y-m-d H:i:s') . " - ERROR: COULD NOT CONNECT TO DB\n";
          //fwrite($fh, $stringData);
          $log = "insert into app_log (datetime, logLevel, message) values ('" . date('Y-m-d H:i:s') . "', 'CRIT', 'ERROR: COULD NOT CONNECT TO DB')";
          if (!mysql_query($log)) {
            die('Error: ' . mysql_error());
          }
        }
      }
    }
  } elseif ($jsonObject['players'] == 0) {
    if (APP_LOG_LEVEL >= 2) {
      //$fh = fopen(APP_LOG, 'a') or die("Can't open file\n" . APP_LOG . "\n");
      //$stringData = date('Y-m-d H:i:s') . " - NO PLAYERS -- Recheck in " . interval_syncGameTime . " seconds...\n";
      //fwrite($fh, $stringData);
      $log = "insert into app_log (datetime, logLevel, message) values ('" . date('Y-m-d H:i:s') . "', 'WARN', 'NO PLAYERS -- Recheck in " . interval_syncGameTime . " seconds...')";
      if(!mysql_query($log)) {
        die('Error: ' . mysql_error());
        if(APP_LOG_LEVEL >= 1) {
          //$fh = fopen(APP_LOG, 'a') or die("Can't open file\n" . APP_LOG . "\n");
          //$stringData = date('Y-m-d H:i:s') . " - ERROR: COULD NOT CONNECT TO DB\n";
          //fwrite($fh, $stringData);
          $log = "insert into app_log (datetime, logLevel, message) values ('" . date('Y-m-d H:i:s') . "', 'CRIT', 'ERROR: COULD NOT CONNECT TO DB')";
          if (!mysql_query($log)) {
            die('Error: ' . mysql_error());
          }
        }
      }
    }
  }
}




/*function syncGameTime() {
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
*/


function syncGameVersion() {
  global $API_HOST;
  global $API_PORT;
  global $API_USER;
  global $API_PASS;
  global $interval_syncGameVersion;
  global $APP_LOG;
  global $APP_LOG_LEVEL;
  //API Call to get game status
  $url = 'http://' . API_HOST . ':' . API_PORT . '/api/executeconsolecommand?adminuser=' . API_USER . '&admintoken=' . API_PASS . '&command=version';

  $queryAPI = file_get_contents($url);
  $jsonObject = json_decode($queryAPI, true);

  //var_dump(json_decode($queryAPI, true));

  if($jsonObject['result'] >= 0) {
    if(DEBUG_LOGGING == 1){
      //$fh = fopen(APP_LOG, 'a') or die("Can't open file");
      //fwrite($fh, "DEBUG " . date('Y-m-d H:i:s') . " - URLOUT VAR: " . $url . "\n");
      $log = "insert into app_log (datetime, logLevel, message) values ('" . date('Y-m-d H:i:s') . "', 'DEBUG', 'URLOUT VAR: " . $url . "')";
      if (!mysql_query($log)) {
        die('Error: ' . mysql_error());
      }
      //fwrite($fh, "DEBUG " . date('Y-m-d H:i:s') . " - REMOVE VAR: ");
      $log = "insert into app_log (datetime, logLevel, message) values ('" . date('Y-m-d H:i:s') . "', 'DEBUG', 'REMOVE VAR: ')";
      if (!mysql_query($log)) {
        die('Error: ' . mysql_error());
      }
      //fwrite($fh, "DEBUG " . implode(',', $remove) . "\n");
      //fwrite($fh, "DEBUG " . date('Y-m-d H:i:s') . " - QUERYAPI VAR: ");
      $log = "insert into app_log (datetime, logLevel, message) values ('" . date('Y-m-d H:i:s') . "', 'DEBUG', 'QUERYAPI VAR: ')";
      if (!mysql_query($log)) {
        die('Error: ' . mysql_error());
      }
      //fwrite($fh, "DEBUG " . implode(',', $jsonObject) . "\n");
      $log = "insert into app_log (datetime, logLevel, message) values ('" . date('Y-m-d H:i:s') . "', 'DEBUG', '" . implode(',', $jsonObject) . "')";
      if (!mysql_query($log)) {
        die('Error: ' . mysql_error());
      }
    }
    $sql = "UPDATE servers SET game_version = '" . $jsonObject['result'] . "' WHERE serverID=1";

    if (!mysql_query($sql)) {
      die('Error: ' . mysql_error());
    }

    if(DEBUG_LOGGING == 1) {
      //$fh = fopen(APP_LOG, 'a') or die("Can't open file\n" . APP_LOG . "\n");
      //fwrite($fh, "DEBUG " . date('Y-m-d H:i:s') . " - SQL VAR: " . $sql . "\n");
      $log = "insert into app_log (datetime, logLevel, message) values ('" . date('Y-m-d H:i:s') . "', 'DEBUG', 'SQL VAR: " . $sql . "')";
      if (!mysql_query($log)) {
        die('Error: ' . mysql_error());
      }
    }
    if (mysql_query($sql)) {
      // This is the code you want to loop during the service...
      //$fh = fopen(APP_LOG, 'a') or die("Can't open file\n" . APP_LOG . "\n");
      //$stringData = date('Y-m-d H:i:s') . " - DB/Server version synced.\n";
      //fwrite($fh, $stringData);
      $log = "insert into app_log (datetime, logLevel, message) values ('" . date('Y-m-d H:i:s') . "', 'DEBUG', 'DB/Server version synced.')";
      if (!mysql_query($log)) {
        die('Error: ' . mysql_error());
      }
    } else {
      die('Error: ' . mysql_error());
      // This is the code you want to loop during the service...
      //$fh = fopen(APP_LOG, 'a') or die("Can't open file\n" . APP_LOG . "\n");
      //$stringData = date('Y-m-d H:i:s') . " - ERROR: COULD NOT CONNECT TO DB\n";
      //fwrite($fh, $stringData);
      $log = "insert into app_log (datetime, logLevel, message) values ('" . date('Y-m-d H:i:s') . "', 'DEBUG', 'ERROR: COULD NOT CONNECT TO DB')";
      if (!mysql_query($log)) {
        die('Error: ' . mysql_error());
      }
    }
  } elseif(DEBUG_LOGGING == 1) {
    //$fh = fopen(APP_LOG, 'a') or die("Can't open file\n" . APP_LOG . "\n");
    //$stringData = date('Y-m-d H:i:s') . " - NO PLAYERS -- Recheck in " . interval_syncGameVersion . " seconds...\n";
    //fwrite($fh, $stringData);
    $log = "insert into app_log (datetime, logLevel, message) values ('" . date('Y-m-d H:i:s') . "', 'DEBUG', 'NO PLAYERS -- Recheck in " . interval_syncGameVersion . " seconds...')";
    if (!mysql_query($log)) {
      die('Error: ' . mysql_error());
    }
  }
}



function syncServerInfo() {
  global $API_HOST;
  global $API_PORT;
  global $API_USER;
  global $API_PASS;
  global $interval_syncServerInfo;
  global $APP_LOG;
  global $DEBUG_LOGGING;
  //API Call to get game status
  $url = 'http://' . API_HOST . ':' . API_PORT . '/api/getserverinfo?adminuser=' . API_USER . '&admintoken=' . API_PASS . '';

  $queryAPI = file_get_contents($url);
  $jsonObject = json_decode($queryAPI, true);

  //var_dump(json_decode($queryAPI, true));

  if($jsonObject['IP']['value'] >= 0) {

    $sql = "UPDATE server_info SET
      gameType = '" . $jsonObject['GameType']['value'] . "',
      gameName = '" . $jsonObject['GameName']['value'] . "',
      levelName = '" . $jsonObject['LevelName']['value'] . "',
      gameMode = '" . $jsonObject['GameMode']['value'] . "',
      version = '" . $jsonObject['Version']['value'] . "',
      serverWebsiteURL = '" . $jsonObject['ServerWebsiteURL']['value'] . "',
      ip = '" . $jsonObject['IP']['value'] . "',
      countryCode = '" . $jsonObject['CountryCode']['value'] . "',
      steamID = '" . $jsonObject['SteamID']['value'] . "',
      compatibilityVersion = '" . $jsonObject['CompatibilityVersion']['value'] . "',
      platform = '" . $jsonObject['Platform']['value'] . "',
      port = '" . $jsonObject['Port']['value'] . "',
      currentPlayers = '" . $jsonObject['CurrentPlayers']['value'] . "',
      maxPlayers = '" . $jsonObject['MaxPlayers']['value'] . "',
      gameDifficulty = '" . $jsonObject['GameDifficulty']['value'] . "',
      dayNightLength = '" . $jsonObject['DayNightLength']['value'] . "',
      zombiesRun = '" . $jsonObject['ZombiesRun']['value'] . "',
      dayCount = '" . $jsonObject['DayCount']['value'] . "',
      ping = '" . $jsonObject['Ping']['value'] . "',
      dropOnDeath = '" . $jsonObject['DropOnDeath']['value'] . "',
      dropOnQuit = '" . $jsonObject['DropOnQuit']['value'] . "',
      bloodMoonEnemyCount = '" . $jsonObject['BloodMoonEnemyCount']['value'] . "',
      enemyDifficulty = '" . $jsonObject['EnemyDifficulty']['value'] . "',
      playerKillingMode = '" . $jsonObject['PlayerKillingMode']['value'] . "',
      currentServertime = '" . $jsonObject['CurrentServerTime']['value'] . "',
      dayLightLength = '" . $jsonObject['DayLightLength']['value'] . "',
      blockDurabilityModifier = '" . $jsonObject['BlockDurabilityModifier']['value'] . "',
      airDropFrequency = '" . $jsonObject['AirDropFrequency']['value'] . "',
      lootAbundance = '" . $jsonObject['LootAbundance']['value'] . "',
      lootRespawnDays = '" . $jsonObject['LootRespawnDays']['value'] . "',
      maxSpawnedZombies = '" . $jsonObject['MaxSpawnedZombies']['value'] . "',
      landClaimSize = '" . $jsonObject['LandClaimSize']['value'] . "',
      landClaimDeadZone = '" . $jsonObject['LandClaimDeadZone']['value'] . "',
      landClaimExpiryTime = '" . $jsonObject['LandClaimExpiryTime']['value'] . "',
      landClaimDecayMode = '" . $jsonObject['LandClaimDecayMode']['value'] . "',
      LandClaimOnlineDurabilityModifier = '" . $jsonObject['LandClaimOnlineDurabilityModifier']['value'] . "',
      LandClaimOfflineDurabilityModifier = '" . $jsonObject['LandClaimOfflineDurabilityModifier']['value'] . "',
      maxSpawnedAnimals = '" . $jsonObject['MaxSpawnedAnimals']['value'] . "',
      isDedicated = '" . $jsonObject['IsDedicated']['value'] . "',
      isPasswordProtected = '" . $jsonObject['IsPasswordProtected']['value'] . "',
      showFriendPlayerOnMap = '" . $jsonObject['ShowFriendPlayerOnMap']['value'] . "',
      buildCreate = '" . $jsonObject['BuildCreate']['value'] . "',
      eacEnabled = '" . $jsonObject['EACEnabled']['value'] . "',
      architecture64 = '" . $jsonObject['Architecture64']['value'] . "',
      stockSettings = '" . $jsonObject['StockSettings']['value'] . "',
      stockFiles = '" . $jsonObject['StockFiles']['value'] . "',
      requiresMod = '" . $jsonObject['RequiresMod']['value'] . "',
      airDropMarker = '" . $jsonObject['AirDropMarker']['value'] . "',
      enemySpawnMode = '" . $jsonObject['EnemySpawnMode']['value'] . "',
      isPublic = '" . $jsonObject['IsPublic']['value'] . "'
      WHERE serverID=1";

//printf($jsonObject['result']);

    //if (!mysql_query($sql)) {
    //  die('Error: ' . mysql_error());
    //}

    if(DEBUG_LOGGING == 1) {
      //$fh = fopen(APP_LOG, 'a') or die("Can't open file\n" . APP_LOG . "\n");
      //fwrite($fh, "DEBUG " . date('Y-m-d H:i:s') . " - SQL VAR: " . $sql . "\n");
    }
    if (mysql_query($sql)) {
      // This is the code you want to loop during the service...
      //$fh = fopen(APP_LOG, 'a') or die("Can't open file\n" . APP_LOG . "\n");
      //$stringData = date('Y-m-d H:i:s') . " - DB/Server info synced.\n";
      //fwrite($fh, $stringData);
      $log = "insert into app_log (datetime, logLevel, message) values ('" . date('Y-m-d H:i:s') . "', 'DEBUG', 'DB/Server info synced.')";
      if (!mysql_query($log)) {
        die('Error: ' . mysql_error());
      }
    } else {
      die('Error: ' . mysql_error());
      // This is the code you want to loop during the service...
      //$fh = fopen(APP_LOG, 'a') or die("Can't open file\n" . APP_LOG . "\n");
      //$stringData = date('Y-m-d H:i:s') . " - ERROR: COULD NOT CONNECT TO DB\n";
      //fwrite($fh, $stringData);
      $log = "insert into app_log (datetime, logLevel, message) values ('" . date('Y-m-d H:i:s') . "', 'DEBUG', 'ERROR: COULD NOT CONNECT TO DB')";
      if (!mysql_query($log)) {
        die('Error: ' . mysql_error());
      }
    }
  } elseif(DEBUG_LOGGING == 1) {
    //$fh = fopen(APP_LOG, 'a') or die("Can't open file\n" . APP_LOG . "\n");
    //$stringData = date('Y-m-d H:i:s') . " - NO PLAYERS -- Recheck in " . interval_syncServerInfo . " seconds...\n";
    //fwrite($fh, $stringData);
    $log = "insert into app_log (datetime, logLevel, message) values ('" . date('Y-m-d H:i:s') . "', 'DEBUG', 'NO PLAYERS -- Recheck in " . interval_syncServerInfo . " seconds...')";
    if (!mysql_query($log)) {
      die('Error: ' . mysql_error());
    }
  }
}



//
//
//NEED TO FIX
//
//
function syncOnlinePlayers() {
  global $API_HOST;
  global $API_PORT;
  global $API_USER;
  global $API_PASS;
  global $syncOnlinePlayers;
  global $APP_LOG;
  global $APP_LOG_LEVEL;

  $url = 'http://' . $API_HOST . ':' . $API_PORT . '/api/executeconsolecommand?adminuser=' . $API_USER . '&admintoken=' . $API_PASS . '&command=lkp%20-online';

  $queryAPI = file_get_contents($url);
  $jsonObject = json_decode($queryAPI, true);

  if($jsonObject['result'] >= 1) {
    if(APP_LOG_LEVEL >= 3) {
      //$fh = fopen(APP_LOG, 'a') or die("Can't open file");
      //fwrite($fh, "DEBUG " . date('Y-m-d H:i:s') . " - URLOUT VAR: " . $url . "\n");
      $log = "insert into app_log (datetime, logLevel, message) values ('" . date('Y-m-d H:i:s') . "', 'DEBUG', 'URLOUT VAR: " . $url . "')";
    }
    $sql = "UPDATE players SET onlineStatus='1' WHERE playerid = '" . $jsonObject['result']['id'] . "'";
    if (!mysql_query($sql)) {
      die('Error: ' . mysql_error());
      if(APP_LOG_LEVEL >= 1) {
        //$fh = fopen(APP_LOG, 'a') or die("Can't open file\n" . APP_LOG . "\n");
        //$stringData = date('Y-m-d H:i:s') . " - ERROR: COULD NOT CONNECT TO DB\n";
        //fwrite($fh, $stringData);
        $log = "insert into app_log (datetime, logLevel, message) values ('" . date('Y-m-d H:i:s') . "', 'CRIT', 'ERROR: COULD NOT CONNECT TO DB')";
        if (!mysql_query($log)) {
          die('Error: ' . mysql_error());
        }
      }
    }
    if(APP_LOG_LEVEL >= 3) {
      $log = "insert into app_log (datetime, logLevel, message) values ('" . date('Y-m-d H:i:s') . "', 'INFO', 'Syncing online users')";
      if(!mysql_query($log)) {
        die('Error: ' . mysql_error());
        if(APP_LOG_LEVEL >= 1) {
          //$fh = fopen(APP_LOG, 'a') or die("Can't open file\n" . APP_LOG . "\n");
          //$stringData = date('Y-m-d H:i:s') . " - ERROR: COULD NOT CONNECT TO DB\n";
          //fwrite($fh, $stringData);
          $log = "insert into app_log (datetime, logLevel, message) values ('" . date('Y-m-d H:i:s') . "', 'CRIT', 'ERROR: COULD NOT CONNECT TO DB')";
          if (!mysql_query($log)) {
            die('Error: ' . mysql_error());
          }
        }
      }
    }
  } elseif ($jsonObject['result'] == 0) {
    if (APP_LOG_LEVEL >= 2) {
      //$fh = fopen(APP_LOG, 'a') or die("Can't open file\n" . APP_LOG . "\n");
      //$stringData = date('Y-m-d H:i:s') . " - NO PLAYERS -- Recheck in " . interval_syncOnlinePlayers . " seconds...\n";
      //fwrite($fh, $stringData);
      $log = "insert into app_log (datetime, logLevel, message) values ('" . date('Y-m-d H:i:s') . "', 'WARN', 'NO PLAYERS -- Recheck in " . interval_syncOnlinePlayers . " seconds...')";
      if(!mysql_query($log)) {
        die('Error: ' . mysql_error());
        if(APP_LOG_LEVEL >= 1) {
          //$fh = fopen(APP_LOG, 'a') or die("Can't open file\n" . APP_LOG . "\n");
          //$stringData = date('Y-m-d H:i:s') . " - ERROR: COULD NOT CONNECT TO DB\n";
          //fwrite($fh, $stringData);
          $log = "insert into app_log (datetime, logLevel, message) values ('" . date('Y-m-d H:i:s') . "', 'CRIT', 'ERROR: COULD NOT CONNECT TO DB')";
          if (!mysql_query($log)) {
            die('Error: ' . mysql_error());
          }
        }
      }
    }
  }
}












?>
