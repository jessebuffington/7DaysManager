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

  $sql = "DELETE FROM app_log WHERE datetime < (NOW() - INTERVAL ". PURGE_APP_LOG_DAYS ." DAY)";
  if (!mysql_query($sql)) {
    die('Error: ' . mysql_error());
    if(APP_LOG_LEVEL >= 1) {
      $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'CRIT', 'purgeAppLog', 'ERROR: COULD NOT CONNECT TO DB')";
      if (!mysql_query($log)) {
        die('Error: ' . mysql_error());
      }
    }
  } else {
    if (APP_LOG_LEVEL >= 2) {
      $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'WARN', 'purgeAppLog', 'App log purged beyond <b>". PURGE_APP_LOG_DAYS ."</b> days')";
      if(!mysql_query($log)) {
        die('Error: ' . mysql_error());
        if(APP_LOG_LEVEL >= 1) {
          $log = "insert into app_log (datetime, logLevel, runName, message) values ('" . date('Y-m-d H:i:s') . "', 'CRIT', 'purgeAppLog', 'ERROR: COULD NOT CONNECT TO DB')";
          if (!mysql_query($log)) {
            die('Error: ' . mysql_error());
          }
        }
      }
    }
  }

?>
