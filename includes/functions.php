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
  global $APP_LOG_LEVEL;

  $url = 'http://' . API_HOST . ':' . API_PORT . '/api/getstats?adminuser=' . API_USER . '&admintoken=' . API_PASS . '';

  $queryAPI = file_get_contents($url);
  $jsonObject = json_decode($queryAPI, true);

  //var_dump(json_decode($queryAPI, true));

  $currDay = $jsonObject['gametime']['days'];
  $currTime =  $jsonObject['gametime']['hours'] . ":" . $jsonObject['gametime']['minutes'];

  if($jsonObject['players'] >= 1) {
    if(APP_LOG_LEVEL >= 1) {
      $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'DEBUG', 'syncGameTime', 'URLOUT VAR: " . $url . "')";
    }
    // 7days Real-time Calculation
    $val1 = floor($currDay / 7);
    $val2 = $val1 + 1;
    $val3 = $val2 * 7;
    $daysLeft = $val3 - $currDay;
    //Dump to log
    echo "Current In-Game Time: " . $currTime . "\n";
    echo "Current In-Game Day: " . $currDay . "\n";
    echo "Days Left: " . $daysLeft . "\n";

    $sql = "UPDATE server_gameTime SET currentDay='" . $currDay . "', currentTime = '" . $currTime . "', daysLeft = '" . $daysLeft . "' WHERE serverID=1";

    if (!mysql_query($sql)) {
      die('Error: ' . mysql_error());
      if(APP_LOG_LEVEL >= 1) {
        $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'CRIT', 'syncGameTime', 'ERROR: COULD NOT CONNECT TO DB')";
        if (!mysql_query($log)) {
          die('Error: ' . mysql_error());
        }
      }
    }
    if(APP_LOG_LEVEL >= 3) {
      $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'INFO', 'syncGameTime', 'DB/Server time synced. Day-" . $currDay . " | Time-" . $currTime . " | DaysLeft-" . $daysLeft . "')";
      if(!mysql_query($log)) {
        die('Error: ' . mysql_error());
        if(APP_LOG_LEVEL >= 1) {
          $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'CRIT', 'syncGameTime', 'ERROR: COULD NOT CONNECT TO DB')";
          if (!mysql_query($log)) {
            die('Error: ' . mysql_error());
          }
        }
      }
    }
  } elseif ($jsonObject['players'] == 0) {
    if (APP_LOG_LEVEL >= 3) {
      $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'INFO', 'syncGameTime', 'NO PLAYERS -- Recheck in " . interval_syncGameTime . " seconds...')";
      if(!mysql_query($log)) {
        die('Error: ' . mysql_error());
        if(APP_LOG_LEVEL >= 1) {
          $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'CRIT', 'syncGameTime', 'ERROR: COULD NOT CONNECT TO DB')";
          if (!mysql_query($log)) {
            die('Error: ' . mysql_error());
          }
        }
      }
    }
  }
}


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
    if(APP_LOG_LEVEL >= 4){
      $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'DEBUG', 'syncGameVersion', 'URLOUT VAR: " . $url . "')";
      if (!mysql_query($log)) {
        die('Error: ' . mysql_error());
        if(APP_LOG_LEVEL >= 1) {
          $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'CRIT', 'syncGameVersion', 'ERROR: COULD NOT CONNECT TO DB')";
          if (!mysql_query($log)) {
            die('Error: ' . mysql_error());
          }
        }
      }
      $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'DEBUG', 'syncGameVersion', 'REMOVE VAR: ')";
      if (!mysql_query($log)) {
        die('Error: ' . mysql_error());
        if(APP_LOG_LEVEL >= 1) {
          $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'CRIT', 'syncGameVersion', 'ERROR: COULD NOT CONNECT TO DB')";
          if (!mysql_query($log)) {
            die('Error: ' . mysql_error());
          }
        }
      }
      $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'DEBUG', 'syncGameVersion', 'QUERYAPI VAR: ')";
      if (!mysql_query($log)) {
        die('Error: ' . mysql_error());
        if(APP_LOG_LEVEL >= 1) {
          $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'CRIT', 'syncGameVersion', 'ERROR: COULD NOT CONNECT TO DB')";
          if (!mysql_query($log)) {
            die('Error: ' . mysql_error());
          }
        }
      }
      $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'DEBUG', 'syncGameVersion', '" . implode(',', $jsonObject) . "')";
      if (!mysql_query($log)) {
        die('Error: ' . mysql_error());
        if(APP_LOG_LEVEL >= 1) {
          $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'CRIT', 'syncGameVersion', 'ERROR: COULD NOT CONNECT TO DB')";
          if (!mysql_query($log)) {
            die('Error: ' . mysql_error());
          }
        }
      }
    }
    $sql = "UPDATE servers SET game_version = '" . $jsonObject['result'] . "' WHERE serverID=1";
    if (!mysql_query($sql)) {
      die('Error: ' . mysql_error());
      if(APP_LOG_LEVEL >= 1) {
        $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'CRIT', 'syncGameVersion', 'ERROR: COULD NOT CONNECT TO DB')";
        if (!mysql_query($log)) {
          die('Error: ' . mysql_error());
        }
      }
    }
    if(APP_LOG_LEVEL >= 3) {
      $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'INFO', 'syncGameVersion', 'DB/Server version synced.')";
      if (!mysql_query($log)) {
        die('Error: ' . mysql_error());
        if(APP_LOG_LEVEL >= 1) {
          $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'CRIT', 'syncGameVersion', 'ERROR: COULD NOT CONNECT TO DB')";
          if (!mysql_query($log)) {
            die('Error: ' . mysql_error());
          }
        }
      }
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
  global $APP_LOG_LEVEL;

  $serverID = "1";

  //API Call to get game status
  $url = 'http://' . API_HOST . ':' . API_PORT . '/api/getserverinfo?adminuser=' . API_USER . '&admintoken=' . API_PASS . '';

  $queryAPI = file_get_contents($url);
  $jsonObject = json_decode($queryAPI, true);

  //var_dump(json_decode($queryAPI, true));

  if($jsonObject['IP']['value'] >= 0) {

    $sql = "REPLACE into server_info
      (serverID, gameType, gameName, gameHost, serverDescription, serverWebsiteURL, levelName, gameMode, version, ip, countryCode, steamID,
        compatibilityVersion, platform, ServerLoginConfirmationText, port, currentPlayers, maxPlayers, gameDifficulty, dayNightLength,
        zombiesRun, dayCount, ping, dropOnDeath, dropOnQuit, bloodMoonEnemyCount, enemyDifficulty, playerKillingMode, currentServertime,
        dayLightLength, blockDurabilityModifier, airDropFrequency, lootRespawnDays, lootAbundance, maxSpawnedZombies, landClaimSize,
        landClaimDeadZone, landClaimExpiryTime, landClaimDecayMode, LandClaimOnlineDurabilityModifier, LandClaimOfflineDurabilityModifier,
        PartySharedKillRange, maxSpawnedAnimals, ServerVisibility, isDedicated, isPasswordProtected, showFriendPlayerOnMap, buildCreate,
        eacEnabled, architecture64, stockSettings, stockFiles, requiresMod, airDropMarker, enemySpawnMode, isPublic)
      values (
        '".$serverID."',
        '" . $jsonObject['GameType']['value'] . "',
        '" . $jsonObject['GameName']['value'] . "',
        '" . $jsonObject['GameHost']['value'] . "',
        '" . $jsonObject['ServerDescription']['value'] . "',
        '" . $jsonObject['ServerWebsiteURL']['value'] . "',
        '" . $jsonObject['LevelName']['value'] . "',
        '" . $jsonObject['GameMode']['value'] . "',
        '" . $jsonObject['Version']['value'] . "',
        '" . $jsonObject['IP']['value'] . "',
        '" . $jsonObject['CountryCode']['value'] . "',
        '" . $jsonObject['SteamID']['value'] . "',
        '" . $jsonObject['CompatibilityVersion']['value'] . "',
        '" . $jsonObject['Platform']['value'] . "',
        '" . $jsonObject['ServerLoginConfirmationText']['value'] . "',
        '" . $jsonObject['Port']['value'] . "',
        '" . $jsonObject['CurrentPlayers']['value'] . "',
        '" . $jsonObject['MaxPlayers']['value'] . "',
        '" . $jsonObject['GameDifficulty']['value'] . "',
        '" . $jsonObject['DayNightLength']['value'] . "',
        '" . $jsonObject['ZombiesRun']['value'] . "',
        '" . $jsonObject['DayCount']['value'] . "',
        '" . $jsonObject['Ping']['value'] . "',
        '" . $jsonObject['DropOnDeath']['value'] . "',
        '" . $jsonObject['DropOnQuit']['value'] . "',
        '" . $jsonObject['BloodMoonEnemyCount']['value'] . "',
        '" . $jsonObject['EnemyDifficulty']['value'] . "',
        '" . $jsonObject['PlayerKillingMode']['value'] . "',
        '" . $jsonObject['CurrentServerTime']['value'] . "',
        '" . $jsonObject['DayLightLength']['value'] . "',
        '" . $jsonObject['BlockDurabilityModifier']['value'] . "',
        '" . $jsonObject['AirDropFrequency']['value'] . "',
        '" . $jsonObject['LootRespawnDays']['value'] . "',
        '" . $jsonObject['LootAbundance']['value'] . "',
        '" . $jsonObject['MaxSpawnedZombies']['value'] . "',
        '" . $jsonObject['LandClaimSize']['value'] . "',
        '" . $jsonObject['LandClaimDeadZone']['value'] . "',
        '" . $jsonObject['LandClaimExpiryTime']['value'] . "',
        '" . $jsonObject['LandClaimDecayMode']['value'] . "',
        '" . $jsonObject['LandClaimOnlineDurabilityModifier']['value'] . "',
        '" . $jsonObject['LandClaimOfflineDurabilityModifier']['value'] . "',
        '" . $jsonObject['PartySharedKillRange']['value'] . "',
        '" . $jsonObject['MaxSpawnedAnimals']['value'] . "',
        '" . $jsonObject['ServerVisibility']['value'] . "',
        '" . $jsonObject['IsDedicated']['value'] . "',
        '" . $jsonObject['IsPasswordProtected']['value'] . "',
        '" . $jsonObject['ShowFriendPlayerOnMap']['value'] . "',
        '" . $jsonObject['BuildCreate']['value'] . "',
        '" . $jsonObject['EACEnabled']['value'] . "',
        '" . $jsonObject['Architecture64']['value'] . "',
        '" . $jsonObject['StockSettings']['value'] . "',
        '" . $jsonObject['StockFiles']['value'] . "',
        '" . $jsonObject['RequiresMod']['value'] . "',
        '" . $jsonObject['AirDropMarker']['value'] . "',
        '" . $jsonObject['EnemySpawnMode']['value'] . "',
        '" . $jsonObject['IsPublic']['value'] . "'
     )";
    if (!mysql_query($sql)) {
      die('Error: ' . mysql_error());
      if (APP_LOG_LEVEL >= 3) {
        $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'INFO', 'syncServerInfo', 'DB/Server info synced.')";
        if (!mysql_query($log)) {
          die('Error: ' . mysql_error());
          if(APP_LOG_LEVEL >= 1) {
            $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'CRIT', 'syncServerInfo', 'ERROR: COULD NOT CONNECT TO DB')";
            if (!mysql_query($log)) {
              die('Error: ' . mysql_error());
            }
          }
        }
      }
    }
  }
}

