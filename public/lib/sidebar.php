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
//       :YYiii$$$$$$$YYYYYYY$$$$YY$$$$YYiiiiiYYYYYYi';
//
//          XOXO
//  		Jesse B.
//



  echo '<!-- Left side column. contains the logo and sidebar -->';
  echo '<aside class="main-sidebar">';
  echo '  <!-- sidebar: style can be found in sidebar.less -->';
  echo '  <section class="sidebar">';
  echo '    <!-- Sidebar user panel -->';
  echo '    <div class="user-panel">';
  echo '      <div class="pull-left image">';
  echo '        <img src="/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">';
  echo '      </div>';
  echo '      <div class="pull-left info">';
  echo '        <p>Alexander Pierce</p>';
  echo '        <a href="#"><i class="fa fa-circle text-success"></i> Online</a>';
  echo '      </div>';
  echo '    </div>';
  echo '    <!-- search form -->';
  echo '    <form action="#" method="get" class="sidebar-form">';
  echo '      <div class="input-group">';
  echo '        <input type="text" name="q" class="form-control" placeholder="Search...">';
  echo '        <span class="input-group-btn">';
  echo '              <button type="submit" name="search" id="search-btn" class="btn btn-flat">';
  echo '                <i class="fa fa-search"></i>';
  echo '              </button>';
  echo '            </span>';
  echo '      </div>';
  echo '    </form>';
  echo '    <!-- /.search form -->';
  echo '    <!-- sidebar menu: : style can be found in sidebar.less -->';
  echo '    <ul class="sidebar-menu" data-widget="tree">';
  echo '      <li class="header">MAIN NAVIGATION</li>';
  echo '      <li class="active treeview menu-open">';
  echo '        <a href="#">';
  echo '          <i class="fa fa-dashboard"></i> <span>Dashboard</span>';
  echo '          <span class="pull-right-container">';
  echo '            <i class="fa fa-angle-left pull-right"></i>';
  echo '          </span>';
  echo '        </a>';
  echo '        <ul class="treeview-menu">';
  echo '          <li><a href="index.html"><i class="fa fa-circle-o"></i> Dashboard v1</a></li>';
  echo '          <li class="active"><a href="index2.html"><i class="fa fa-circle-o"></i> Dashboard v2</a></li>';
  echo '        </ul>';
  echo '      </li>';
  echo '      <li class="treeview">';
  echo '        <a href="#">';
  echo '          <i class="fa fa-files-o"></i>';
  echo '          <span>Layout Options</span>';
  echo '          <span class="pull-right-container">';
  echo '            <span class="label label-primary pull-right">4</span>';
  echo '          </span>';
  echo '        </a>';
  echo '        <ul class="treeview-menu">';
  echo '          <li><a href="pages/layout/top-nav.html"><i class="fa fa-circle-o"></i> Top Navigation</a></li>';
  echo '          <li><a href="pages/layout/boxed.html"><i class="fa fa-circle-o"></i> Boxed</a></li>';
  echo '          <li><a href="pages/layout/fixed.html"><i class="fa fa-circle-o"></i> Fixed</a></li>';
  echo '          <li><a href="pages/layout/collapsed-sidebar.html"><i class="fa fa-circle-o"></i> Collapsed Sidebar</a></li>';
  echo '        </ul>';
  echo '      </li>';
  echo '      <li>';
  echo '        <a href="pages/widgets.html">';
  echo '          <i class="fa fa-th"></i> <span>Widgets</span>';
  echo '          <span class="pull-right-container">';
  echo '            <small class="label pull-right bg-green">new</small>';
  echo '          </span>';
  echo '        </a>';
  echo '      </li>';
  echo '      <li class="treeview">';
  echo '        <a href="#">';
  echo '          <i class="fa fa-pie-chart"></i>';
  echo '          <span>Charts</span>';
  echo '          <span class="pull-right-container">';
  echo '            <i class="fa fa-angle-left pull-right"></i>';
  echo '          </span>';
  echo '        </a>';
  echo '        <ul class="treeview-menu">';
  echo '          <li><a href="pages/charts/chartjs.html"><i class="fa fa-circle-o"></i> ChartJS</a></li>';
  echo '          <li><a href="pages/charts/morris.html"><i class="fa fa-circle-o"></i> Morris</a></li>';
  echo '          <li><a href="pages/charts/flot.html"><i class="fa fa-circle-o"></i> Flot</a></li>';
  echo '          <li><a href="pages/charts/inline.html"><i class="fa fa-circle-o"></i> Inline charts</a></li>';
  echo '        </ul>';
  echo '      </li>';
  echo '      <li class="treeview">';
  echo '        <a href="#">';
  echo '          <i class="fa fa-laptop"></i>';
  echo '          <span>UI Elements</span>';
  echo '          <span class="pull-right-container">';
  echo '            <i class="fa fa-angle-left pull-right"></i>';
  echo '          </span>';
  echo '        </a>';
  echo '        <ul class="treeview-menu">';
  echo '          <li><a href="pages/UI/general.html"><i class="fa fa-circle-o"></i> General</a></li>';
  echo '          <li><a href="pages/UI/icons.html"><i class="fa fa-circle-o"></i> Icons</a></li>';
  echo '          <li><a href="pages/UI/buttons.html"><i class="fa fa-circle-o"></i> Buttons</a></li>';
  echo '          <li><a href="pages/UI/sliders.html"><i class="fa fa-circle-o"></i> Sliders</a></li>';
  echo '          <li><a href="pages/UI/timeline.html"><i class="fa fa-circle-o"></i> Timeline</a></li>';
  echo '          <li><a href="pages/UI/modals.html"><i class="fa fa-circle-o"></i> Modals</a></li>';
  echo '        </ul>';
  echo '      </li>';
  echo '      <li class="treeview">';
  echo '        <a href="#">';
  echo '          <i class="fa fa-edit"></i> <span>Forms</span>';
  echo '          <span class="pull-right-container">';
  echo '            <i class="fa fa-angle-left pull-right"></i>';
  echo '          </span>';
  echo '        </a>';
  echo '        <ul class="treeview-menu">';
  echo '          <li><a href="pages/forms/general.html"><i class="fa fa-circle-o"></i> General Elements</a></li>';
  echo '          <li><a href="pages/forms/advanced.html"><i class="fa fa-circle-o"></i> Advanced Elements</a></li>';
  echo '          <li><a href="pages/forms/editors.html"><i class="fa fa-circle-o"></i> Editors</a></li>';
  echo '        </ul>';
  echo '      </li>';
  echo '      <li class="treeview">';
  echo '        <a href="#">';
  echo '          <i class="fa fa-table"></i> <span>Tables</span>';
  echo '          <span class="pull-right-container">';
  echo '            <i class="fa fa-angle-left pull-right"></i>';
  echo '          </span>';
  echo '        </a>';
  echo '        <ul class="treeview-menu">';
  echo '          <li><a href="pages/tables/simple.html"><i class="fa fa-circle-o"></i> Simple tables</a></li>';
  echo '          <li><a href="pages/tables/data.html"><i class="fa fa-circle-o"></i> Data tables</a></li>';
  echo '        </ul>';
  echo '      </li>';
  echo '      <li>';
  echo '        <a href="pages/calendar.html">';
  echo '          <i class="fa fa-calendar"></i> <span>Calendar</span>';
  echo '          <span class="pull-right-container">';
  echo '            <small class="label pull-right bg-red">3</small>';
  echo '            <small class="label pull-right bg-blue">17</small>';
  echo '          </span>';
  echo '        </a>';
  echo '      </li>';
  echo '      <li>';
  echo '        <a href="pages/mailbox/mailbox.html">';
  echo '          <i class="fa fa-envelope"></i> <span>Mailbox</span>';
  echo '          <span class="pull-right-container">';
  echo '            <small class="label pull-right bg-yellow">12</small>';
  echo '            <small class="label pull-right bg-green">16</small>';
  echo '            <small class="label pull-right bg-red">5</small>';
  echo '          </span>';
  echo '        </a>';
  echo '      </li>';
  echo '      <li class="treeview">';
  echo '        <a href="#">';
  echo '          <i class="fa fa-folder"></i> <span>Examples</span>';
  echo '          <span class="pull-right-container">';
  echo '            <i class="fa fa-angle-left pull-right"></i>';
  echo '          </span>';
  echo '        </a>';
  echo '        <ul class="treeview-menu">';
  echo '          <li><a href="pages/examples/invoice.html"><i class="fa fa-circle-o"></i> Invoice</a></li>';
  echo '          <li><a href="pages/examples/profile.html"><i class="fa fa-circle-o"></i> Profile</a></li>';
  echo '          <li><a href="pages/examples/login.html"><i class="fa fa-circle-o"></i> Login</a></li>';
  echo '          <li><a href="pages/examples/register.html"><i class="fa fa-circle-o"></i> Register</a></li>';
  echo '          <li><a href="pages/examples/lockscreen.html"><i class="fa fa-circle-o"></i> Lockscreen</a></li>';
  echo '          <li><a href="pages/examples/404.html"><i class="fa fa-circle-o"></i> 404 Error</a></li>';
  echo '          <li><a href="pages/examples/500.html"><i class="fa fa-circle-o"></i> 500 Error</a></li>';
  echo '          <li><a href="pages/examples/blank.html"><i class="fa fa-circle-o"></i> Blank Page</a></li>';
  echo '          <li><a href="pages/examples/pace.html"><i class="fa fa-circle-o"></i> Pace Page</a></li>';
  echo '        </ul>';
  echo '      </li>';
  echo '      <li class="treeview">';
  echo '        <a href="#">';
  echo '          <i class="fa fa-share"></i> <span>Multilevel</span>';
  echo '          <span class="pull-right-container">';
  echo '            <i class="fa fa-angle-left pull-right"></i>';
  echo '          </span>';
  echo '        </a>';
  echo '        <ul class="treeview-menu">';
  echo '          <li><a href="#"><i class="fa fa-circle-o"></i> Level One</a></li>';
  echo '          <li class="treeview">';
  echo '            <a href="#"><i class="fa fa-circle-o"></i> Level One';
  echo '              <span class="pull-right-container">';
  echo '                <i class="fa fa-angle-left pull-right"></i>';
  echo '              </span>';
  echo '            </a>';
  echo '            <ul class="treeview-menu">';
  echo '              <li><a href="#"><i class="fa fa-circle-o"></i> Level Two</a></li>';
  echo '              <li class="treeview">';
  echo '                <a href="#"><i class="fa fa-circle-o"></i> Level Two';
  echo '                  <span class="pull-right-container">';
  echo '                    <i class="fa fa-angle-left pull-right"></i>';
  echo '                  </span>';
  echo '                </a>';
  echo '                <ul class="treeview-menu">';
  echo '                  <li><a href="#"><i class="fa fa-circle-o"></i> Level Three</a></li>';
  echo '                  <li><a href="#"><i class="fa fa-circle-o"></i> Level Three</a></li>';
  echo '                </ul>';
  echo '              </li>';
  echo '            </ul>';
  echo '          </li>';
  echo '          <li><a href="#"><i class="fa fa-circle-o"></i> Level One</a></li>';
  echo '        </ul>';
  echo '      </li>';
  echo '      <li><a href="https://adminlte.io/docs"><i class="fa fa-book"></i> <span>Documentation</span></a></li>';
  echo '      <li class="header">LABELS</li>';
  echo '      <li><a href="#"><i class="fa fa-circle-o text-red"></i> <span>Important</span></a></li>';
  echo '      <li><a href="#"><i class="fa fa-circle-o text-yellow"></i> <span>Warning</span></a></li>';
  echo '      <li><a href="#"><i class="fa fa-circle-o text-aqua"></i> <span>Information</span></a></li>';
  echo '    </ul>';
  echo '  </section>';
  echo '  <!-- /.sidebar -->';
  echo '</aside>';
?>
