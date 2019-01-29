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
  $onlinePlayers = mysql_query("SELECT * FROM players where not playerid = '0' order by playerid asc");
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
    if ($row['banned'] == 1) {
     echo '<td class="text-left"><input type="submit" name="UnBan" id="UnBan" value="UnBan" class="btn btn-success pull-right">UnBan</input></td>';
   } else {
     echo '<td class="text-left"><input type="submit" name="Ban" id="Ban" value="Ban" class="btn btn-danger pull-right">Ban</input></td>';
   }
   if (isset($_POST['UnBan'])) {
     $unbanPlayer = "UPDATE players set banned = '0' where playerid = '".$row['playerid']."'";
     $manUnbanPlayer = "UPDATE server_bans set manUnban = '1' where steamid  = '".$row['steamid']."'";
     if (!mysql_query($unbanPlayer)) {
       die('Error: ' . mysql_error());
     }
     if (!mysql_query($manUnbanPlayer)) {
       die('Error: ' . mysql_error());
     }
   } elseif (isset($_POST['Ban'])) {
     $unbanPlayer = "UPDATE players set banned = '1' where playerid = '".$row['playerid']."'";
     $manUnbanPlayer = "INSERT into server_bans (playerName, steamid, ip, reason, bannedTo, permanent, playTime, score, playerKills, zombies, dateAdded, admin)
                         VALUES ('".$row['playerName']."', '".$row['steamid']."', '".$row['ip']."', $reason, $bannedTo, $permanent, '".$row['playTime']."', '".$row['score']."', '".$row['playerKills']."', '".$row['zombies']."', NOW(), '".$_SESSION['loginUsername']."')";
     if (!mysql_query($unbanPlayer)) {
       die('Error: ' . mysql_error());
     }
     if (!mysql_query($manUnbanPlayer)) {
       die('Error: ' . mysql_error());
     }
   }
    echo '</tr>';
  }
}

function getBannedPlayers_List() {
  $onlinePlayers = mysql_query("SELECT * FROM server_bans where manUnban = '0' order by id asc");
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
    if ($row['banned'] = 1) {
      echo '<td class="text-left"><button type="submit" name="submit" id="submit" value="Submit" class="btn btn-success pull-right">UnBan</button></td>';
    }
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
    echo '<td class="text-left">
      <button type="submit" name="submit" id="submit" value="Submit" class="btn btn-primary pull-right">PM</button></td>
      <button type="submit" name="submit" id="submit" value="Submit" class="btn btn-success pull-right">Give</button></td>
      <button type="submit" name="submit" id="submit" value="Submit" class="btn btn-warning pull-right">Kick</button></td>
      <button type="submit" name="submit" id="submit" value="Submit" class="btn btn-danger pull-right">Ban</button></td>';
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
    //$gameCurrentDay = ceil($gameCurrentDay / 7) * 7;
    echo $queryGameTime['daysLeft'];
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

function getGameLog() {
  $getGameLog = mysql_query("SELECT * FROM server_log order by id desc limit " . GAME_LOG_LIMIT . "");
  if (!$getGameLog) {
    die('Invalid query: ' . mysql_error());
  }
  while($row = mysql_fetch_array($getGameLog)) {
    echo '<tr>';
    echo '<td class="text-left">' . $row['message'] . '</td>';
    echo '</tr>';
  }
}

function getShopLog() {
  $getAppLog = mysql_query("SELECT * FROM shop_log order by datetime desc limit " . SHOP_LOG_LIMIT . "");
  if (!$getAppLog) {
    die('Invalid query: ' . mysql_error());
  }
  while($row = mysql_fetch_array($getShopLog)) {
    echo '<tr>';
    echo '<td class="text-left">' . $row['datetime'] . '</td>';
    echo '<td class="text-left">' . $row['logLevel'] . '</td>';
    echo '<td class="text-left">' . $row['runName'] . '</td>';
    echo '<td class="text-left">' . $row['message'] . '</td>';
    echo '</tr>';
  }
}

function getSiteLoginAttempts() {
  $getSiteLoginAttempts = mysql_query("SELECT * FROM site_loginAttempts order by id desc limit " . SITE_LOGIN_ATTEMPT_LIMIT . "");
  if (!$getSiteLoginAttempts) {
    die('Invalid query: ' . mysql_error());
  }
  while($row = mysql_fetch_array($getSiteLoginAttempts)) {
    echo '<tr>';
    echo '<td class="text-left">' . $row['username'] . '</td>';
    echo '<td class="text-left">' . $row['ip'] . '</td>';
    echo '<td class="text-left">' . $row['failedAttempts'] . '</td>';
    echo '<td class="text-left">' . $row['lastLogin'] . '</td>';
    echo '<td class="text-left">' . $row['lastFailedLoginAttempt'] . '</td>';
    echo '</tr>';
  }
}

function sendGameChat() {
  if (!$_POST['message']) {
    unset($_POST);
    header('location:/');
  } else {
    $userName = "[cc0000]7DM:[f2f3f4]";
    $chatMessage = $_POST['message'];
    /*if (!$userName) {
      $userName = '\[cc0000\]7DM:%20\[f2f3f4\]';
    }*/
    $chatMessage = str_replace(array(' '), '%20', $chatMessage);
    $url = 'http://' . API_HOST . ':' . API_PORT . '/api/executeconsolecommand?adminuser=' . API_USER . '&admintoken=' . API_PASS . '&command=say%20"' . $userName . '%20' . $chatMessage . '"';
    $queryAPI = file_get_contents($url);
    echo $queryAPI;

    unset($_POST['message']);
  }
  unset($_POST['message']);
  header('Refresh: 2; URL = /index.php');
}

?>
