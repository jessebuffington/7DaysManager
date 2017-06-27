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

include '../includes/config.php';

$dir = '/home/steam/Steam/servers/7daysManager/';
$log = '/home/steam/Steam/servers/7daysManager/log/7daysManager.log';
 
//Method for displaying the help and default variables.
function displayUsage(){
    global $log;
 
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

//fork the process to work in a daemonized environment
file_put_contents($log, 'Status: starting up.\n', FILE_APPEND);
$pid = pcntl_fork();
if($pid == -1){
	file_put_contents($log, 'Error: could not daemonize process.\n', FILE_APPEND);
	return 1; //error
}
else if($pid){
	return 0; //success
}
else{
    //the main process
    while(true){
	file_put_contents($log, 'Running...\n', FILE_APPEND);
	sleep(5);

	exec('lib/listPlayers.php');
	sleep 30
    }
}
 
?>