function syncOnlinePlayers() {
  global $API_HOST;
  global $API_PORT;
  global $API_USER;
  global $API_PASS;
  global $syncOnlinePlayers;
  global $APP_LOG;
  global $APP_LOG_LEVEL;

  $url = 'http://' . API_HOST . ':' . API_PORT . '/api/getplayersonline?adminuser=' . API_USER . '&admintoken=' . API_PASS . '';

  $queryAPI = file_get_contents($url);
  $jsonObject = json_decode($queryAPI, true);

  $sql = "UPDATE players SET onlineStatus='0'";
  if (!mysql_query($sql)) {
    die('Error: ' . mysql_error());
    if(APP_LOG_LEVEL >= 1) {
      $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'CRIT', 'syncOnlinePlayers', 'ERROR: COULD NOT CONNECT TO DB')";
      if (!mysql_query($log)) {
        die('Error: ' . mysql_error());
      }
    }
  }
  if($jsonObject != NULL) {
    if(APP_LOG_LEVEL >= 4) {
      //var_dump(json_decode($queryAPI, true));

      $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'DEBUG', 'syncOnlinePlayers', 'URLOUT VAR: " . $url . "')";
      if(!mysql_query($log)) {
        die('Error: ' . mysql_error());
        if(APP_LOG_LEVEL >= 1) {
          $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'CRIT', 'syncOnlinePlayers', 'ERROR: COULD NOT CONNECT TO DB')";
          if (!mysql_query($log)) {
            die('Error: ' . mysql_error());
          }
        }
      }
    }
    foreach($jsonObject as $item) {
      //foreach($item as $object) {
        //var_dump($item);
        $columns = implode(", ",array_keys($item));
        //var_dump($columns);
        $escaped_values = array_map('mysql_real_escape_string', array_values($item));
        //var_dump($escaped_values);
        $values  = "'" . implode("', '", $escaped_values) . "'";
        //var_dump($values);
        $sql = "replace into players (steamid, playerid, ip, playerName, onlineStatus, currentPosition, experience, level, health, stamina, zombiesKilled, playersKilled, deaths, score, playtime, lastSeen, ping) values ($values)";
        //var_dump($sql);
        mysql_query($sql);
        if (!mysql_query($sql)) {
          die('Error: ' . mysql_error());
          if(APP_LOG_LEVEL >= 1) {
            $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'CRIT', 'syncOnlinePlayers', 'ERROR: COULD NOT CONNECT TO DB')";
            if (!mysql_query($log)) {
              die('Error: ' . mysql_error());
            }
          }
        }
        $sql2 = "insert into playerHistory (steamid, playerid, ip, playerName, onlineStatus, currentPosition, experience, level, health, stamina, zombiesKilled, playersKilled, deaths, score, playtime, lastSeen, ping) values ($values)";
        //var_dump($sql);
        mysql_query($sql2);
        if (!mysql_query($sql2)) {
          die('Error: ' . mysql_error());
          if(APP_LOG_LEVEL >= 1) {
            $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'CRIT', 'insertPlayerHistory', 'ERROR: COULD NOT CONNECT TO DB')";
            if (!mysql_query($log)) {
              die('Error: ' . mysql_error());
            }
          }
        }
      }
    //}
