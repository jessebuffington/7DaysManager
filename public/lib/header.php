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

<?php
  include($_SERVER["DOCUMENT_ROOT"] . "/includes/config.php");
  include($_SERVER["DOCUMENT_ROOT"] . "/includes/functions.php");


  if(isset($pageTitle)){
    echo '<title>' . $pageTitle . ' | ' . SITE_NAME . '</title>';
  } else {
    echo '<title>';
    echo SITE_NAME;
    echo '</title>';
  }
  if (!isset($pageParent)) $pageParent = '';
?>



  <link rel="shortcut icon" href="/lib/img/favicon.ico" type="image/x-icon">
  <link rel="icon" href="/lib/img/favicon.ico" type="image/png" />
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <header class="main-header">

  <?php
  if (!($pageTitle == 'Login' || $pageTitle == 'Register')) {
    echo '<a href="/" class="logo">
          <span class="logo-mini">' . SITE_NAME_SHORT . '</span>
           <span class="logo-lg"><b>' . SITE_NAME . '</b></span>
        </a>
        <nav class="navbar navbar-static-top">
          <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
          </a>
          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
              <li class="dropdown">
                <a href="/lib/logout.php" class="btn-danger">Logout</a>
              </li>
            </ul>
          </div>
        </nav>
      </header>';
    }
  ?>

  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="/bower_components/bootstrap/dist/css/bootstrap.css">
  <link rel="stylesheet" href="/bower_components/font-awesome/css/font-awesome.css">
  <link rel="stylesheet" href="/bower_components/Ionicons/css/ionicons.css">
  <link rel="stylesheet" href="/bower_components/bootstrap-daterangepicker/daterangepicker.css">
  <link rel="stylesheet" href="/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  <link rel="stylesheet" href="/plugins/iCheck/all.css">
  <link rel="stylesheet" href="/bower_components/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css">
  <link rel="stylesheet" href="/plugins/timepicker/bootstrap-timepicker.min.css">
  <link rel="stylesheet" href="/bower_components/select2/dist/css/select2.min.css">
  <link rel="stylesheet" href="/bower_components/jvectormap/jquery-jvectormap.css">
  <link rel="stylesheet" href="/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
  <link rel="stylesheet" href="/dist/css/AdminLTE.css">
  <link rel="stylesheet" href="/lib/css/opslinks.css">
  <link rel="stylesheet" href="/dist/css/skins/_all-skins.css">

  <link rel="stylesheet" href="/lib/css/google.css">

  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
