<?php
function __autoload($class) {
  require APP_ROOT . '/lib/' . strtolower($class) . '.php';
}


//Method for displaying the help and default variables.
function displayUsage() {
  global $APP_LOG;

  printf("
    " . Console::light_purple('7DaysManager') . "
    " . Console::cyan(APP_VERSION) . "

    Usage:
      7daysManager.php [options]
      options:
      -h , --help , help          Obviously displays this help message.
      --start , start             Starts enabled app processes. This is the default option when running this command.
      --stop , stop               Stops ALL app processes.
      -r , --restart , restart    Restarts app processes. (Kills all then starts only enabled processes in config).
      -m , --monitor , monitor    Executes the monitoring function.
                                    This works best when set up as a cron job or a windows scheduled task.
      -s , --status , status      Status/Monitor -- Same as -m
      \n\n
      The location of the log file (default: " . APP_LOG . ")
      \n\n"
  );
}


function stopProcesses() {
  global $APP_LOG;
  // Get app names from app_status table
  $queryAppStatus = mysql_query('SELECT name FROM app_status order by id asc');
  if (!$queryAppStatus) {
    die('Invalid query: ' . mysql_error());
    if(APP_LOG_LEVEL >= 1) {
      $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'CRIT', 'stopProcesses', 'ERROR: COULD NOT CONNECT TO DB')";
      if (!mysql_query($log)) {
        die('Error: ' . mysql_error());
      }
    }
  }
  while($appStatus = mysql_fetch_array($queryAppStatus)) {
    $appName = $appStatus['name'];
    if($appName != NULL) {
      if(APP_LOG_LEVEL >= 4) {
        $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'DEBUG', 'stopProcesses', 'SQLOUT VAR: " . $appName . "')";
        if(!mysql_query($log)) {
          die('Error: ' . mysql_error());
          if(APP_LOG_LEVEL >= 1) {
            $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'CRIT', 'stopProcesses', 'ERROR: COULD NOT CONNECT TO DB')";
            if (!mysql_query($log)) {
              die('Error: ' . mysql_error());
            }
          }
        }
      }
      $cmd = 'pgrep -f ' . $appName;
      $pids = null;
      exec($cmd, $pids);
      if(empty($pids)) { // Update status of inactive apps
        echo $appName . " is not running!\n";
        $sql = "update app_status set status = 'InActive' where name = '" . $appName . "'";
        if (!mysql_query($sql)) {
          die('Error: ' . mysql_error());
          if(APP_LOG_LEVEL >= 1) {
            $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'CRIT', 'stopProcesses', 'ERROR: COULD NOT CONNECT TO DB')";
            if (!mysql_query($log)) {
              die('Error: ' . mysql_error());
            }
          }
        }
        if(APP_LOG_LEVEL >= 2) {
          $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'WARN', 'stopProcesses', '" . $appName . " is <b>NOT</b> running!')";
          if(!mysql_query($log)) {
            die('Error: ' . mysql_error());
            if(APP_LOG_LEVEL >= 1) {
              $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'CRIT', 'stopProcesses', 'ERROR: COULD NOT CONNECT TO DB')";
              if (!mysql_query($log)) {
                die('Error: ' . mysql_error());
              }
            }
          }
        }
      } else { // Kill active apps
        echo $appName . " is " . Console::light_green('active') . " -- " . Console::light_red('KILLING NOW!') . "\n";
        $cmd = 'pgrep -f ' . $appName . ' | xargs kill';
        exec($cmd);
        $sql = "update app_status set status = 'InActive' where name = '" . $appName . "'";
        if (!mysql_query($sql)) {
          die('Error: ' . mysql_error());
          if(APP_LOG_LEVEL >= 1) {
            $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'CRIT', 'stopProcesses', 'ERROR: COULD NOT CONNECT TO DB')";
            if (!mysql_query($log)) {
              die('Error: ' . mysql_error());
            }
          }
        }
        if(APP_LOG_LEVEL >= 2) {
          $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'WARN', 'stopProcesses', '<b>Killing " . $appName . "</b>')";
          if(!mysql_query($log)) {
            die('Error: ' . mysql_error());
            if(APP_LOG_LEVEL >= 1) {
              $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'CRIT', 'stopProcesses', 'ERROR: COULD NOT CONNECT TO DB')";
              if (!mysql_query($log)) {
                die('Error: ' . mysql_error());
              }
            }
          }
        }
      }
    } else {
      // Log SQL output if app_status table is empty
      if(APP_LOG_LEVEL >= 3) {
        $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'INFO', 'stopProcesses', '-- NO APPS IN ENABLED LIST -- PLEASE CONFIGURE DB --')";
        if(!mysql_query($log)) {
          die('Error: ' . mysql_error());
          if(APP_LOG_LEVEL >= 1) {
            $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'CRIT', 'stopProcesses', 'ERROR: COULD NOT CONNECT TO DB')";
            if (!mysql_query($log)) {
              die('Error: ' . mysql_error());
            }
          }
        }
      }
    }
  }
}


