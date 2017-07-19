<html>
  <body>
    <head>
      <meta charset="UTF-8">
      <link rel='stylesheet prefetch' href='/css/bootstrap.css'>
      <link rel='stylesheet prefetch' href='/css/mdb.min.css'>
      <link rel='stylesheet prefetch' href='/css/font-awesome.css'>
      <link rel="stylesheet" href="/css/main.css">
      <?php
        define ( 'pageTitle','Online Players' );
        include_once($_SERVER["DOCUMENT_ROOT"] . "/lib/header.php");
      ?>
    </head>
    <header>
      <?php
        include_once($_SERVER["DOCUMENT_ROOT"] . "/lib/navbar.php");
      ?>
    </header>
    <div class="table-title">
      <h3><?php echo $pageTitle;?></h3>
    </div>
    <table id="onlinePlayers" class="table-fill">
      <thead>
        <tr>
          <th class="text-left">Player ID</th>
          <th class="text-left">Player Name</th>
          <th class="text-left">Level</th>
          <th class="text-left">Health</th>
          <th class="text-left">Zombies Killed</th>
          <th class="text-left">Players Killed</th>
          <th class="text-left">Deaths</th>
          <th class="text-left">Position</th>
          <th class="text-left">Steam ID</th>
          <th class="text-left">IP</th>
          <th class="text-left">Ping</th>
        </tr>
      </thead>
      <tbody class="table-hover">
        <?php
          $onlinePlayers = mysql_query("SELECT * FROM players order by id asc");
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
        ?>
      </tbody>
    </table>
    <!--Footer-->
    <?php
      include_once($_SERVER["DOCUMENT_ROOT"] . "/lib/footer.php");
    ?>
  </body>
</html>