/*
    foreach($jsonObject as $loop) {
      $sql = "UPDATE players SET onlineStatus='1' WHERE playerid = '" . $jsonObject['0']['entityid'] . "'";
      if (!mysql_query($sql)) {
        die('Error: ' . mysql_error());
        if(APP_LOG_LEVEL >= 1) {
          $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'CRIT', 'syncOnlinePlayers', 'ERROR: COULD NOT CONNECT TO DB')";
          if (!mysql_query($log)) {
            die('Error: ' . mysql_error());
          }
        }
      }
    }*/
    if(APP_LOG_LEVEL >= 3) {
      $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'INFO', 'syncOnlinePlayers', 'Syncing online users')";
      if(!mysql_query($log)) {
        die('Error: ' . mysql_error());
        if(APP_LOG_LEVEL >= 1) {
          $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'CRIT', 'syncOnlinePlayers', 'ERROR: COULD NOT CONNECT TO DB')";
          if (!mysql_query($log)) {
            die('Error: ' . mysql_error());
          }
        }
      }
    }
  } else {
    if (APP_LOG_LEVEL >= 3) {
      $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'INFO', 'syncOnlinePlayers', 'NO PLAYERS -- Recheck in " . interval_syncOnlinePlayers . " seconds...')";
      if(!mysql_query($log)) {
        die('Error: ' . mysql_error());
        if(APP_LOG_LEVEL >= 1) {
          $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'CRIT', 'syncOnlinePlayers', 'ERROR: COULD NOT CONNECT TO DB')";
          if (!mysql_query($log)) {
            die('Error: ' . mysql_error());
          }
        }
      }
    }
  }
}

