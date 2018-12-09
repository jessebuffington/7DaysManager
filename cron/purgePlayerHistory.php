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

  $getPlayerids = mysql_query("select distinct playerid from players where not playerid = '0'");
  if (!$getPlayerids) {
    die('Error: ' . mysql_error());
    if(APP_LOG_LEVEL >= 1) {
      $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'CRIT', 'insertPlayerHistory', 'ERROR: COULD NOT CONNECT TO DB')";
      if (!mysql_query($log)) {
        die('Error: ' . mysql_error());
      }
    }
  }
  while($playerid = mysql_fetch_array($getPlayerids)) {
    echo "Purging " . $playerid['playerid'] . "\n";
    $deleteSql = "DELETE FROM playerHistory WHERE id NOT IN (SELECT id FROM (SELECT id FROM playerHistory where playerid = '" . $playerid['playerid'] . "' ORDER BY id DESC LIMIT " . PURGE_PLAYER_HISTORY_ROWS . ")foo)";
    var_dump($deleteSql);
    mysql_query($deleteSql);
    if (!mysql_query($deleteSql)) {
      die('Error: ' . mysql_error());
      if(APP_LOG_LEVEL >= 1) {
        $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'CRIT', 'insertPlayerHistory', 'ERROR: COULD NOT CONNECT TO DB')";
        if (!mysql_query($log)) {
          die('Error: ' . mysql_error());
        }
      }
    }
    if(APP_LOG_LEVEL >= 3) {
      $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'INFO', 'insertPlayerHistory', 'Purged " . PURGE_PLAYER_HISTORY_ROWS . " rows from playerHistory table | USER: " . $playerid['playerid'] . "')";
      if (!mysql_query($log)) {
        die('Error: ' . mysql_error());
      }
    }
  }
?>
