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

  include 'includes/appConfig.php';
  include 'includes/functions.php';


  //configure command line arguments
  if($argc > 0){
    foreach($argv as $arg){
      $args = explode('=',$arg);
      switch($args[0]){
        //case 0;
          //return displayUsage();
        case '-h':
          return displayUsage();
        case '--help';
          return displayUsage();
        case 'help';
          return displayUsage();
        case '--stop';
          return stopProcesses();
        case 'stop';
          return stopProcesses();
        case '-r':
          return restartProcesses();
        case '--restart';
          return restartProcesses();
        case 'restart';
          return restartProcesses();
        case '--start';
          return startProcesses();
        case 'start';
          return startProcesses();
        case '-m';
          return monitorAppStatus();
        case '--monitor';
          return monitorAppStatus();
        case 'monitor';
          return monitorAppStatus();
        $log = $args[1];
        break;
      }
    }
  }
?>