function insertPlayerHistory() {
  global $API_HOST;
  global $API_PORT;
  global $API_USER;
  global $API_PASS;
  global $insertPlayerHistory;
  global $APP_LOG;
  global $APP_LOG_LEVEL;

  $url = 'http://' . API_HOST . ':' . API_PORT . '/api/getplayersonline?adminuser=' . API_USER . '&admintoken=' . API_PASS . '';

  $queryAPI = file_get_contents($url);
  $jsonObject = json_decode($queryAPI, true);

  //var_dump(json_decode($queryAPI, true));

  if($jsonObject != NULL) {
    if(APP_LOG_LEVEL >= 4) {
      $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'DEBUG', 'insertPlayerHistory', 'URLOUT VAR: " . $url . "')";
      if(!mysql_query($log)) {
        die('Error: ' . mysql_error());
        if(APP_LOG_LEVEL >= 1) {
          $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'CRIT', 'insertPlayerHistory', 'ERROR: COULD NOT CONNECT TO DB')";
          if (!mysql_query($log)) {
            die('Error: ' . mysql_error());
          }
        }
      }
    }
    while($jsonObject = $item) {
      $escaped_values = array_map('mysql_real_escape_string', array_values($item));
      //var_dump($escaped_values);
      $values  = "'" . implode("', '", $escaped_values) . "'";
      //var_dump($values);
      $sql = "insert into playerHistory (steamid, playerid, ip, playerName, onlineStatus, currentPosition, experience, level, health, stamina, zombiesKilled, playersKilled, deaths, score, playtime, lastSeen, ping) values ($values)";
      //var_dump($sql);
      mysql_query($sql);
      if (!mysql_query($sql)) {
        die('Error: ' . mysql_error());
        if(APP_LOG_LEVEL >= 1) {
          $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'CRIT', 'insertPlayerHistory', 'ERROR: COULD NOT CONNECT TO DB')";
          if (!mysql_query($log)) {
            die('Error: ' . mysql_error());
          }
        }
      }
    }
    if(APP_LOG_LEVEL >= 3) {
      $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'INFO', 'insertPlayerHistory', 'Adding online players to playerHistory table.')";
      if(!mysql_query($log)) {
        die('Error: ' . mysql_error());
        if(APP_LOG_LEVEL >= 1) {
          $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'CRIT', 'insertPlayerHistory', 'ERROR: COULD NOT CONNECT TO DB')";
          if (!mysql_query($log)) {
            die('Error: ' . mysql_error());
          }
        }
      }
    }
  } else {
    if (APP_LOG_LEVEL >= 3) {
      $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'INFO', 'insertPlayerHistory', 'NO PLAYERS -- Recheck in " . interval_insertPlayerHistory . " seconds...')";
      if(!mysql_query($log)) {
        die('Error: ' . mysql_error());
        if(APP_LOG_LEVEL >= 1) {
          $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'CRIT', 'insertPlayerHistory', 'ERROR: COULD NOT CONNECT TO DB')";
          if (!mysql_query($log)) {
            die('Error: ' . mysql_error());
          }
        }
      }
    }
  }
}

function syncAllPlayers() {
  global $API_HOST;
  global $API_PORT;
  global $API_USER;
  global $API_PASS;
  global $syncAllPlayers;
  global $APP_LOG;
  global $APP_LOG_LEVEL;

  $url = 'http://' . API_HOST . ':' . API_PORT . '/api/getplayerlist?adminuser=' . API_USER . '&admintoken=' . API_PASS . '';

  $queryAPI = file_get_contents($url);
  $jsonObject = json_decode($queryAPI, true);

  //var_dump($jsonObject);

  if($jsonObject != NULL) {
    if(APP_LOG_LEVEL >= 4) {
      //var_dump(json_decode($queryAPI, true));

      $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'DEBUG', 'syncAllPlayers', 'URLOUT VAR: " . $url . "')";
      if(!mysql_query($log)) {
        die('Error: ' . mysql_error());
        if(APP_LOG_LEVEL >= 1) {
          $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'CRIT', 'syncAllPlayers', 'ERROR: COULD NOT CONNECT TO DB')";
          if (!mysql_query($log)) {
            die('Error: ' . mysql_error());
          }
        }
      }
    }
    foreach($jsonObject as $item) {
      foreach($item as $object) {
        //var_dump($object);
        $columns = implode(", ",array_keys($object));
        //var_dump($columns);
        $escaped_values = array_map('mysql_real_escape_string', array_values($object));
        //var_dump($escaped_values);
        $values  = "'" . implode("', '", $escaped_values) . "'";
        //var_dump($values);
        $sql = "replace into players (steamid, playerid, ip, playerName, onlineStatus, currentPosition, playtime, lastSeen, ping, banned) values ($values)";
        //var_dump($sql);
        mysql_query($sql);
        if (!mysql_query($sql)) {
          die('Error: ' . mysql_error());
          if(APP_LOG_LEVEL >= 1) {
            $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'CRIT', 'syncAllPlayers', 'ERROR: COULD NOT CONNECT TO DB')";
            if (!mysql_query($log)) {
              die('Error: ' . mysql_error());
            }
          }
        }
      }
    }
    if(APP_LOG_LEVEL >= 3) {
      $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'INFO', 'syncAllPlayers', 'Syncing ALL users')";
      if(!mysql_query($log)) {
        die('Error: ' . mysql_error());
        if(APP_LOG_LEVEL >= 1) {
          $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'CRIT', 'syncAllPlayers', 'ERROR: COULD NOT CONNECT TO DB')";
          if (!mysql_query($log)) {
            die('Error: ' . mysql_error());
          }
        }
      }
    }
  } else {
    if (APP_LOG_LEVEL >= 3) {
      $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'INFO', 'syncAllPlayers', 'No players have EVER played in this server -- Recheck in " . interval_syncAllPlayers . " seconds...')";
      if(!mysql_query($log)) {
        die('Error: ' . mysql_error());
        if(APP_LOG_LEVEL >= 1) {
          $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'CRIT', 'syncAllPlayers', 'ERROR: COULD NOT CONNECT TO DB')";
          if (!mysql_query($log)) {
            die('Error: ' . mysql_error());
          }
        }
      }
    }
  }
}


