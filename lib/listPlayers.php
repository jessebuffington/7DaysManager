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

session_start();

include '../includes/config.php';
include '../includes/dbConn.php';
require_once '../PHPTelnet.php';


$value1 = $telnetResult['playerid'];
$value2 = $telnetResult['playerName'];
$value3 = $telnetResult['currentPosition'];
$value4 = $telnetResult['rotPosition'];
$value5 = $telnetResult['remote'];
$value6 = $telnetResult['health'];
$value7 = $telnetResult['deaths'];
$value8 = $telnetResult['zombiesKilled'];
$value9 = $telnetResult['playersKilled'];
$value10 = $telnetResult['score'];
$value11 = $telnetResult['level'];
$value12 = $telnetResult['steamid'];
$value13 = $telnetResult['ip'];
$value14 = $telnetResult['ping'];
$value15 = $telnetResult['onlineStatus'];

$telnet = new PHPTelnet(); // using a function from the included script

$telnetResult = $telnet->Connect($TELNET_HOST,$TELNET_PORT , $TELNET_PASS); 
switch ($telnetResult) {
	case 0: 
	echo "Connected!!";

	$telnet->DoCommand('lp', $telnetResult);
	echo $result;
	$telnet->DoCommand('exit', $telnetResult); 
	echo $result;
}

$sqlUpdatePlayer = "UPDATE players set playerName = '$value2', currentPosition = '$value3', rotPosition = '$value4', remote = '$value5', health = '$value6', deaths = '$value7', zombiesKilled = '$value8', playersKilled = '$value9', score = '$value10', level = '$value11', steamid = '$value12', ip = '$value13', ping = '$value14', onlineStatus = 1 where playerid='$value1'";
$sqlInsertNewPlayer = "INSERT INTO players (playerid, playerName, currentPosition, rotPosition, remote, health, deaths, zombiesKilled, playersKilled, score, level, steamid, ip, ping, onlineStatus) VALUES ('$value1', '$value2', '$value3', '$value4', '$value5', '$value6', '$value7', '$value8', '$value9', '$value10', '$value11', '$value12', '$value13', '$value14', '$value15')";
$sqlGetPlayers = "SELECT playerid from 'players'";

$sqlGetPlayers_result = mysql_query($sqlGetPlayers) or die(mysql_error());

while ($row = mysql_fetch_array($sqlGetPlayers_result)
        if ($value1 = $sqlGetPlayers_result['playerid']) {
		if (!mysql_query($sqlUpdatePlayer)) {
			die('Error: ' . mysql_error());
		}
	}else{
		if (!mysql_query($sqlInsertNewPlayer)) {
			die('Error: ' . mysql_error());
		}
	}

mysql_close();

?>
