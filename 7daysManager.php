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
        case '--help':
        return displayUsage();
        case '--log':
        $log = $args[1];
        break;
      }
    }
  }

/*
  ini_set('display_errors',1);
  print "Parent : ". getmypid() . "\n";

  global $pids;
  $pids = Array();

  // Daemonize
  $pid = pcntl_fork();
  if($pid){
   // Only the parent will know the PID. Kids aren't self-aware
   // Parent says goodbye!
   print "\tParent : " . getmypid() . " exiting\n";
   exit();
  }

  print "Child : " . getmypid() . "\n";

  // Handle signals so we can exit nicely
  declare(ticks = 1);
  function sig_handler($signo){
   global $pids,$pidFileWritten;
   if ($signo == SIGTERM || $signo == SIGHUP || $signo == SIGINT){
   // If we are being restarted or killed, quit all children

   // Send the same signal to the children which we recieved
   foreach($pids as $p){ posix_kill($p,$signo); }

   // Women and Children first (let them exit)
   foreach($pids as $p){ pcntl_waitpid($p,$status); }
   print "Parent : "
   .  getmypid()
   . " all my kids should be gone now. Exiting.\n";
   exit();
   }else if($signo == SIGUSR1){
   print "I currently have " . count($pids) . " children\n";
   }
  }
  // setup signal handlers to actually catch and direct the signals
  pcntl_signal(SIGTERM, "sig_handler");
  pcntl_signal(SIGHUP,  "sig_handler");
  pcntl_signal(SIGINT, "sig_handler");
  pcntl_signal(SIGUSR1, "sig_handler");

  // The program to launch
  $program = "lib/syncAllPlayers.php";
  $arguments = Array("");

  while(TRUE){
   if(count($pids) < 6){
   $pid=pcntl_fork();
   if(!$pid){
   pcntl_exec($program,$arguments); // takes an array of arguments
   exit();
   } else {
   $pids[] = $pid;
   }
   }

   // Collect any children which have exited on their own. pcntl_waitpid will
   // return the PID that exited or 0 or ERROR
   // WNOHANG means we won't sit here waiting if there's not a child ready
   // for us to reap immediately
   // -1 means any child
   $dead_and_gone = pcntl_waitpid(-1,$status,WNOHANG);
   while($dead_and_gone > 0){
   // Remove the gone pid from the array
   unset($pids[array_search($dead_and_gone,$pids)]);

   // Look for another one
   $dead_and_gone = pcntl_waitpid(-1,$status,WNOHANG);
   }

   // Sleep for 1 second
   sleep(1);
  }
*/

shell_exec('nohup php -f lib/syncAllPlayers.php >> var/log/syncAllPlayers.log 2>&1 &');
//shell_exec('nohup php -f lib/syncEntities.php >> var/log/syncEntities.log 2>&1 &');
//shell_exec('nohup php -f lib/syncGameLog.php >> var/log/syncGameLog.log 2>&1 &');
shell_exec('nohup php -f lib/syncGameTime.php >> var/log/syncGameTime.log 2>&1 &');
shell_exec('nohup php -f lib/syncGameVersion.php >> var/log/syncGameVersion.log 2>&1 &');
//shell_exec('nohup php -f lib/syncLandclaims.php >> var/log/syncLandClaims.log 2>&1 &');
shell_exec('nohup php -f lib/syncOnlinePlayers.php >> var/log/syncOnlinePlayers.log 2>&1 &');
shell_exec('nohup php -f lib/syncServerInfo.php >> var/log/syncServerInfo.log 2>&1 &');
shell_exec('nohup php -f lib/insertPlayerHistory.php > var/log/insertPlayerHistory.log 2>&1 &');
shell_exec('nohup php -f lib/syncGameChat.php > var/log/syncGameChat.log 2>&1 &');

?>
