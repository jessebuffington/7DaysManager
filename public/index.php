<?php
  require($_SERVER["DOCUMENT_ROOT"] . "/lib/loginHeader.php");
?>

<!DOCTYPE html>
<html>
  <head>
    <?php
      $pageTitle='Dashboard';
      include($_SERVER[ "DOCUMENT_ROOT"] . "/lib/header.php");
    ?>
    <script>
      $('.gameChat').scrollTop($('.gameChat')[0].scrollHeight);
    </script>

    <script src='/pages/scripts/jquery-latest.js'></script>
    <script>
      function refresh_gameTime() {
        $("#gameTime").load(location.href + " #gameTime");
      }
      function refresh_playersOnline() {
        $("#playersOnline").load(location.href + " #playersOnline");
      }
      function refresh_playersOverall() {
        $("#playersOverall").load(location.href + " #playersOverall");
      }
      function refresh_currZombies() {
        $("#currZombies").load(location.href + " #currZombies");
      }
      function refresh_currAnimals() {
        $("#currAnimals").load(location.href + " #currAnimals");
      }
      function refresh_activePlayers() {
        $("#activePlayers").load(location.href + " #activePlayers");
      }
      function refresh_gameChat() {
        $("#gameChat").load(location.href + " #gameChat");
      }
      function refresh_playersThisWeek() {
        $("#playersThisWeek").load(location.href + " #playersThisWeek");
      }
      function refresh_appStatus() {
        $("#appStatus").load(location.href + " #appStatus");
      }
      setInterval('refresh_gameTime()', 5000);
      setInterval('refresh_playersOnline()', 10000);
      setInterval('refresh_playersOverall()', 10000);
      setInterval('refresh_currZombies()', 10000);
      setInterval('refresh_currAnimals()', 10000);
      setInterval('refresh_activePlayers()', 10000);
      setInterval('refresh_gameChat()', 100);
      setInterval('refresh_playersThisWeek()', 100);
      setInterval('refresh_appStatus()', 10000);
    </script>

  </head>
  <body class="hold-transition skin-<?php echo HEADER_COLOR ?> sidebar-mini">
    <div class="wrapper">
      <?php
        include($_SERVER[ "DOCUMENT_ROOT"] . "/lib/sidebar.php");
      ?>
      <div class="content-wrapper">
        <section class="content-header">
          <h1>
            <?php echo $pageTitle ?>
          </h1>
          <ol class="breadcrumb">
            <li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active"><?php echo $pageTitle ?></li>
          </ol>
        </section>
        <section class="content">
          <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-aqua">
                  <i class="ion ion-ios-clock-outline"></i>
                </span>
                  <div class="info-box-content">
                    <span id='gameTime' class="info-box-number">
                      <?php
                        echo queryGameTime();
                      ?>
                    </span>
                  </div>
                </div>
              </div>
              <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                  <span class="info-box-icon bg-green">
                    <i class="ion ion-ios-location-outline"></i>
                  </span>
                  <div class="info-box-content">
                    <span class="info-box-text">
                      Players Online
                    </span>
                    <span id='playersOnline' class="info-box-number">
                      <?php
                        $queryPlayers_online = mysql_query("SELECT count(*) as onlinePlayers FROM players where onlineStatus = 1");
                        while($queryPlayers_online = mysql_fetch_array($queryPlayers_online)) {
                          echo $queryPlayers_online['onlinePlayers'];
                        }
                      ?>
                    </span>
                    <span id='playersOverall' class="info-box-number">
                      <small>
                        <i>
                        <?php
                          $queryPlayers_all = mysql_query("SELECT count(*) as allPlayers FROM players");
                          while($queryPlayers_all = mysql_fetch_array($queryPlayers_all)) {
                            echo '(' . $allPlayers = $queryPlayers_all['allPlayers'];
                          }
                        ?>
                        Overall)</i>
                      </small>
                    </span>
                  </div>
                </div>
              </div>
              <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                  <span class="info-box-icon bg-red"><i class="ion ion-ios-people-outline"></i></span>
                    <div class="info-box-content">
                      <span class="info-box-text">
                        Zombies
                      </span>
                      <span id='currZombies' class="info-box-number">
                        <?php
                          $queryZombies = mysql_query("SELECT count(*) as entityZombies FROM memEntities where type like 'EntityZombie%'");
                          while($queryZombies = mysql_fetch_array($queryZombies)) {
                            echo $queryZombies['entityZombies'];
                          }
                        ?>
                      </span>
                    </div>
                  </div>
                </div>
                <div class="clearfix visible-sm-block"></div>
                <div class="col-md-3 col-sm-6 col-xs-12">
                  <div class="info-box">
                    <span class="info-box-icon bg-yellow">
                      <i class="ion ion-ios-paw-outline"></i>
                    </span>
                    <div class="info-box-content">
                      <span class="info-box-text">
                        Animals
                      </span>
                      <span id='currAnimals' class="info-box-number">
                        <?php
                          $queryAnimals = mysql_query("SELECT count(*) as entityAnimals FROM memEntities where type like 'EntityAnimal%'");
                          while($queryAnimals = mysql_fetch_array($queryAnimals)) {
                            echo $queryAnimals['entityAnimals'];
                          }
                        ?>
                      </span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-8">
                  <!-- <div class="box box-success">
                    <div class="box-header with-border">
                      <h3 class="box-title">
                        Visitors Report
                      </h3>
                      <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse">
                          <i class="fa fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove">
                          <i class="fa fa-times"></i>
                        </button>
                      </div>
                    </div>
                    <div class="box-body no-padding">
                      <div class="row">
                        <div class="col-md-9 col-sm-8">
                          <div class="pad">
                            <div id="world-map-markers" style="height: 325px;">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3 col-sm-4">
                          <div class="pad box-pane-right bg-green" style="min-height: 280px">
                            <div class="description-block margin-bottom">
                              <div class="sparkbar pad" data-color="#fff">
                                90,70,90,70,75,80,70
                              </div>
                              <h5 class="description-header">
                                8390
                              </h5>
                              <span class="description-text">
                                Visits
                              </span>
                            </div>
                            <div class="description-block margin-bottom">
                              <div class="sparkbar pad" data-color="#fff">
                                90,50,90,70,61,83,63
                              </div>
                              <h5 class="description-header">
                                30%
                              </h5>
                              <span class="description-text">
                                Referrals
                              </span>
                            </div>
                            <div class="description-block">
                              <div class="sparkbar pad" data-color="#fff">
                                90,50,90,70,61,83,63
                              </div>
                              <h5 class="description-header">
                                70%
                              </h5>
                              <span class="description-text">
                                Organic
                              </span>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div> -->
                  <div class="row">
                    <div class="col-md-8">
                      <div class="box box-warning direct-chat direct-chat-warning">
                        <div class="box-header with-border">
                          <h3 class="box-title">
                            Game Chat
                          </h3>
                        </div>
                        <div class="box-body">
                          <div class="direct-chat-messages">
                            <?php
                              $queryGameChat100=mysql_query('SELECT * FROM chatLog order by timestamp asc limit 100');
                              while($queryGameChat=mysql_fetch_array($queryGameChat100)) {
                                echo '<div class="direct-chat-msg ';
                                if($queryGameChat['inGame'] == 1) {
                                  echo 'left';
                                } else {
                                  echo 'right';
                                }
                                echo '">';
                                echo '<div class="direct-chat-info clearfix">';
                                echo '<span class="direct-chat-name pull-';
                                if($queryGameChat['inGame'] == 1) {
                                  echo 'left';
                                } else {
                                  echo 'right';
                                }
                                echo '">';
                                //echo '<a class="users-list-name" href="http://steamidfinder.com/lookup/' . $queryAllPlayersID[ 'steamid'] . '" target="_blank">';
                                echo $queryGameChat['playerName'];
                                echo '</a>';
                                echo '</span>';
                                echo '<span class="direct-chat-timestamp pull-';
                                if($queryGameChat['inGame'] == 0) {
                                  echo 'left';
                                } else {
                                  echo 'right';
                                }
                                echo '">' . $queryGameChat['timestamp'] . '</span>';
                                echo '</div>';
                                //echo '<a href="http://steamidfinder.com/lookup/' . $queryAllPlayersID['steamid'] . '" target="_blank">';
                                if($queryGameChat['inGame'] == 0) {
                                  echo '<img class="direct-chat-img" src="/lib/img/icon.png" alt="message user image">';
                                } else {
                                  echo '<img class="direct-chat-img" src="/bower_components/Ionicons/png/512/ios7-contact.png" alt="message user image">';
                                }
                                //echo '</a>';
                                echo '<div class="direct-chat-text">' . $queryGameChat['message']  . '</div>';
                                echo '</div>';
                              }
                            ?>
                          </div>
                        </div>
                        <div class="box-footer">
                          <form action="#" method="post">
                            <div class="input-group">
                              <input type="text" name="message" placeholder="Type Message ..." class="form-control">
                              <span class="input-group-btn">
                                <button type="button" class="btn btn-warning btn-flat">
                                  Send
                                </button>
                              </span>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="box box-danger">
                        <div class="box-header with-border">
                          <h3 class="box-title">
                            Active Players
                          </h3>
                          <div class="box-tools pull-right">
                            <span class="label label-danger">
                              <?php
                                echo $allPlayers;
                              ?>
                              Total Players
                            </span>
                        </div>
                      </div>
                      <div class="box-body no-padding">
                        <ul class="users-list clearfix">
                          <?php
                            $queryPlayers=mysql_query('SELECT * FROM players order by score desc limit 12');
                            while($queryAllPlayers=mysql_fetch_array($queryPlayers)) {
                              echo '<li>';
                              echo '<a class="users-list-name" href="http://steamidfinder.com/lookup/' . $queryAllPlayersID = $queryAllPlayers['steamid'] . '" target="_blank"><img src="/bower_components/Ionicons/png/512/ios7-contact.png" alt="User Image"></a>';
                              echo '<a class="users-list-name" href="http://steamidfinder.com/lookup/' . $queryAllPlayersID = $queryAllPlayers['steamid'] . '" target="_blank">' . $queryAllPlayers['playerName'] . '</a>';
                              echo '<span class="users-list-date"' . $queryAllPlayers['lastSeen'] . '</span>';
                              echo '</li>';
                            }
                          ?>
                        </ul>
                      </div>
                      <div class="box-footer text-center">
                        <a href="/pages/allPlayers.php" class="uppercase">
                          View All Players
                        </a>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="box box-info">
                  <div class="box-header with-border">
                    <h3 class="box-title">
                      App Status
                    </h3>
                  </div>
                  <div class="box-body">
                    <div class="table-responsive">
                      <table id='appStatus' class="table no-margin">
                        <thead>
                          <tr>
                            <th>Name</th>
                            <th>Status</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                            $queryAppStatus=mysql_query('SELECT * FROM app_status order by id asc');
                            while($appStatus=mysql_fetch_array($queryAppStatus)) {
                              echo '<tr>';
                              echo '<td>';
                              echo $appStatus['name'];
                              echo '</td>';
                              echo '<td>';
                              if ($appStatus['status'] == 'Active') {
                                echo '<span class="label label-success">Active</span>';
                              } else {
                                echo '<span class="label label-danger">InActive</span>';
                              }
                              echo '</td>';
                              echo '</tr>';
                            }
                          ?>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="box-footer clearfix">
                <a href="/pages/reports/appLog.php" class="btn btn-sm btn-info btn-flat pull-left">
                  View App Log
                </a>
                <a href="javascript:void(0)" class="btn btn-sm btn-default btn-flat pull-right">
                  View App Processes
                </a>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="info-box bg-blue">
              <span class="info-box-icon">
                <i class="ion ion-ios-pricetag-outline"></i>
              </span>
              <div class="info-box-content">
                <span class="info-box-text">
                  Shop Items Purchased (This Week)
                </span>
                <span class="info-box-number">
                  590
                </span>
                <div class="progress">
                  <div class="progress-bar" style="width: 90%"></div>
                </div>
                <span class="progress-description">
                  50% Increase in 30 Days
                </span>
              </div>
            </div>
            <div class="info-box bg-green">
              <span class="info-box-icon">
                <i class="ion ion-ios-heart-outline"></i>
              </span>
              <div class="info-box-content">
                <span class="info-box-text">
                  Players (This Week)
                </span>
                <span class="info-box-number">
                  <?php
                    $queryPlayersThisWeek=mysql_query('select count(*) as playerWeekCount from players where lastSeen >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)');
                    while($countPlayersThisWeek=mysql_fetch_array($queryPlayersThisWeek)) {
                      echo $countPlayersThisWeek['playerWeekCount'];
                    }
                  ?>
                </span>
                <div class="progress">
                  <div class="progress-bar" style="width: 20%"></div>
                </div>
                <span class="progress-description">
                  20% Increase in 30 Days
                </span>
              </div>
            </div>
            <!--<div class="info-box bg-red">
              <span class="info-box-icon">
                <i class="ion ion-ios-cloud-download-outline"></i>
              </span>
              <div class="info-box-content">
                <span class="info-box-text">
                  Downloads
                </span>
                <span class="info-box-number">
                  114,381
                </span>
                <div class="progress">
                  <div class="progress-bar" style="width: 70%"></div>
                </div>
                <span class="progress-description">
                  70% Increase in 30 Days
                </span>
              </div>
            </div>
            <div class="info-box bg-aqua">
              <span class="info-box-icon">
                <i class="ion-ios-chatbubble-outline"></i>
              </span>
              <div class="info-box-content">
                <span class="info-box-text">
                  Direct Messages
                </span>
                <span class="info-box-number">
                  163,921
                </span>
                <div class="progress">
                  <div class="progress-bar" style="width: 40%"></div>
                </div>
                <span class="progress-description">
                  40% Increase in 30 Days
                </span>
              </div>
            </div>-->
          </div>
        </div>
      </section>
    </div>
    <?php
      include_once($_SERVER[ "DOCUMENT_ROOT"] . "/lib/footer.php");
    ?>
  </body>
</html>