function syncGameLog() {
  global $TELNET_HOST;
  global $TELNET_PORT;
  global $TELNET_PASS;
  global $interval_syncGameLog;
  global $APP_LOG;
  global $APP_LOG_LEVEL;

  $telnet = fsockopen(TELNET_HOST, TELNET_PORT, $errno, $errstr, 10);
  if($telnet) {
    fputs($telnet, TELNET_PASS."\r\n");
  }

  if(APP_LOG_LEVEL >= 2) {
    $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'WARN', 'syncGameLog', '***Telnet connection starting up!***')";
    if (!mysql_query($log)) {
      die('Error: ' . mysql_error());
      if(APP_LOG_LEVEL >= 1) {
        $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'CRIT', 'syncGameLog', 'ERROR: COULD NOT CONNECT TO DB')";
        if(!mysql_query($log)) {
          die('Error: ' . mysql_error());
        }
      }
    }
  }
  while ($line = fgets($telnet)) {
    $line = trim($line);
    $_line = mysql_real_escape_string($line);
    //echo $line."\n";
    if(APP_LOG_LEVEL >= 4) {
      $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'DEBUG', 'syncGameLog', 'TELNET CONNECT STRING: " . $telnet . "')";
      if (!mysql_query($log)) {
        die('Error: ' . mysql_error());
        if(APP_LOG_LEVEL >= 1) {
          $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'CRIT', 'syncGameLog', 'ERROR: COULD NOT CONNECT TO DB')";
          if(!mysql_query($log)) {
            die('Error: ' . mysql_error());
          }
        }
      }
    }
    $gameLog = "insert into server_log (message) values ('$_line')";
    if(!mysql_query($gameLog)) {
      die('Error: ' . mysql_error());
      if(APP_LOG_LEVEL >= 1) {
        $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'CRIT', 'syncGameLog', 'ERROR: COULD NOT CONNECT TO DB')";
        if(!mysql_query($log)) {
          die('Error: ' . mysql_error());
        }
      }
    }
    if(APP_LOG_LEVEL >= 4) {
      $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'DEBUG', 'syncGameLog', 'Game log synced to DB')";
      if (!mysql_query($log)) {
        die('Error: ' . mysql_error());
        if(APP_LOG_LEVEL >= 1) {
          $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'CRIT', 'syncGameLog', 'ERROR: COULD NOT CONNECT TO DB')";
          if(!mysql_query($log)) {
            die('Error: ' . mysql_error());
          }
        }
      }
    }
  }
}