function startProcesses() {
  global $API_HOST;
  global $API_PORT;
  global $API_USER;
  global $API_PASS;
  global $interval_syncGameVersion;
  global $APP_LOG;
  global $APP_LOG_LEVEL;
  // Get app names from app_status table
  $queryAppStatus = mysql_query('SELECT name FROM app_status where enabled = "1" order by id asc');
  if (!$queryAppStatus) {
    die('Invalid query: ' . mysql_error());
    if(APP_LOG_LEVEL >= 1) {
      $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'CRIT', 'startProcesses', 'ERROR: COULD NOT CONNECT TO DB')";
      if (!mysql_query($log)) {
        die('Error: ' . mysql_error());
      }
    }
  }
  while($appStatus = mysql_fetch_array($queryAppStatus)) {
    $appName = $appStatus['name'];
    if($appName != NULL) {
      if(APP_LOG_LEVEL >= 4) {
        $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'DEBUG', 'startProcesses', 'SQLOUT VAR: " . $appName . "')";
        if(!mysql_query($log)) {
          die('Error: ' . mysql_error());
          if(APP_LOG_LEVEL >= 1) {
            $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'CRIT', 'startProcesses', 'ERROR: COULD NOT CONNECT TO DB')";
            if (!mysql_query($log)) {
              die('Error: ' . mysql_error());
            }
          }
        }
      }
      $cmd = 'pgrep -f ' . $appName;
      $pids = null;
      exec($cmd, $pids);
      if(empty($pids)) { // Update status of inactive apps
        echo "\n" . $appName . " is " . Console::light_red('INACTIVE!') . "\n";
        $sql = "update app_status set status = 'InActive' where name = '" . $appName . "'";
        if (!mysql_query($sql)) {
          die('Error: ' . mysql_error());
          if(APP_LOG_LEVEL >= 1) {
            $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'CRIT', 'startProcesses', 'ERROR: COULD NOT CONNECT TO DB')";
            if (!mysql_query($log)) {
              die('Error: ' . mysql_error());
            }
          }
        }
        if(APP_LOG_LEVEL >= 2) {
          $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'WARN', 'startProcesses', '" . $appName . " is <b>NOT</b> running!')";
          if(!mysql_query($log)) {
            die('Error: ' . mysql_error());
            if(APP_LOG_LEVEL >= 1) {
              $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'CRIT', 'startProcesses', 'ERROR: COULD NOT CONNECT TO DB')";
              if (!mysql_query($log)) {
                die('Error: ' . mysql_error());
              }
            }
          }
        }
        echo Console::light_green('Starting ' . $appName . '...') . "\n\n";
        shell_exec('nohup php -f ' . APP_ROOT . 'lib/' . $appName . '.php >> ' . APP_ROOT . 'var/log/' . $appName . '_' . date('Ymd') . '.log 2>&1 &');
        $sql = "update app_status set status = 'Active' where name = '" . $appName . "'";
        if (!mysql_query($sql)) {
          die('Error: ' . mysql_error());
          if(APP_LOG_LEVEL >= 1) {
            $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'CRIT', 'startProcesses', 'ERROR: COULD NOT CONNECT TO DB')";
            if (!mysql_query($log)) {
              die('Error: ' . mysql_error());
            }
          }
        }
        if(APP_LOG_LEVEL >= 2) {
          $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'WARN', 'startProcesses', '" . $appName . " is <b>STARTING UP!</b>')";
          if(!mysql_query($log)) {
            die('Error: ' . mysql_error());
            if(APP_LOG_LEVEL >= 1) {
              $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'CRIT', 'startProcesses', 'ERROR: COULD NOT CONNECT TO DB')";
              if (!mysql_query($log)) {
                die('Error: ' . mysql_error());
              }
            }
          }
        }
      } else { // Start inactive apps
        echo $appName . " is " . Console::light_green('ACTIVE') . " -- Skipping\n";
        $sql = "update app_status set status = 'Active' where name = '" . $appName . "'";
        if (!mysql_query($sql)) {
          die('Error: ' . mysql_error());
          if(APP_LOG_LEVEL >= 1) {
            $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'CRIT', 'startProcesses', 'ERROR: COULD NOT CONNECT TO DB')";
            if (!mysql_query($log)) {
              die('Error: ' . mysql_error());
            }
          }
        }
        if(APP_LOG_LEVEL >= 2) {
          $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'WARN', 'startProcesses', '" . $appName . " is <b>ACTIVE</b> -- SKIPPING')";
          if(!mysql_query($log)) {
            die('Error: ' . mysql_error());
            if(APP_LOG_LEVEL >= 1) {
              $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'CRIT', 'startProcesses', 'ERROR: COULD NOT CONNECT TO DB')";
              if (!mysql_query($log)) {
                die('Error: ' . mysql_error());
              }
            }
          }
        }
      }
    } else {
      // Log SQL output if app_status table is empty
      if(APP_LOG_LEVEL >= 3) {
        $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'INFO', 'startProcesses', '-- NO APPS IN LIST -- PLEASE CONFIGURE DB --')";
        if(!mysql_query($log)) {
          die('Error: ' . mysql_error());
          if(APP_LOG_LEVEL >= 1) {
            $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'CRIT', 'startProcesses', 'ERROR: COULD NOT CONNECT TO DB')";
            if (!mysql_query($log)) {
              die('Error: ' . mysql_error());
            }
          }
        }
      }
    }
  }
}


