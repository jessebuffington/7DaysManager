#!/bin/php
<?php
//
//                  Y.                      _
//                  YiL                   .```.
//                  Yii;                .; .;;`.
//                  YY;ii._           .;`.;;;; :
//                  iiYYYYYYiiiii;;;;i` ;;::;;;;
//              _.;YYYYYYiiiiiiYYYii  .;;.   ;;;
//           .YYYYYYYYYYiiYYYYYYYYYYYYii;`  ;;;;
//         .YYYYYYY$$YYiiYY$$$$iiiYYYYYY;.ii;`..
//        :YYY$!.  TYiiYY$$$$$YYYYYYYiiYYYYiYYii.
//        Y$MM$:   :YYYYYY$!"``"4YYYYYiiiYYYYiiYY.
//     `. :MM$$b.,dYY$$Yii" :'   :YYYYllYiiYYYiYY
//  _.._ :`4MM$!YYYYYYYYYii,.__.diii$$YYYYYYYYYYY
//  .,._ $b`P`     "4$$$$$iiiiiiii$$$$YY$$$$$$YiY;
//     `,.`$:       :$$$$$$$$$YYYYY$$$$$$$$$YYiiYYL
//      "`;$$.    .;PPb$`.,.``T$$YY$$$$YYYYYYiiiYYU:
//      ;$P$;;: ;;;;i$y$"!Y$$$b;$$$Y$YY$$YYYiiiYYiYY
//      $Fi$$ .. ``:iii.`-":YYYYY$$YY$$$$$YYYiiYiYYY
//      :Y$$rb ````  `_..;;i;YYY$YY$$$$$$$YYYYYYYiYY:
//       :$$$$$i;;iiiiidYYYYYYYYYY$$$$$$YYYYYYYiiYYYY.
//        `$$$$$$$YYYYYYYYYYYYY$$$$$$YYYYYYYYiiiYYYYYY
//        .i!$$$$$$YYYYYYYYY$$$$$$YYY$$YYiiiiiiYYYYYYY
//       :YYiii$$$$$$$YYYYYYY$$$$YY$$$$YYiiiiiYYYYYYi'
//
//          XOXO
//  		Jesse B.
//

  include '../includes/appConfig.php';
  include '../includes/functions.php';

  //configure command line arguments
  if($argc > 0){
    foreach($argv as $arg){
      $args = explode('=',$arg);
      switch($args[0]){
        case '--help':
        return displayUsage();
        case '--log':
        $log = $args[1];
        break;
      }
    }
  }

// Get app names from app_status table
  $queryAppStatus = mysql_query('SELECT name FROM app_status where enabled = "1" order by id asc');
  if (!$queryAppStatus) {
    die('Invalid query: ' . mysql_error());
    if(APP_LOG_LEVEL >= 1) {
      $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'CRIT', 'updateAppStatus', 'ERROR: COULD NOT CONNECT TO DB')";
      if (!mysql_query($log)) {
        die('Error: ' . mysql_error());
      }
    }
  }
  while($appStatus = mysql_fetch_array($queryAppStatus)) {
    $appName = $appStatus['name'];
    if($appName != NULL) {
      if(APP_LOG_LEVEL >= 4) {
        $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'DEBUG', 'updateAppStatus', 'SQLOUT VAR: " . $appName . "')";
        if(!mysql_query($log)) {
          die('Error: ' . mysql_error());
          if(APP_LOG_LEVEL >= 1) {
            $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'CRIT', 'updateAppStatus', 'ERROR: COULD NOT CONNECT TO DB')";
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
        $sql = "update app_status set status = 'InActive' where name = '" . $appName . "'";
        if (!mysql_query($sql)) {
          die('Error: ' . mysql_error());
          if(APP_LOG_LEVEL >= 1) {
            $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'CRIT', 'updateAppStatus', 'ERROR: COULD NOT CONNECT TO DB')";
            if (!mysql_query($log)) {
              die('Error: ' . mysql_error());
            }
          }
        }
        if(APP_LOG_LEVEL >= 2) {
          $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'WARN', 'updateAppStatus', '" . $appName . " is <b>NOT</b> running!')";
          if(!mysql_query($log)) {
            die('Error: ' . mysql_error());
            if(APP_LOG_LEVEL >= 1) {
              $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'CRIT', 'updateAppStatus', 'ERROR: COULD NOT CONNECT TO DB')";
              if (!mysql_query($log)) {
                die('Error: ' . mysql_error());
              }
            }
          }
        }
      } else { // Update status of active apps
        $sql = "update app_status set status = 'Active' where name = '" . $appName . "'";
        if (!mysql_query($sql)) {
          die('Error: ' . mysql_error());
          if(APP_LOG_LEVEL >= 1) {
            $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'CRIT', 'updateAppStatus', 'ERROR: COULD NOT CONNECT TO DB')";
            if (!mysql_query($log)) {
              die('Error: ' . mysql_error());
            }
          }
        }
        if(APP_LOG_LEVEL >= 4) {
          print_r($appName);
          print_r($pids);
          $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'DEBUG', 'updateAppStatus', '" . $appName . " is running -- continuing')";
          if(!mysql_query($log)) {
            die('Error: ' . mysql_error());
            if(APP_LOG_LEVEL >= 1) {
              $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'CRIT', 'updateAppStatus', 'ERROR: COULD NOT CONNECT TO DB')";
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
        $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'INFO', 'updateAppStatus', '-- NO APPS IN LIST -- PLEASE CONFIGURE DB --')";
        if(!mysql_query($log)) {
          die('Error: ' . mysql_error());
          if(APP_LOG_LEVEL >= 1) {
            $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'CRIT', 'updateAppStatus', 'ERROR: COULD NOT CONNECT TO DB')";
            if (!mysql_query($log)) {
              die('Error: ' . mysql_error());
            }
          }
        }
      }
    }
  }
?>
