<?php

function __autoload($class) {
  require SITE_ROOT . '/lib/' . strtolower($class) . '.php';
}


//
// Website Comments Form
//
function insertComment() {
  if (!$_POST['operator']) {
    unset($_POST);
    header('location:/pages/comments.php');
  }else{
    $rating = $_POST['rating'];
    $userName = $_POST['userName'];
    $comment = $_POST['comment'];
    if (!$userName) {
      $userName = '---';
    }
    $sql = "insert into site_comments (name,rating,message,datetime,siteLocation) values('$userName','$rating','$comment',NOW(),'7DaysManager')";
    //$jurisdictionErr = $rdbReplicationErr = $logErr = $operatorErr = "";
    //$jurisdiction = $rdbReplication = $genLog = $operator = "";
    $no = " no";
    if (!mysql_query($sql)) {
      die('Error: ' . mysql_error());
    }
    header('location:/pages/comments.php');
    unset($_POST);
  }
}


//////////////////
// 7DaysManager //
//////////////////

function getAllPlayers_List() {
  $onlinePlayers = mysql_query("SELECT * FROM players order by playerid asc");
  if (!$onlinePlayers) {
    die('Invalid query: ' . mysql_error());
  }
  while($row = mysql_fetch_array($onlinePlayers)) {
    echo '<tr>';
    echo '<td class="text-left">' . $row['playerid'] . '</td>';
    echo '<td class="text-left">' . $row['playerName'] . '</td>';
    echo '<td class="text-left">' . $row['level'] . '</td>';
    echo '<td class="text-left">' . $row['health'] . '</td>';
    echo '<td class="text-left">' . $row['zombiesKilled'] . '</td>';
    echo '<td class="text-left">' . $row['playersKilled'] . '</td>';
    echo '<td class="text-left">' . $row['deaths'] . '</td>';
    echo '<td class="text-left">' . $row['currentPosition'] . '</td>';
    echo '<td class="text-left"><a href="http://steamidfinder.com/lookup/' . $row['steamid'] . ' "target="_blank">' . $row['steamid'] . '</a></td>';
    echo '<td class="text-left"><a href="https://tools.keycdn.com/geo?host=' . $row['ip'] . ' "target="_blank">' . $row['ip'] . '</a></td>';
    echo '</tr>';
  }
}

function getBannedPlayers_List() {
  $onlinePlayers = mysql_query("SELECT * FROM server_bans order by playerid asc");
  if (!$onlinePlayers) {
    die('Invalid query: ' . mysql_error());
  }
  while($row = mysql_fetch_array($onlinePlayers)) {
    echo '<tr>';
    echo '<td class="text-left">' . $row['playerName'] . '</td>';
    echo '<td class="text-left">' . $row['reason'] . '</td>';
    echo '<td class="text-left">' . $row['playTime'] . '</td>';
    echo '<td class="text-left">' . $row['bannedTo'] . '</td>';
    echo '<td class="text-left"><a href="http://steamidfinder.com/lookup/' . $row['steamid'] . ' "target="_blank">' . $row['steamid'] . '</a></td>';
    echo '<td class="text-left"><a href="https://tools.keycdn.com/geo?host=' . $row['ip'] . ' "target="_blank">' . $row['ip'] . '</a></td>';
    echo '</tr>';
  }
}

function getOnlinePlayers_List() {
  $onlinePlayers = mysql_query("SELECT * FROM players where onlineStatus = '1' order by playerid asc");
  if (!$onlinePlayers) {
    die('Invalid query: ' . mysql_error());
  }
  while($row = mysql_fetch_array($onlinePlayers)) {
    echo '<tr>';
    echo '<td class="text-left">' . $row['playerid'] . '</td>';
    echo '<td class="text-left">' . $row['playerName'] . '</td>';
    echo '<td class="text-left">' . $row['level'] . '</td>';
    echo '<td class="text-left">' . $row['health'] . '</td>';
    echo '<td class="text-left">' . $row['zombiesKilled'] . '</td>';
    echo '<td class="text-left">' . $row['playersKilled'] . '</td>';
    echo '<td class="text-left">' . $row['deaths'] . '</td>';
    echo '<td class="text-left">' . $row['currentPosition'] . '</td>';
    echo '<td class="text-left"><a href="http://steamidfinder.com/lookup/' . $row['steamid'] . ' "target="_blank">' . $row['steamid'] . '</a></td>';
    echo '<td class="text-left"><a href="https://tools.keycdn.com/geo?host=' . $row['ip'] . ' "target="_blank">' . $row['ip'] . '</a></td>';
    echo '<td class="text-left">' . $row['ping'] . '</td>';
    echo '</tr>';
  }
}

function queryGameTime() {
  $queryGameTime = mysql_query("SELECT * FROM server_gameTime");
  while($queryGameTime = mysql_fetch_array($queryGameTime)) {
    echo '<big>';
    echo $queryGameTime['currentTime'];
    echo '</big></span>'; echo '<span class="info-box-number">Day ';
    echo $gameCurrentDay = $queryGameTime['currentDay'];
    echo '</span>'; echo '<span class="info-box-number"><small><i>(Next Bloodmoon: ';
    $gameCurrentDay = ceil($gameCurrentDay / 7) * 7;
    echo ')</i></small>';
  }
}

function settingsUpdateServerConnection() {
  global $APP_LOG_LIMIT;

  if (!$_POST['inputIP']) {
    unset($_POST);
    header('location:/pages/settings/serverSettings.php');
  }else{
    $inputIP = $_POST['inputIP'];
    $inputPass = $_POST['inputPass'];
    $inputPort = $_POST['inputPort'];
    $inputEnabled = $_POST['inputEnabled'];

    $sql = "replace into servers (IP, telnetPort, password, isEnabled, dateUpdated) values ('$inputIP', '$inputPass', '$inputPort', NOW())";
    if (!mysql_query($sql)) {
      die('Error: ' . mysql_error());
    }
    unset($_POST);
    header('location:/pages/settings/serverSettings.php');
  }
}

function getAppLog() {
  $getAppLog = mysql_query("SELECT * FROM app_log order by datetime desc limit " . APP_LOG_LIMIT . "");
  if (!$getAppLog) {
    die('Invalid query: ' . mysql_error());
  }
  while($row = mysql_fetch_array($getAppLog)) {
    echo '<tr>';
    echo '<td class="text-left">' . $row['datetime'] . '</td>';
    echo '<td class="text-left">' . $row['logLevel'] . '</td>';
    echo '<td class="text-left">' . $row['runName'] . '</td>';
    echo '<td class="text-left">' . $row['message'] . '</td>';
    echo '</tr>';
  }
}

?>