function restartProcesses() {
  global $API_HOST;
  global $API_PORT;
  global $API_USER;
  global $API_PASS;
  global $interval_syncGameVersion;
  global $APP_LOG;
  global $APP_LOG_LEVEL;
  // Get app names from app_status table
  $queryAppStatus = mysql_query('SELECT name FROM app_status order by id asc');
  if (!$queryAppStatus) {
    die('Invalid query: ' . mysql_error());
    if(APP_LOG_LEVEL >= 1) {
      $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'CRIT', 'restartProcesses', 'ERROR: COULD NOT CONNECT TO DB')";
      if (!mysql_query($log)) {
        die('Error: ' . mysql_error());
      }
    }
  }
  while($appStatus = mysql_fetch_array($queryAppStatus)) {
    $appName = $appStatus['name'];
    if($appName != NULL) {
      if(APP_LOG_LEVEL >= 4) {
        $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'DEBUG', 'restartProcesses', 'SQLOUT VAR: " . $appName . "')";
        if(!mysql_query($log)) {
          die('Error: ' . mysql_error());
          if(APP_LOG_LEVEL >= 1) {
            $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'CRIT', 'restartProcesses', 'ERROR: COULD NOT CONNECT TO DB')";
            if (!mysql_query($log)) {
              die('Error: ' . mysql_error());
            }
          }
        }
      }
      $cmd = 'pgrep -f ' . $appName;
      $pids = null;
      exec($cmd, $pids);
      if(empty($pids)) { // Update status of inactive apps
        echo $appName . " is not running!\n";
        $sql = "update app_status set status = 'InActive' where name = '" . $appName . "'";
        if (!mysql_query($sql)) {
          die('Error: ' . mysql_error());
          if(APP_LOG_LEVEL >= 1) {
            $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'CRIT', 'restartProcesses', 'ERROR: COULD NOT CONNECT TO DB')";
            if (!mysql_query($log)) {
              die('Error: ' . mysql_error());
            }
          }
        }
        if(APP_LOG_LEVEL >= 2) {
          $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'WARN', 'restartProcesses', '" . $appName . " is <b>NOT</b> running!')";
          if(!mysql_query($log)) {
            die('Error: ' . mysql_error());
            if(APP_LOG_LEVEL >= 1) {
              $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'CRIT', 'restartProcesses', 'ERROR: COULD NOT CONNECT TO DB')";
              if (!mysql_query($log)) {
                die('Error: ' . mysql_error());
              }
            }
          }
        }
      } else { // Kill active apps
        echo $appName . " is " . Console::light_green('active') . " -- " . Console::light_red('KILLING NOW!') . "\n";
        $cmd = 'pgrep -f ' . $appName . ' | xargs kill';
        exec($cmd);
        $sql = "update app_status set status = 'InActive' where name = '" . $appName . "'";
        if (!mysql_query($sql)) {
          die('Error: ' . mysql_error());
          if(APP_LOG_LEVEL >= 1) {
            $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'CRIT', 'restartProcesses', 'ERROR: COULD NOT CONNECT TO DB')";
            if (!mysql_query($log)) {
              die('Error: ' . mysql_error());
            }
          }
        }
        if(APP_LOG_LEVEL >= 2) {
          $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'WARN', 'restartProcesses', '<b>Killing " . $appName . "</b>')";
          if(!mysql_query($log)) {
            die('Error: ' . mysql_error());
            if(APP_LOG_LEVEL >= 1) {
              $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'CRIT', 'restartProcesses', 'ERROR: COULD NOT CONNECT TO DB')";
              if (!mysql_query($log)) {
                die('Error: ' . mysql_error());
              }
            }
          }
        }
      }
    } else {
      // Log SQL output if app_status table is empty
      if(APP_LOG_LEVEL >= 3) {
        $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'INFO', 'restartProcesses', '-- NO APPS IN ENABLED LIST -- PLEASE CONFIGURE DB --')";
        if(!mysql_query($log)) {
          die('Error: ' . mysql_error());
          if(APP_LOG_LEVEL >= 1) {
            $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'CRIT', 'restartProcesses', 'ERROR: COULD NOT CONNECT TO DB')";
            if (!mysql_query($log)) {
              die('Error: ' . mysql_error());
            }
          }
        }
      }
    }
  }
  //
  // START APP PROCESSES
  //
  $queryAppStatus = mysql_query('SELECT name FROM app_status where enabled = "1" order by id asc');
  if (!$queryAppStatus) {
    die('Invalid query: ' . mysql_error());
    if(APP_LOG_LEVEL >= 1) {
      $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'CRIT', 'restartProcesses', 'ERROR: COULD NOT CONNECT TO DB')";
      if (!mysql_query($log)) {
        die('Error: ' . mysql_error());
      }
    }
  }
  while($appStatus = mysql_fetch_array($queryAppStatus)) {
    $appName = $appStatus['name'];
    if($appName != NULL) {
      if(APP_LOG_LEVEL >= 4) {
        $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'DEBUG', 'restartProcesses', 'SQLOUT VAR: " . $appName . "')";
        if(!mysql_query($log)) {
          die('Error: ' . mysql_error());
          if(APP_LOG_LEVEL >= 1) {
            $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'CRIT', 'restartProcesses', 'ERROR: COULD NOT CONNECT TO DB')";
            if (!mysql_query($log)) {
              die('Error: ' . mysql_error());
            }
          }
        }
      }
      $cmd = 'pgrep -f ' . $appName;
      $pids = null;
      exec($cmd, $pids);
      if(empty($pids)) { // Update status of inactive apps
        echo "\n" . $appName . " is " . Console::light_red('INACTIVE!') . "\n";
        $sql = "update app_status set status = 'InActive' where name = '" . $appName . "'";
        if (!mysql_query($sql)) {
          die('Error: ' . mysql_error());
          if(APP_LOG_LEVEL >= 1) {
            $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'CRIT', 'restartProcesses', 'ERROR: COULD NOT CONNECT TO DB')";
            if (!mysql_query($log)) {
              die('Error: ' . mysql_error());
            }
          }
        }
        if(APP_LOG_LEVEL >= 2) {
          $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'WARN', 'restartProcesses', '" . $appName . " is <b>NOT</b> running!')";
          if(!mysql_query($log)) {
            die('Error: ' . mysql_error());
            if(APP_LOG_LEVEL >= 1) {
              $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'CRIT', 'restartProcesses', 'ERROR: COULD NOT CONNECT TO DB')";
              if (!mysql_query($log)) {
                die('Error: ' . mysql_error());
              }
            }
          }
        }
        echo Console::light_green('Starting ' . $appName . '...') . "\n\n";
        shell_exec('nohup php -f ' . APP_ROOT . 'lib/' . $appName . '.php >> ' . APP_ROOT . 'var/log/' . $appName . '_' . date('Ymd') . '.log 2>&1 &');
        $sql = "update app_status set status = 'Active' where name = '" . $appName . "'";
        if (!mysql_query($sql)) {
          die('Error: ' . mysql_error());
          if(APP_LOG_LEVEL >= 1) {
            $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'CRIT', 'restartProcesses', 'ERROR: COULD NOT CONNECT TO DB')";
            if (!mysql_query($log)) {
              die('Error: ' . mysql_error());
            }
          }
        }
        if(APP_LOG_LEVEL >= 2) {
          $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'WARN', 'restartProcesses', '" . $appName . " is <b>STARTING UP!</b>')";
          if(!mysql_query($log)) {
            die('Error: ' . mysql_error());
            if(APP_LOG_LEVEL >= 1) {
              $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'CRIT', 'restartProcesses', 'ERROR: COULD NOT CONNECT TO DB')";
              if (!mysql_query($log)) {
                die('Error: ' . mysql_error());
              }
            }
          }
        }
      } else { // Start inactive apps
        echo $appName . " is " . Console::light_green('ACTIVE') . " -- Skipping\n";
        $sql = "update app_status set status = 'Active' where name = '" . $appName . "'";
        if (!mysql_query($sql)) {
          die('Error: ' . mysql_error());
          if(APP_LOG_LEVEL >= 1) {
            $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'CRIT', 'restartProcesses', 'ERROR: COULD NOT CONNECT TO DB')";
            if (!mysql_query($log)) {
              die('Error: ' . mysql_error());
            }
          }
        }
        if(APP_LOG_LEVEL >= 2) {
          $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'WARN', 'restartProcesses', '" . $appName . " is <b>ACTIVE</b> -- SKIPPING')";
          if(!mysql_query($log)) {
            die('Error: ' . mysql_error());
            if(APP_LOG_LEVEL >= 1) {
              $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'CRIT', 'restartProcesses', 'ERROR: COULD NOT CONNECT TO DB')";
              if (!mysql_query($log)) {
                die('Error: ' . mysql_error());
              }
            }
          }
        }
      }
    } else {
      // Log SQL output if app_status table is empty
      if(APP_LOG_LEVEL >= 3) {
        $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'INFO', 'restartProcesses', '-- NO APPS IN LIST -- PLEASE CONFIGURE DB --')";
        if(!mysql_query($log)) {
          die('Error: ' . mysql_error());
          if(APP_LOG_LEVEL >= 1) {
            $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'CRIT', 'restartProcesses', 'ERROR: COULD NOT CONNECT TO DB')";
            if (!mysql_query($log)) {
              die('Error: ' . mysql_error());
            }
          }
        }
      }
    }
  }
}


