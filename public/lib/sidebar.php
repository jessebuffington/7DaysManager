<!--
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
//       :YYiii$$$$$$$YYYYYYY$$$$YY$$$$YYiiiiiYYYYYYi
//
//          XOXO
//  		Jesse B.
//
-->
  <aside class="main-sidebar">
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <!--<div class="user-panel">
        <div class="pull-left image">
          <img src="/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p>Alexander Pierce</p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div> -->
      <form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="This is broken...">
          <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat">
                  <i class="fa fa-search"></i>
                </button>
              </span>
        </div>
      </form>
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">MAIN NAVIGATION</li>
         <li <?php if($pageTitle == 'Dashboard') {echo 'class="active"';} ?>>
          <a href="/index.php">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
          </a>
        </li>
        <li <?php if($pageParent == 'Players') {echo 'class="active treeview menu open"';}else{echo 'class="treeview menu"';} ?>>
          <a href="#">
            <i class="fa fa-th"></i> <span>Players</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li <?php if($pageTitle == 'Online Players') {echo 'class = "active"';} ?>><a href="/pages/onlinePlayers.php"><i class="fa fa-circle-o"></i> Online Players</a></li>
            <li <?php if($pageTitle == 'Banned Players') {echo 'class = "active"';} ?>><a href="/pages/bannedPlayers.php"><i class="fa fa-circle-o"></i> Banned Players</a></li>
            <li <?php if($pageTitle == 'All Players') {echo 'class = "active"';} ?>><a href="/pages/allPlayers.php"><i class="fa fa-circle-o"></i> All Players</a></li>
          </ul>
        </li>
        <!--<li class="treeview">
          <a href="#">
            <i class="fa fa-files-o"></i>
            <span>Layout Options</span>
            <span class="pull-right-container">
              <span class="label label-primary pull-right">4</span>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="pages/layout/top-nav.html"><i class="fa fa-circle-o"></i> Top Navigation</a></li>
            <li><a href="pages/layout/boxed.html"><i class="fa fa-circle-o"></i> Boxed</a></li>
            <li><a href="pages/layout/fixed.html"><i class="fa fa-circle-o"></i> Fixed</a></li>
            <li><a href="pages/layout/collapsed-sidebar.html"><i class="fa fa-circle-o"></i> Collapsed Sidebar</a></li>
          </ul>
        </li>
        <li>
          <a href="/pages/widgets.html">
            <i class="fa fa-th"></i> <span>Widgets</span>
            <span class="pull-right-container">
              <small class="label pull-right bg-green">new</small>
            </span>
          </a>
        </li>-->
        <li <?php if($pageParent == 'Reports') {echo 'class="active treeview menu open"';}else{echo 'class="treeview menu"';} ?>>
          <a href="#">
            <i class="fa fa-pie-chart"></i>
            <span>Reports</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li <?php if($pageTitle == 'Player Report') {echo 'class = "active"';} ?>><a href="/pages/reports/playerReport.php"><i class="fa fa-circle-o"></i> Player Report</a></li>
            <li <?php if($pageTitle == 'Server Utilization') {echo 'class = "active"';} ?>><a href="/pages/reports/serverUsageReport.php"><i class="fa fa-circle-o"></i> Server Utilization</a></li>
            <!--<li <?php if($pageTitle == 'PAGE') {echo 'class = "active"';} ?>><a href="/pages/reports/#"><i class="fa fa-circle-o"></i> PAGE</a></li>
            <li <?php if($pageTitle == 'PAGE') {echo 'class = "active"';} ?>><a href="/pages/reports/#"><i class="fa fa-circle-o"></i> PAGE</a></li>
            <li <?php if($pageTitle == 'PAGE') {echo 'class = "active"';} ?>><a href="/pages/reports/#"><i class="fa fa-circle-o"></i> PAGE</a></li>-->
          </ul>
        </li>
        <li <?php if($pageParent == 'Settings') {echo 'class="active treeview menu open"';}else{echo 'class="treeview menu"';} ?>>
          <a href="#">
            <i class="fa fa-laptop"></i>
            <span>Settings</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li <?php if($pageTitle == 'Server Settings') {echo 'class = "active"';} ?>><a href="/pages/settings/serverSettings.php"><i class="fa fa-circle-o"></i> Server Config</a></li>
            <li <?php if($pageTitle == 'In-Game Config') {echo 'class = "active"';} ?>><a href="/pages/settings/ingameConfig.php"><i class="fa fa-circle-o"></i> In-Game Config</a></li>
            <li <?php if($pageTitle == 'Server Economy') {echo 'class = "active"';} ?>><a href="/pages/settings/serverEconomy.php"><i class="fa fa-circle-o"></i> Server Economy</a></li>
            <li <?php if($pageTitle == 'Map Config') {echo 'class = "active"';} ?>><a href="/pages/settings/mapConfig.php"><i class="fa fa-circle-o"></i> Map Config</a></li>
            <li <?php if($pageTitle == 'Commands/Broadcasts') {echo 'class = "active"';} ?>><a href="/pages/settings/customCommands.php"><i class="fa fa-circle-o"></i> Commands/Broadcasts</a></li>
            <li <?php if($pageTitle == 'Player Management') {echo 'class = "active"';} ?>><a href="/pages/settings/playerManagement.php"><i class="fa fa-circle-o"></i> Player Management</a></li>
            <li <?php if($pageTitle == 'Player Voting System') {echo 'class = "active"';} ?>><a href="/pages/settings/playerVoting.php"><i class="fa fa-circle-o"></i> Player Voting System</a></li>
            <!--<li <?php if($pageTitle == 'PAGE') {echo 'class = "active"';} ?>><a href="/pages/settings/#"><i class="fa fa-circle-o"></i> PAGE</a></li>
            <li <?php if($pageTitle == 'PAGE') {echo 'class = "active"';} ?>><a href="/pages/settings/#"><i class="fa fa-circle-o"></i> PAGE</a></li>
            <li <?php if($pageTitle == 'PAGE') {echo 'class = "active"';} ?>><a href="/pages/settings/#"><i class="fa fa-circle-o"></i> PAGE</a></li>
            <li <?php if($pageTitle == 'PAGE') {echo 'class = "active"';} ?>><a href="/pages/settings/#"><i class="fa fa-circle-o"></i> PAGE</a></li>-->
          </ul>
        </li>
        <!--<li class="treeview">
          <a href="#">
            <i class="fa fa-edit"></i> <span>Forms</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="pages/forms/general.html"><i class="fa fa-circle-o"></i> General Elements</a></li>
            <li><a href="pages/forms/advanced.html"><i class="fa fa-circle-o"></i> Advanced Elements</a></li>
            <li><a href="pages/forms/editors.html"><i class="fa fa-circle-o"></i> Editors</a></li>
          </ul>
        </li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-table"></i> <span>Tables</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="pages/tables/simple.html"><i class="fa fa-circle-o"></i> Simple tables</a></li>
            <li><a href="pages/tables/data.html"><i class="fa fa-circle-o"></i> Data tables</a></li>
          </ul>
        </li>
        <li>
          <a href="pages/calendar.html">
            <i class="fa fa-calendar"></i> <span>Calendar</span>
            <span class="pull-right-container">
              <small class="label pull-right bg-red">3</small>
              <small class="label pull-right bg-blue">17</small>
            </span>
          </a>
        </li>
        <li>
          <a href="pages/mailbox/mailbox.html">
            <i class="fa fa-envelope"></i> <span>Mailbox</span>
            <span class="pull-right-container">
              <small class="label pull-right bg-yellow">12</small>
              <small class="label pull-right bg-green">16</small>
              <small class="label pull-right bg-red">5</small>
            </span>
          </a>
        </li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-folder"></i> <span>Examples</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="pages/examples/invoice.html"><i class="fa fa-circle-o"></i> Invoice</a></li>
            <li><a href="pages/examples/profile.html"><i class="fa fa-circle-o"></i> Profile</a></li>
            <li><a href="pages/examples/login.html"><i class="fa fa-circle-o"></i> Login</a></li>
            <li><a href="pages/examples/register.html"><i class="fa fa-circle-o"></i> Register</a></li>
            <li><a href="pages/examples/lockscreen.html"><i class="fa fa-circle-o"></i> Lockscreen</a></li>
            <li><a href="pages/examples/404.html"><i class="fa fa-circle-o"></i> 404 Error</a></li>
            <li><a href="pages/examples/500.html"><i class="fa fa-circle-o"></i> 500 Error</a></li>
            <li><a href="pages/examples/blank.html"><i class="fa fa-circle-o"></i> Blank Page</a></li>
            <li><a href="pages/examples/pace.html"><i class="fa fa-circle-o"></i> Pace Page</a></li>
          </ul>
        </li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-share"></i> <span>Multilevel</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="#"><i class="fa fa-circle-o"></i> Level One</a></li>
            <li class="treeview">
              <a href="#"><i class="fa fa-circle-o"></i> Level One
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li><a href="#"><i class="fa fa-circle-o"></i> Level Two</a></li>
                <li class="treeview">
                  <a href="#"><i class="fa fa-circle-o"></i> Level Two
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                  </a>
                  <ul class="treeview-menu">
                    <li><a href="#"><i class="fa fa-circle-o"></i> Level Three</a></li>
                    <li><a href="#"><i class="fa fa-circle-o"></i> Level Three</a></li>
                  </ul>
                </li>
              </ul>
            </li>
            <li><a href="#"><i class="fa fa-circle-o"></i> Level One</a></li>
          </ul>
        </li>
        <li><a href="https://adminlte.io/docs"><i class="fa fa-book"></i> <span>Documentation</span></a></li>
        <li class="header">LABELS</li>
        <li><a href="#"><i class="fa fa-circle-o text-red"></i> <span>Important</span></a></li>
        <li><a href="#"><i class="fa fa-circle-o text-yellow"></i> <span>Warning</span></a></li>
        <li><a href="#"><i class="fa fa-circle-o text-aqua"></i> <span>Information</span></a></li>
      </ul>-->
    </section>
  </aside>