function syncGameChat() {
  global $TELNET_HOST;
  global $TELNET_PORT;
  global $TELNET_PASS;
  global $interval_syncGameChat;
  global $APP_LOG;
  global $APP_LOG_LEVEL;

  $maxret = 4;

  $telnet = fsockopen(TELNET_HOST, TELNET_PORT, $errno, $errstr, 10);
  if($telnet) {
    fputs($telnet, TELNET_PASS."\r\n");
  }

  if(APP_LOG_LEVEL >= 2) {
    $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'WARN', 'syncGameChat', '***Telnet connection starting up!***')";
    if (!mysql_query($log)) {
      die('Error: ' . mysql_error());
      if(APP_LOG_LEVEL >= 1) {
        $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'CRIT', 'syncGameChat', 'ERROR: COULD NOT CONNECT TO DB')";
        if(!mysql_query($log)) {
          die('Error: ' . mysql_error());
        }
      }
    }
  }
  //2018-12-08T21:36:32 63345.176 INF Chat (from '76561198040479184', entity id '4407', to 'Global'): 'Luggistics': /day7
  while ($line = fgets($telnet)) {
    $line = trim($line);
    $_line = mysql_real_escape_string($line);
    $string = str_replace(array( '\'', ':' ), '', $line);
    $string = explode(" ", $string, 13);
    //print_r($string);
    //echo $line."\n";
    if(APP_LOG_LEVEL >= 4) {
      $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'DEBUG', 'syncGameChat', 'TELNET CONNECT STRING: " . $telnet . "')";
      if (!mysql_query($log)) {
        die('Error: ' . mysql_error());
        if(APP_LOG_LEVEL >= 1) {
          $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'CRIT', 'syncGameChat', 'ERROR: COULD NOT CONNECT TO DB')";
          if(!mysql_query($log)) {
            die('Error: ' . mysql_error());
          }
        }
      }
    }
    /////////////////////
    // Parse Game Chat //
    /////////////////////
    if(in_array('Chat', $string)) {
      echo "Found Chat Message";
      print_r($string);
      if($string[11] == 'Server') {
        $gameChat = "insert into chatLog (timestamp, playerName, message, inGame) values (NOW(), '" . $string[11] . "', '" . $string[12] . "', '0')";
      } else {
        $gameChat = "insert into chatLog (timestamp, playerName, message, inGame) values (NOW(), '" . $string[11] . "', '" . $string[12] . "', '1')";
      }
      echo "MySQL Command: " . $gameChat . "\n";
      if(!mysql_query($gameChat)) {
        echo "Error: " . mysql_error();
        sleep(2);
        //die('Error: ' . mysql_error());
        if(APP_LOG_LEVEL >= 1) {
          $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'CRIT', 'syncGameChat', 'ERROR: COULD NOT CONNECT TO DB')";
          if(!mysql_query($log)) {
            die('Error: ' . mysql_error());
          }
        }
      } while($maxret-- > 0);
      /*if(APP_LOG_LEVEL >= 4) {
        $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'DEBUG', 'syncGameChat', 'Game Chat synced to DB')";
        if (!mysql_query($log)) {
          die('Error: ' . mysql_error());
          if(APP_LOG_LEVEL >= 1) {
            $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'CRIT', 'syncGameChat', 'ERROR: COULD NOT CONNECT TO DB')";
            if(!mysql_query($log)) {
              die('Error: ' . mysql_error());
            }
          }
        }
      }*/
      /////////////////////////////
      // Execute Player Commands //
      /////////////////////////////
      $playerEntityID = str_replace( ',', '', $string[8]);
      $playerName =  $string[11];
      $command = $string[12];
      $commandStrip = ltrim($string[12], '/');

      if((substr($string[12], 0, 1) === '/')) {
        echo "User: " . $playerName . " executed a command.\n";
        echo "Command: " . $command . "\n";
        if(APP_LOG_LEVEL >= 2) {
          $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'WARN', 'executePlayerCommand', 'Player - " . $playerName . " - executed command: " . $commandStrip . "')";
          if(!mysql_query($log)) {
            die('Error: ' . mysql_error());
          }
        }
        $getCustomCommand = mysql_query("select * from customCommands where command = '" . $commandStrip . "'");
        if(!$getCustomCommand) {
          die('Error: ' . mysql_error());
        }
        $sqlOut = mysql_fetch_array($getCustomCommand);
        $customCommand = $sqlOut['command'];
        echo "SQL Output: " . $customCommand . "\n\n";
        if($commandStrip != $customCommand) {
          $errorMessage = "**Not a custom command!**";
          echo $errorMessage . "\n";
          if(APP_LOG_LEVEL >= 2) {
            $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'WARN', 'executePlayerCommand', 'Command Output: " . $errorMessage . "')";
            if(!mysql_query($log)) {
              die('Error: ' . mysql_error());
            }
          }
          /*$url = 'http://' . API_HOST . ':' . API_PORT . '/api/executeconsolecommand?adminuser=' . API_USER . '&admintoken=' . API_PASS . '&command=say%20"**Not%20a%20custom%20command!**"';
          $queryAPI = file_get_contents($url);*/
        } else {
          if($commandStrip == 'day7'){
            $getDay7 = mysql_query("select daysLeft from server_gameTime where serverID = '1'");
            if(!$getDay7) {
              die('Error: ' . mysql_error());
            }
            $nextBloodmoon = mysql_fetch_array($getDay7);
            $nextBloodmoon = $nextBloodmoon['daysLeft'];
            if ($nextBloodmoon == 0) {
              $url = 'http://' . API_HOST . ':' . API_PORT . '/api/executeconsolecommand?adminuser=' . API_USER . '&admintoken=' . API_PASS . '&command=say "[cc0000]7DM:[f2f3f4] Next Bloodmoon is tonight!!!"';
              $url = str_replace( ' ', '%20', $url);
              $queryAPI = file_get_contents($url);
              if(APP_LOG_LEVEL >= 2) {
                $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'WARN', 'executePlayerCommand', 'Command Output: Next Bloodmoon is tonight!!!')";
                if(!mysql_query($log)) {
                  die('Error: ' . mysql_error());
                }
              }
            } else {
              $url = 'http://' . API_HOST . ':' . API_PORT . '/api/executeconsolecommand?adminuser=' . API_USER . '&admintoken=' . API_PASS . '&command=say "[cc0000]7DM:[f2f3f4] Next Bloodmoon in ' . $nextBloodmoon . ' days!"';
              $url = str_replace( ' ', '%20', $url);
              $queryAPI = file_get_contents($url);
              if(APP_LOG_LEVEL >= 2) {
                $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'WARN', 'executePlayerCommand', 'Command Output: Next Bloodmoon is in " . $nextBloodmoon . " days!')";
                if(!mysql_query($log)) {
                  die('Error: ' . mysql_error());
                }
              }
            }
          }
          if($commandStrip == 'day7-stats') {
            $url = 'http://' . API_HOST . ':' . API_PORT . '/api/executeconsolecommand?adminuser=' . API_USER . '&admintoken=' . API_PASS . '&command=pm ' . $playerEntityID . ' "[cc0000]7DM:[f2f3f4] Function not availiable yet! Check back later or complain to NuTcAsE on Discord or Steam."';
            $url = str_replace( ' ', '%20', $url);
            $queryAPI = file_get_contents($url);
            if(APP_LOG_LEVEL >= 2) {
              $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'WARN', 'executePlayerCommand', 'Command Output: Function not availiable yet! Check back later or complain to NuTcAsE on Discord or Steam.')";
              if(!mysql_query($log)) {
                die('Error: ' . mysql_error());
              }
            }
          }
          /*if($commandStrip == 'buy'|'shop') {
            echo "Execute store logic here\n";
            $url = 'http://' . API_HOST . ':' . API_PORT . '/api/executeconsolecommand?adminuser=' . API_USER . '&admintoken=' . API_PASS . '&command=pm ' . $playerEntityID . ' "[cc0000]7DM:[f2f3f4]%20Function not availiable yet! Check back later or complain to NuTcAsE on Discord or Steam."';
            $url = str_replace( ' ', '%20', $url);
            $queryAPI = file_get_contents($url);
            if(APP_LOG_LEVEL >= 2) {
              $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'WARN', 'executePlayerCommand', 'Command Output: Function not availiable yet! Check back later or complain to NuTcAsE on Discord or Steam.')";
              if(!mysql_query($log)) {
                die('Error: ' . mysql_error());
              }
            }
          }*/
          if($commandStrip == 'suicide') {
            $url = 'http://' . API_HOST . ':' . API_PORT . '/api/executeconsolecommand?adminuser=' . API_USER . '&admintoken=' . API_PASS . '&command=pm ' . $playerEntityID . ' R.I.P.';
            $url = str_replace( ' ', '%20', $url);
            echo $url . "\n";
            $queryAPI = file_get_contents($url);
            if(APP_LOG_LEVEL >= 2) {
              $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'WARN', 'executePlayerCommand', 'Command Output: Executing kill command for player " . $playerName . ".')";
              if(!mysql_query($log)) {
                die('Error: ' . mysql_error());
              }
            }
            $url = 'http://' . API_HOST . ':' . API_PORT . '/api/executeconsolecommand?adminuser=' . API_USER . '&admintoken=' . API_PASS . '&command=kill ' . $playerEntityID . '';
            $url = str_replace( ' ', '%20', $url);
            echo $url . "\n";
            $queryAPI = file_get_contents($url);
            if(APP_LOG_LEVEL >= 2) {
              $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'WARN', 'executePlayerCommand', 'Command Output: Sent kill command for " . $playerName . "')";
              if(!mysql_query($log)) {
                die('Error: ' . mysql_error());
              }
            }
          }
          if($commandStrip == 'help') {
            $url = 'http://' . API_HOST . ':' . API_PORT . '/api/executeconsolecommand?adminuser=' . API_USER . '&admintoken=' . API_PASS . '&command=pm ' . $playerEntityID . ' "[cc0000]7DM:[f2f3f4] Function not availiable yet! Check back later or complain to NuTcAsE on Discord or Steam."';
            $url = str_replace( ' ', '%20', $url);
            $queryAPI = file_get_contents($url);
            if(APP_LOG_LEVEL >= 2) {
              $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'WARN', 'executePlayerCommand', 'Command Output: Function not availiable yet! Check back later or complain to NuTcAsE on Discord or Steam.')";
              if(!mysql_query($log)) {
                die('Error: ' . mysql_error());
              }
            }
          }
          if($commandStrip == 'zgate') {
            $url = 'http://' . API_HOST . ':' . API_PORT . '/api/executeconsolecommand?adminuser=' . API_USER . '&admintoken=' . API_PASS . '&command=pm ' . $playerEntityID . ' "[cc0000]7DM:[f2f3f4] Function not availiable yet! Check back later or complain to NuTcAsE on Discord or Steam."';
            $url = str_replace( ' ', '%20', $url);
            $queryAPI = file_get_contents($url);
            if(APP_LOG_LEVEL >= 2) {
              $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'WARN', 'executePlayerCommand', 'Command Output: Function not availiable yet! Check back later or complain to NuTcAsE on Discord or Steam.')";
              if(!mysql_query($log)) {
                die('Error: ' . mysql_error());
              }
            }
          }
          if($commandStrip == 'wallet') {
            $url = 'http://' . API_HOST . ':' . API_PORT . '/api/executeconsolecommand?adminuser=' . API_USER . '&admintoken=' . API_PASS . '&command=pm ' . $playerEntityID . ' "[cc0000]7DM:[f2f3f4] Function not availiable yet! Check back later or complain to NuTcAsE on Discord or Steam."';
            $url = str_replace( ' ', '%20', $url);
            $queryAPI = file_get_contents($url);
            if(APP_LOG_LEVEL >= 2) {
              $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'WARN', 'executePlayerCommand', 'Command Output: Function not availiable yet! Check back later or complain to NuTcAsE on Discord or Steam.')";
              if(!mysql_query($log)) {
                die('Error: ' . mysql_error());
              }
            }
          }
        }
      }
    } elseif (in_array('GMSG', $string)) {
      echo "Found General Message \n";
      //print_r($string);
      if ($string[3] == 'GMSG') {
        $generalMessage = "insert into chatLog (timestamp, playerName, message, inGame) values (NOW(), 'Server', '" . $string[4] . " " . $string[5] . " " . $string[6] . " " . $string[7] . " " . $string[8] . "', '0')";
        echo "MySQL Command: " . $generalMessage . "\n";
        if(!mysql_query($generalMessage)) {
          echo "Error: " . mysql_error();
          sleep(2);
          //die('Error: ' . mysql_error());
          if(APP_LOG_LEVEL >= 1) {
            $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'CRIT', 'syncGameChat', 'ERROR: COULD NOT CONNECT TO DB')";
            if(!mysql_query($log)) {
              die('Error: ' . mysql_error());
            }
          }
        } while($maxret-- > 0);
        if(APP_LOG_LEVEL >= 3) {
          $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'INFO', 'GMSG', '" . $string[4] . " " . $string[5] . " " . $string[6] . " " . $string[7] . " " . $string[8] . "')";
          if(!mysql_query($log)) {
            die('Error: ' . mysql_error());
          }
        }
      }
    } else {
      echo "Not a chat message\n\n";
      if(APP_LOG_LEVEL >= 4) {
        $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'DEBUG', 'executePlayerCommand', 'Not a chat message')";
        if(!mysql_query($log)) {
          die('Error: ' . mysql_error());
        }
      }
    }
  }
}


