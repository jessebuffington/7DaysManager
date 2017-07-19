<html>
  <body>
    <head>
      <meta charset="UTF-8">
      <link rel='stylesheet prefetch' href='/css/bootstrap.css'>
      <link rel='stylesheet prefetch' href='/css/mdb.min.css'>
      <link rel='stylesheet prefetch' href='/css/font-awesome.css'>
      <link rel="stylesheet" href="/css/main.css">
      <?php
        define ( 'pageTitle','Banned Users' );
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
    <table id="bannedPlayers" class="table-fill">
      <thead>
        <tr>
          <th class="text-left">Action</th>
          <th class="text-left">Username</th>
          <th class="text-left">Reason</th>
          <th class="text-left">Play Time</th>
          <th class="text-left">Banned Until</th>
          <th class="text-left">Steam ID</th>
          <th class="text-left">IP</th>
        </tr>
      </thead>
      <tbody class="table-hover">
        <?php
          $bannedPlayers = mysql_query("SELECT * FROM bans where manUnban = '0' order by id asc");
          if (!$bannedPlayers) {
            echo '<tr>';
            echo '<td class="text-left">NO BANS YET!</td>';
            echo '</tr>';
            die('Invalid query: ' . mysql_error());
          }
          while($row = mysql_fetch_array($bannedPlayers)) {
            echo '<tr>';
            echo '<td class="text-left"><a href="#">Unban</a></td>';
            echo '<td class="text-left">' . $row['username'] . '</td>';
            echo '<td class="text-left-red">' . $row['reason'] . '</td>';
            echo '<td class="text-left">' . $row['playTime'] . ' minutes</td>';
            echo '<td class="text-left">' . $row['bannedTo'] . '</td>';
            echo '<td class="text-left"><a href="http://steamidfinder.com/lookup/' . $row['steam'] . ' "target="_blank">' . $row['steam'] . '</a></td>';
            echo '<td class="text-left"><a href="https://tools.keycdn.com/geo?host=' . $row['ip'] . ' "target="_blank">' . $row['ip'] . '</a></td>';
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
