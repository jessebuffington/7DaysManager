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
  include($_SERVER["DOCUMENT_ROOT"] . "/includes/dbConfig.php");

  $miCounter = 1;

  $res = mysql_query("SELECT * FROM site_navbar_main where isActive = '1' order by m_menu_id asc");
  if (!$res) {
    die('Invalid query: ' . mysql_error());
  }

  echo '<nav class="navbar navbar-toggleable-md navbar-dark">';
  echo '<div class="container">';
  echo '<button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarNav1" aria-controls="navbarNav1" aria-expanded="false" aria-label="Toggle navigation">';
  echo '<span class="navbar-toggler-icon"></span>';
  echo '</button>';
  echo '<a class="navbar-brand" href="/index.php">';
  echo '<strong>7DaysManager</strong>';
  echo '</a>';
  echo '<div class="collapse navbar-collapse" id="navbarNav1">';
  echo '<ul class="navbar-nav mr-auto">';

  while($row = mysql_fetch_array($res)) {
    if ($row['isParent'] == 0){
      echo '<li class="nav-item"><a class="nav-link" href="' . $row['m_menu_link'] . '">' . $row['m_menu_name'] . '</a></li>';
    }
    if ($row['isParent'] == 1) {
      $res_pro = mysql_query("SELECT * FROM site_navbar_sub WHERE isActive = '1' and m_menu_id = '" . $row['m_menu_id'] . "' order by s_menu_id");
      if (!$res_pro) {
        //echo '</ul>';
        die('Invalid query: ' . mysql_error());
      }
      echo '<li class="nav-item dropdown btn-group"><a class="nav-link dropdown-toggle" id="dropdownMenu' . $row['m_menu_id'] . '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" href="' . $row['m_menu_link'] . '">' . $row['m_menu_name'] . '</a><div class="dropdown-menu dropdown" aria-labelledby="dropdownMenu' . $row['m_menu_id'] . '">';

      //echo '<a class="dropdown-item" href="' . $row['s_menu_link'] . '">' . $row['s_menu_name'] . '</a>';
      while($pro_row = mysql_fetch_array($res_pro)) {
        echo '<a class="dropdown-item" href="' . $pro_row['s_menu_link'] . '">' . $pro_row['s_menu_name'] . '</a>';
      }
      echo '</div></li>';
    } else {
    }
  }
  echo '</ul>';
  //mysql_close();

    // Add search feature
    echo '<form class="form-inline waves-effect waves-light">';
    echo '<input class="form-control" type="text" placeholder="Search">';
    echo '</form>';

    echo '</div>';
    echo '</div>';
    echo '</nav>';
?>