//
//
//NEED TO FIX -- DISREGARD BELOW PLS
//
//
function syncLandclaims() {
  global $API_HOST;
  global $API_PORT;
  global $API_USER;
  global $API_PASS;
  global $syncLandclaims;
  global $APP_LOG;
  global $APP_LOG_LEVEL;

  $url = 'http://' . API_HOST . ':' . API_PORT . '/api/executeconsolecommand?adminuser=' . API_USER . '&admintoken=' . API_PASS . '&command=llp';

  $queryAPI = file_get_contents($url);
  $jsonObject = json_decode($queryAPI, true);

  //var_dump($jsonObject);

  if($jsonObject != NULL) {
    if(APP_LOG_LEVEL >= 4) {
      var_dump(json_decode($queryAPI, true));

      $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'DEBUG', 'syncLandclaims', 'URLOUT VAR: " . $url . "')";
      if(!mysql_query($log)) {
        die('Error: ' . mysql_error());
        if(APP_LOG_LEVEL >= 1) {
          $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'CRIT', 'syncLandclaims', 'ERROR: COULD NOT CONNECT TO DB')";
          if (!mysql_query($log)) {
            die('Error: ' . mysql_error());
          }
        }
      }
    }

    foreach($jsonObject as $item) {
      foreach($item as $object) {
        //var_dump($object);
        $columns = implode(", ",array_keys($object));
        //var_dump($columns);
        $escaped_values = array_map('mysql_real_escape_string', array_values($object));
        //var_dump($escaped_values);
        $values  = "'" . implode("', '", $escaped_values) . "'";
        //var_dump($values);
        //$sql = "replace into players (steam, x, y, z, claimActive) values ($values)";
        var_dump($sql);
        $sql = "replace into server_landclaims SET
          steam = '" . $jsonObject['claimowners']['steamid'] . "',
          claimactive = '" . $jsonObject['claimowners']['claimactive'] . "',
          claims = '" . $jsonObject['claimowners']['claims']['x']['y']['z'] . "'
          WHERE serverID=1";
        mysql_query($sql);
        if (!mysql_query($sql)) {
          die('Error: ' . mysql_error());
          if(APP_LOG_LEVEL >= 1) {
            $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'CRIT', 'syncLandclaims', 'ERROR: COULD NOT CONNECT TO DB')";
            if (!mysql_query($log)) {
              die('Error: ' . mysql_error());
            }
          }
        }
      }
    }
    if(APP_LOG_LEVEL >= 3) {
      $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'INFO', 'syncLandclaims', 'Syncing current landclaims')";
      if(!mysql_query($log)) {
        die('Error: ' . mysql_error());
        if(APP_LOG_LEVEL >= 1) {
          $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'CRIT', 'syncLandclaims', 'ERROR: COULD NOT CONNECT TO DB')";
          if (!mysql_query($log)) {
            die('Error: ' . mysql_error());
          }
        }
      }
    }
  } else {
    if (APP_LOG_LEVEL >= 3) {
      $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'INFO', 'syncLandclaims', 'No landclaims have been placed in-game -- Recheck in " . interval_syncAllPlayers . " seconds...')";
      if(!mysql_query($log)) {
        die('Error: ' . mysql_error());
        if(APP_LOG_LEVEL >= 1) {
          $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'CRIT', 'syncLandclaims', 'ERROR: COULD NOT CONNECT TO DB')";
          if (!mysql_query($log)) {
            die('Error: ' . mysql_error());
          }
        }
      }
    }
  }
}


