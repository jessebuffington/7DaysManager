<!DOCTYPE html>
<html>
<!--
                Y.                      _
                YiL                   .```.
                Yii;                .; .;;`.
                YY;ii._           .;`.;;;; :
                iiYYYYYYiiiii;;;;i` ;;::;;;;
            _.;YYYYYYiiiiiiYYYii  .;;.   ;;;
         .YYYYYYYYYYiiYYYYYYYYYYYYii;`  ;;;;
       .YYYYYYY$$YYiiYY$$$$iiiYYYYYY;.ii;`..
      :YYY$!.  TYiiYY$$$$$YYYYYYYiiYYYYiYYii.
      Y$MM$:   :YYYYYY$!"``"4YYYYYiiiYYYYiiYY.
   `. :MM$$b.,dYY$$Yii" :'   :YYYYllYiiYYYiYY
_.._ :`4MM$!YYYYYYYYYii,.__.diii$$YYYYYYYYYYY
.,._ $b`P`     "4$$$$$iiiiiiii$$$$YY$$$$$$YiY;
   `,.`$:       :$$$$$$$$$YYYYY$$$$$$$$$YYiiYYL
    "`;$$.    .;PPb$`.,.``T$$YY$$$$YYYYYYiiiYYU:
    ;$P$;;: ;;;;i$y$"!Y$$$b;$$$Y$YY$$YYYiiiYYiYY
    $Fi$$ .. ``:iii.`-":YYYYY$$YY$$$$$YYYiiYiYYY
    :Y$$rb ````  `_..;;i;YYY$YY$$$$$$$YYYYYYYiYY:
     :$$$$$i;;iiiiidYYYYYYYYYY$$$$$$YYYYYYYiiYYYY.
      `$$$$$$$YYYYYYYYYYYYY$$$$$$YYYYYYYYiiiYYYYYY
      .i!$$$$$$YYYYYYYYY$$$$$$YYY$$YYiiiiiiYYYYYYY
     :YYiii$$$$$$$YYYYYYY$$$$YY$$$$YYiiiiiYYYYYYi'

        Much code -- WOW
		Jesse B.
-->

  <head>
    <?php
      $pageTitle='Dashboard';
      include_once($_SERVER[ "DOCUMENT_ROOT"] . "/lib/header.php");
    ?>
    <script>
      $('.gameChat').scrollTop($('.gameChat')[0].scrollHeight);
    </script>
  </head>
  <body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">
      <?php
        include_once($_SERVER[ "DOCUMENT_ROOT"] . "/lib/sidebar.php");
      ?>
      <div class="content-wrapper">
        <section class="content-header">
          <h1>
            Dashboard
            <small>
              Version <?php echo $version;?>
            </small>
          </h1>
          <ol class="breadcrumb">
            <li>
              <a href="#">
                <i class="fa fa-dashboard"></i>
                 Home
              </a>
            </li>
            <li class="active">
              Dashboard
            </li>
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
                    <span class="info-box-number">
                      <?php
                        $queryGameTime = mysql_query("SELECT * FROM gameTime");
                        while($queryGameTime = mysql_fetch_array($queryGameTime)) {
                          echo '<big>';
                          echo $queryGameTime['currentTime'];
                          echo '</big></span>'; echo '<span class="info-box-number">Day ';
                          echo $gameCurrentDay = $queryGameTime['currentDay'];
                          echo '</span>'; echo '<span class="info-box-number"><small><i>(Next Bloodmoon: ';
                          $gameCurrentDay = ceil($gameCurrentDay / 7) * 7;
                          echo ')</i></small>';
                        }
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
                    <span class="info-box-number">
                      <?php
                        $queryPlayers_online = mysql_query("SELECT count(*) as onlinePlayers FROM players where onlineStatus = 1");
                        while($queryPlayers_online = mysql_fetch_array($queryPlayers_online)) {
                          echo $queryPlayers_online['onlinePlayers'];
                        }
                      ?>
                    </span>
                    <span class="info-box-number">
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
                      <span class="info-box-number">
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
                      <span class="info-box-number">
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
                  <div class="box box-success">
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
                  </div>
                  <div class="row">
                    <div class="col-md-8">
                      <div class="box box-warning direct-chat direct-chat-warning">
                        <div class="box-header with-border">
                          <h3 class="box-title">
                            Game Chat
                          </h3>
                          <div class="box-tools pull-right">
                            <!--<span data-toggle="tooltip" title="3 New Messages" class="badge bg-yellow">
                              3
                            </span>-->
                            <button type="button" class="btn btn-box-tool" data-widget="collapse">
                              <i class="fa fa-minus"></i>
                            </button>
                            <!--<button type="button" class="btn btn-box-tool" data-toggle="tooltip" title="Contacts" data-widget="chat-pane-toggle">
                              <i class="fa fa-comments"></i>
                            </button>-->
                            <button type="button" class="btn btn-box-tool" data-widget="remove">
                              <i class="fa fa-times"></i>
                            </button>
                          </div>
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
                                echo '<img class="direct-chat-img" src="/bower_components/Ionicons/png/512/ios7-contact.png" alt="message user image">';
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
                            Most Active Players
                          </h3>
                          <div class="box-tools pull-right">
                            <span class="label label-danger">
                              <?php
                                echo $allPlayers;
                              ?>
                              Total Players
                            </span>
                            <type="button" class="btn btn-box-tool" data-widget="collapse">
                            <i class="fa fa-minus"></i>
                          </button>
                          <button type="button" class="btn btn-box-tool" data-widget="remove">
                            <i class="fa fa-times"></i>
                          </button>
                        </div>
                      </div>
                      <div class="box-body no-padding">
                        <ul class="users-list clearfix">
                          <?php
                            $queryPlayers=mysql_query('SELECT * FROM players order by score desc limit 12');
                            while($queryAllPlayers=mysql_fetch_array($queryPlayers)) {
                              echo '<li>';
                              echo '<a class="users-list-name" href="http://steamidfinder.com/lookup/' . $queryAllPlayersID = $queryAllPlayers[ 'steamid'] . '" target="_blank"><img src="/bower_components/Ionicons/png/512/ios7-contact.png" alt="User Image"></a>';
                              echo '<a class="users-list-name" href="http://steamidfinder.com/lookup/' . $queryAllPlayersID = $queryAllPlayers[ 'steamid'] . '" target="_blank">' . $queryAllPlayers['playerName'] . '</a>';
                              echo '<span class="users-list-date"' . $queryAllPlayers[ 'lastSeen'] . '</span>';
                              echo '</li>';
                            }
                          ?>
                        </ul>
                      </div>
                      <div class="box-footer text-center">
                        <a href="/pages/allplayers.php" class="uppercase">
                          View All Players
                        </a>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="box box-info">
                  <div class="box-header with-border">
                    <h3 class="box-title">
                      Latest Orders
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
                  <div class="box-body">
                    <div class="table-responsive">
                      <table class="table no-margin">
                        <thead>
                          <tr>
                            <th>Order ID</th>
                            <th>Item</th>
                            <th>Status</th>
                            <th>Popularity</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td><a href="pages/examples/invoice.html">
                              OR9842
                            </a>
                          </td>
                          <td>Call of Duty IV</td>
                          <td><span class="label label-success">
                            Shipped
                          </span>
                        </td>
                        <td>
                          <div class="sparkbar" data-color="#00a65a" data-height="20">
                            90,80,90,-70,61,-83,63
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <a href="pages/examples/invoice.html">
                            OR1848
                          </a>
                        </td>
                        <td>
                          Samsung Smart TV
                        </td>
                        <td>
                          <span class="label label-warning">
                            Pending
                          </span>
                        </td>
                        <td>
                          <div class="sparkbar" data-color="#f39c12" data-height="20">
                            90,80,-90,70,61,-83,68
                          </div>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="box-footer clearfix">
                <a href="javascript:void(0)" class="btn btn-sm btn-info btn-flat pull-left">
                  Place New Order
                </a>
                <a href="javascript:void(0)" class="btn btn-sm btn-default btn-flat pull-right">
                  View All Orders
                </a>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="info-box bg-yellow">
              <span class="info-box-icon">
                <i class="ion ion-ios-pricetag-outline"></i>
              </span>
              <div class="info-box-content">
                <span class="info-box-text">
                  Inventory
                </span>
                <span class="info-box-number">
                  5,200
                </span>
                <div class="progress">
                  <div class="progress-bar" style="width: 50%"></div>
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
                  Mentions
                </span>
                <span class="info-box-number">
                  92,050
                </span>
                <div class="progress">
                  <div class="progress-bar" style="width: 20%"></div>
                </div>
                <span class="progress-description">
                  20% Increase in 30 Days
                </span>
              </div>
            </div>
            <div class="info-box bg-red">
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
            </div>
          </div>
        </div>
      </section>
    </div>
    <?php
      include_once($_SERVER[ "DOCUMENT_ROOT"] . "/lib/footer.php");
    ?>
  </body>
</html>