function monitorAppStatus() {
  // Get app names from app_status table
  $queryAppStatus = mysql_query('SELECT name FROM app_status where enabled = "1" order by id asc');
  if (!$queryAppStatus) {
    die('Invalid query: ' . mysql_error());
    if(APP_LOG_LEVEL >= 1) {
      $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'CRIT', 'monitorAppStatus', 'ERROR: COULD NOT CONNECT TO DB')";
      if (!mysql_query($log)) {
        die('Error: ' . mysql_error());
      }
    }
  }
  while($appStatus = mysql_fetch_array($queryAppStatus)) {
    $appName = $appStatus['name'];
    if($appName != NULL) {
      if(APP_LOG_LEVEL >= 4) {
        $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'DEBUG', 'monitorAppStatus', 'SQLOUT VAR: " . $appName . "')";
        if(!mysql_query($log)) {
          die('Error: ' . mysql_error());
          if(APP_LOG_LEVEL >= 1) {
            $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'CRIT', 'monitorAppStatus', 'ERROR: COULD NOT CONNECT TO DB')";
            if (!mysql_query($log)) {
              die('Error: ' . mysql_error());
            }
          }
        }
      }
      $cmd = 'pgrep -f ' . $appName;
      $pids = null;
      exec($cmd, $pids);
      if(empty($pids)) { // Update status of inactive apps
        echo "\n" . $appName . " is " . Console::light_red('INACTIVE!') . "\n";
        $sql = "update app_status set status = 'InActive' where name = '" . $appName . "'";
        if (!mysql_query($sql)) {
          die('Error: ' . mysql_error());
          if(APP_LOG_LEVEL >= 1) {
            $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'CRIT', 'monitorAppStatus', 'ERROR: COULD NOT CONNECT TO DB')";
            if (!mysql_query($log)) {
              die('Error: ' . mysql_error());
            }
          }
        }
        if(APP_LOG_LEVEL >= 2) {
          $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'WARN', 'monitorAppStatus', '" . $appName . " is <b>NOT</b> running!')";
          if(!mysql_query($log)) {
            die('Error: ' . mysql_error());
            if(APP_LOG_LEVEL >= 1) {
              $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'CRIT', 'monitorAppStatus', 'ERROR: COULD NOT CONNECT TO DB')";
              if (!mysql_query($log)) {
                die('Error: ' . mysql_error());
              }
            }
          }
        }
        $queryMonRestart = mysql_query('SELECT monRestart FROM app_status where name = "' . $appName . '" and enabled = "1"');
        if (!$queryMonRestart) {
          die('Invalid query: ' . mysql_error());
          if(APP_LOG_LEVEL >= 1) {
            $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'CRIT', 'monitorAppStatus', 'ERROR: COULD NOT CONNECT TO DB')";
            if (!mysql_query($log)) {
              die('Error: ' . mysql_error());
            }
          }
        }
        $monRestart = mysql_fetch_array($queryMonRestart);
        if ($monRestart['monRestart'] == 0) {
          echo Console::light_red('**Monitor detected that ' . $appName . ' is NOT configured to restart upon failure. Please review. -- SKIPPING') . "\n\n";
          if(APP_LOG_LEVEL >= 2) {
            $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'WARN', 'monitorAppStatus', '<b>**Monitor detected that " . $appName . " is NOT configured to restart upon failure. Please review. -- SKIPPING</b>')";
            if(!mysql_query($log)) {
              die('Error: ' . mysql_error());
              if(APP_LOG_LEVEL >= 1) {
                $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'CRIT', 'monitorAppStatus', 'ERROR: COULD NOT CONNECT TO DB')";
                if (!mysql_query($log)) {
                  die('Error: ' . mysql_error());
                }
              }
            }
          }
        } else {
          echo Console::light_green('**Monitor detected that process needs to be restarted upon failure -- Starting ' . $appName . '...') . "\n\n";
          shell_exec('nohup php -f ' . APP_ROOT . 'lib/' . $appName . '.php >> ' . APP_ROOT . 'var/log/' . $appName . '_' . date('Ymd') . '.log 2>&1 &');
          $sql = "update app_status set status = 'Active' where name = '" . $appName . "'";
          if (!mysql_query($sql)) {
            die('Error: ' . mysql_error());
            if(APP_LOG_LEVEL >= 1) {
              $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'CRIT', 'monitorAppStatus', 'ERROR: COULD NOT CONNECT TO DB')";
              if (!mysql_query($log)) {
                die('Error: ' . mysql_error());
              }
            }
          }
          if(APP_LOG_LEVEL >= 2) {
            $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'WARN', 'monitorAppStatus', '**Monitor detected that process needs to be restarted upon failure -- " . $appName . " is <b>STARTING UP!</b>')";
            if(!mysql_query($log)) {
              die('Error: ' . mysql_error());
              if(APP_LOG_LEVEL >= 1) {
                $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'CRIT', 'monitorAppStatus', 'ERROR: COULD NOT CONNECT TO DB')";
                if (!mysql_query($log)) {
                  die('Error: ' . mysql_error());
                }
              }
            }
          }
        }
      } else { // Update status of active apps
        echo $appName . " is " . Console::light_green('ACTIVE!') . "\n";
        $sql = "update app_status set status = 'Active' where name = '" . $appName . "'";
        if (!mysql_query($sql)) {
          die('Error: ' . mysql_error());
          if(APP_LOG_LEVEL >= 1) {
            $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'CRIT', 'monitorAppStatus', 'ERROR: COULD NOT CONNECT TO DB')";
            if (!mysql_query($log)) {
              die('Error: ' . mysql_error());
            }
          }
        }
        if(APP_LOG_LEVEL >= 4) {
          print_r($appName);
          print_r($pids);
          $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'DEBUG', 'monitorAppStatus', '" . $appName . " is running -- continuing')";
          if(!mysql_query($log)) {
            die('Error: ' . mysql_error());
            if(APP_LOG_LEVEL >= 1) {
              $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'CRIT', 'monitorAppStatus', 'ERROR: COULD NOT CONNECT TO DB')";
              if (!mysql_query($log)) {
                die('Error: ' . mysql_error());
              }
            }
          }
        }
      }
    } else {
      // Log SQL output if app_status table is empty
      if(APP_LOG_LEVEL >= 3) {
        echo "\n\n" . Console::yellow('-- NO APPS IN ENABLED LIST -- PLEASE CONFIGURE DB --') . "\n\n";
        $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'INFO', 'monitorAppStatus', '-- NO APPS IN ENABLED LIST -- PLEASE CONFIGURE DB --')";
        if(!mysql_query($log)) {
          die('Error: ' . mysql_error());
          if(APP_LOG_LEVEL >= 1) {
            $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'CRIT', 'monitorAppStatus', 'ERROR: COULD NOT CONNECT TO DB')";
            if (!mysql_query($log)) {
              die('Error: ' . mysql_error());
            }
          }
        }
      }
    }
  }
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

  $currDay = sprintf("%02d", $jsonObject['gametime']['days']);
  $currTime =  sprintf("%02d", $jsonObject['gametime']['hours']) . ":" . sprintf("%02d", $jsonObject['gametime']['minutes']);

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
        //$sql = "replace into players (steamid, playerid, ip, playerName, onlineStatus, currentPosition, playtime, lastSeen, ping, banned) values ($values)";
        $sql = "INSERT INTO players (steamid, playerid, ip, playerName, onlineStatus, currentPosition, playtime, lastSeen, ping, banned) VALUES ($values) ON DUPLICATE KEY UPDATE steamid = VALUES(steamid), ping = VALUES(ping), lastSeen = VALUES(lastSeen), playtime = VALUES(playtime), onlineStatus = VALUES(onlineStatus), banned = VALUES(banned)";
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
  // This is not really needed and will probably be removed in a future commit -- the game server already logs to a file (this logs to the DB) and the syncGameChat function watches the telnet socket for user input.
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
      echo "Found Chat Message \n";
      //print_r($string);
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
        $commandParams = $sqlOut['serverExecution'];
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
        } else {
          if($commandStrip == 'day7'){
            $getDay7 = mysql_query("select daysLeft from server_gameTime where serverID = '1'");
            if(!$getDay7) {
              die('Error: ' . mysql_error());
            }
            $nextBloodmoon = mysql_fetch_array($getDay7);
            $nextBloodmoon = $nextBloodmoon['daysLeft'];
            if ($nextBloodmoon == 0) {
              $url = 'http://' . API_HOST . ':' . API_PORT . '/api/executeconsolecommand?adminuser=' . API_USER . '&admintoken=' . API_PASS . '&command=say "[' . APP_NAME_COLOR . '][' . APP_SHORTNAME . '][' . APP_CHAT_COLOR . '] Next Bloodmoon is tonight!!!"';
              $url = str_replace( ' ', '%20', $url);
              $queryAPI = file_get_contents($url);
              if(APP_LOG_LEVEL >= 2) {
                $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'WARN', 'executePlayerCommand', 'Command Output: Next Bloodmoon is tonight!!!')";
                if(!mysql_query($log)) {
                  die('Error: ' . mysql_error());
                }
              }
            } else {
              $url = 'http://' . API_HOST . ':' . API_PORT . '/api/executeconsolecommand?adminuser=' . API_USER . '&admintoken=' . API_PASS . '&command=say "[' . APP_NAME_COLOR . '][' . APP_SHORTNAME . '][' . APP_CHAT_COLOR . '] Next Bloodmoon in ' . $nextBloodmoon . ' days!"';
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
            $url = 'http://' . API_HOST . ':' . API_PORT . '/api/executeconsolecommand?adminuser=' . API_USER . '&admintoken=' . API_PASS . '&command=pm ' . $playerEntityID . ' "[' . APP_NAME_COLOR . '][' . APP_SHORTNAME . '] [' . APP_CHAT_COLOR . '] Function not availiable yet! Check back later or complain to NuTcAsE on Discord or Steam."';
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
            $url = 'http://' . API_HOST . ':' . API_PORT . '/api/executeconsolecommand?adminuser=' . API_USER . '&admintoken=' . API_PASS . '&command=pm ' . $playerEntityID . ' "[' . APP_NAME_COLOR . '][' . APP_SHORTNAME . '] [' . APP_CHAT_COLOR . '] Function not availiable yet! Check back later or complain to NuTcAsE on Discord or Steam."';
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
            $url = 'http://' . API_HOST . ':' . API_PORT . '/api/executeconsolecommand?adminuser=' . API_USER . '&admintoken=' . API_PASS . '&command=pm ' . $playerEntityID . ' "[' . APP_NAME_COLOR . '][' . APP_SHORTNAME . '][' . APP_CHAT_COLOR . '] Function not availiable yet! Check back later or complain to NuTcAsE on Discord or Steam."';
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
            $url = 'http://' . API_HOST . ':' . API_PORT . '/api/executeconsolecommand?adminuser=' . API_USER . '&admintoken=' . API_PASS . '&command=pm ' . $playerEntityID . ' "[' . APP_NAME_COLOR . '][' . APP_SHORTNAME . '][' . APP_CHAT_COLOR . '] Function not availiable yet! Check back later or complain to NuTcAsE on Discord or Steam."';
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
            $url = 'http://' . API_HOST . ':' . API_PORT . '/api/executeconsolecommand?adminuser=' . API_USER . '&admintoken=' . API_PASS . '&command=pm ' . $playerEntityID . ' "[' . APP_NAME_COLOR . '][' . APP_SHORTNAME . '][' . APP_CHAT_COLOR . '] Function not availiable yet! Check back later or complain to NuTcAsE on Discord or Steam."';
            $url = str_replace( ' ', '%20', $url);
            $queryAPI = file_get_contents($url);
            if(APP_LOG_LEVEL >= 2) {
              $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'WARN', 'executePlayerCommand', 'Command Output: Function not availiable yet! Check back later or complain to NuTcAsE on Discord or Steam.')";
              if(!mysql_query($log)) {
                die('Error: ' . mysql_error());
              }
            }
          }
          if($commandStrip == 'discord') {
            if (DISCORD_ENABLED == '0') {
              $url = 'http://' . API_HOST . ':' . API_PORT . '/api/executeconsolecommand?adminuser=' . API_USER . '&admintoken=' . API_PASS . '&command=pm ' . $playerEntityID . ' "[' . APP_NAME_COLOR . '][' . APP_SHORTNAME . '][' . APP_CHAT_COLOR . '] Function not enabled."';
              $url = str_replace( ' ', '%20', $url);
              $queryAPI = file_get_contents($url);
              if(APP_LOG_LEVEL >= 2) {
                $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'WARN', 'executePlayerCommand', 'Command Output: Function not enabled (DISCORD_ENABLED = " . DISCORD_ENABLED . ". Please see the DISCORD_ENABLED setting to enable.')";
                if(!mysql_query($log)) {
                  die('Error: ' . mysql_error());
                }
              }
            } else {
              $url = 'http://' . API_HOST . ':' . API_PORT . '/api/executeconsolecommand?adminuser=' . API_USER . '&admintoken=' . API_PASS . '&command=pm ' . $playerEntityID . ' "[' . APP_NAME_COLOR . '][' . APP_SHORTNAME . '][' . APP_CHAT_COLOR . '] Come join us on our Discord server! ' . DISCORD_LINK . '"';
              $url = str_replace( ' ', '%20', $url);
              $queryAPI = file_get_contents($url);
              if(APP_LOG_LEVEL >= 2) {
                $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'WARN', 'executePlayerCommand', 'Command Output: Come join us on our Discord server! " . DISCORD_LINK . "')";
                if(!mysql_query($log)) {
                  die('Error: ' . mysql_error());
                }
              }
            }
          }
          if($commandStrip == 'admin') {
            $url = 'http://' . API_HOST . ':' . API_PORT . '/api/executeconsolecommand?adminuser=' . API_USER . '&admintoken=' . API_PASS . '&command=pm ' . $playerEntityID . ' "[' . APP_NAME_COLOR . '][' . APP_SHORTNAME . '][' . APP_CHAT_COLOR . '] Sure thing! We will notify an admin immediately! If you don\'t hear anything shortly, please reach out on the Discord server -- ' . DISCORD_LINK . '"';
            $url = str_replace( ' ', '%20', $url);
            echo $url . "\n";
            $queryAPI = file_get_contents($url);
            if(APP_LOG_LEVEL >= 2) {
              $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'WARN', 'executePlayerCommand', '<b>" . $playerName . "</b> is trying to notify an admin!!!')";
              if(!mysql_query($log)) {
                die('Error: ' . mysql_error());
              }
            }
            if (NOTIFICATION_MASTER_SWITCH == '1'){
              if (DISCORD_ENABLED == '1'){
                $APP_NAME = APP_NAME;

                $curl = curl_init();

                curl_setopt_array($curl, array(
                  CURLOPT_URL => DISCORD_WEBHOOK,
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => "",
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 30,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => "POST",
                  CURLOPT_POSTFIELDS => "{\n
                    \"username\": \"" . APP_NAME .  "\",\n
                    \"avatar_url\":\"https://raw.githubusercontent.com/bassmastry101/7DaysManager/master/icon.png\",\n
                    \"embeds\": [\n
                      {\n
                        \"color\": \"2067276\",\n
                        \"fields\": [\n
                          {\n
                            \"name\": \"**Admin assistance needed!!**\",\n
                            \"value\": \"User " . $playerName . " has requested an admin NAOW!\"\n
                            }\n
                          ]\n
                        }\n
                      ]\n
                    }",
                  CURLOPT_HTTPHEADER => array(
                    "Content-Type: application/json",
                    "cache-control: no-cache"
                  ),
                ));

                $response = curl_exec($curl);
                $err = curl_error($curl);

                curl_close($curl);

                if ($err) {
                  echo "cURL Error #:" . $err . "\n\n";
                } else {
                  echo $response . "\n\n";
                }

                if(APP_LOG_LEVEL >= 2) {
                  $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'WARN', 'executePlayerCommand', 'Sent notify command via Discord')";
                  if(!mysql_query($log)) {
                    die('Error: ' . mysql_error());
                  }
                }
              }
              if (PUSHBULLET_ENABLED == '1') {
                $PUSHBULLET_TOKEN = PUSHBULLET_TOKEN;

                $curl = curl_init();

                curl_setopt_array($curl, array(
                  CURLOPT_URL => "https://api.pushbullet.com/v2/pushes",
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => "",
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 30,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => "POST",
                  CURLOPT_POSTFIELDS => "{\r\n
                    \"channel_tag\": \"" . PUSHBULLET_CHANNELTAG . "\",\r\n
                    \"type\": \"note\",\r\n
                    \"title\": \"**Admin Assistance is needed!!**\",\r\n
                    \"body\": \"User " . $playerName . " has requested an admin NAOW!\"\r\n}",
                  CURLOPT_HTTPHEADER => array(
                    "Access-Token: " . PUSHBULLET_TOKEN,
                    "Content-Type: application/json",
                    "cache-control: no-cache"
                  ),
                ));

                $response = curl_exec($curl);
                $err = curl_error($curl);

                curl_close($curl);

                if ($err) {
                  echo "cURL Error #:" . $err . "\n\n";
                } else {
                  echo $response . "\n\n";
                }

                if(APP_LOG_LEVEL >= 2) {
                  $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'WARN', 'executePlayerCommand', 'Sent notify command via Pushbullet')";
                  if(!mysql_query($log)) {
                    die('Error: ' . mysql_error());
                  }
                }
              }
              /*if (EMAIL_ENABLED == '1') {
                //
                //PUT EMAIL COMMAND HERE
                //

                if(APP_LOG_LEVEL >= 2) {
                  $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'WARN', 'executePlayerCommand', 'Sent notify command via Email')";
                  if(!mysql_query($log)) {
                    die('Error: ' . mysql_error());
                  }
                }
              }*/
            } elseif (NOTIFICATION_MASTER_SWITCH == '0') {
              if(APP_LOG_LEVEL >= 2) {
                $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'WARN', 'executePlayerCommand', '<b>**Could not notify an admin -- Notifications are not enabled.**</b>')";
                if(!mysql_query($log)) {
                  die('Error: ' . mysql_error());
                }
              }
            }
          }
        //////////////////////////
        // Continue Chat Parser //
        //////////////////////////
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