function syncEntities() {
  global $API_HOST;
  global $API_PORT;
  global $API_USER;
  global $API_PASS;
  global $APP_LOG;
  global $APP_LOG_LEVEL;
  //API Call to get game status
  $url = 'http://' . API_HOST . ':' . API_PORT . '/api/executeconsolecommand?adminuser=' . API_USER . '&admintoken=' . API_PASS . '&command=le';

  $queryAPI = file_get_contents($url);
  $jsonObject = json_decode($queryAPI, true);

  //var_dump(json_decode($queryAPI, true));

  if($jsonObject['IP']['value'] >= 0) {

    $sql = "UPDATE server_entities SET
      steamid = '" . $jsonObject['claimowners']['steamid'] . "',
      claimactive = '" . $jsonObject['claimowners']['claimactive'] . "',
      claims = '" . $jsonObject['claimowners']['claims']['x']['y']['z'] . "'
      WHERE serverID=1";

    if (APP_LOG_LEVEL >= 3) {
      $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'INFO', 'syncEntities', 'DB/Server info synced.')";
      if (!mysql_query($log)) {
        die('Error: ' . mysql_error());
        if(APP_LOG_LEVEL >= 1) {
          $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'CRIT', 'syncEntities', 'ERROR: COULD NOT CONNECT TO DB')";
          if (!mysql_query($log)) {
            die('Error: ' . mysql_error());
          }
        }
      }
    }
  }
}

?>
