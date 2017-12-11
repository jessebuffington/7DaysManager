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
        <br />
        <br />
        <br />
        <br />
        <br />
        <br />
        <br />
        <br />
        <li <?php if($pageTitle == 'Comments/Suggestions') {echo 'class="active"';} ?>>
          <a href="/pages/comments.php">
            <i class="fa fa-commenting-o"></i> <span>Comments/Suggestions</span>
          </a>
        </li>
    </section>
  </aside>
