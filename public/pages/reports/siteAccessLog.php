<?php
  require($_SERVER["DOCUMENT_ROOT"] . "/lib/loginHeader.php");
?>

<!DOCTYPE html>
<html>
<head>
  <?php
    $pageTitle='Site Access Log';
    $pageParent='Reports/Logs';
    include_once($_SERVER[ "DOCUMENT_ROOT"] . "/lib/header.php");
  ?>
  <script>
    function refresh_appLog() {
      $("#appLog").load(location.href + " #appLog");
    }
    setInterval('refresh_appLog()', 100000);
  </script>
</head>
<body class="hold-transition skin-<?php echo HEADER_COLOR ?> sidebar-mini">
<div class="wrapper">
  <?php
    include_once($_SERVER[ "DOCUMENT_ROOT"] . "/lib/sidebar.php");
  ?>
  <div class="content-wrapper">
    <section class="content-header">
      <h1>
        <?php echo $pageTitle ?>
      </h1>
      <small>Limit <?php echo APP_LOG_LIMIT ?> rows</small>
      <ol class="breadcrumb">
        <li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
        <?php if($pageParent) {echo "<li></i> " . $pageParent . "</li>";} ?>
        <li class="active"><?php echo $pageTitle ?></li>
      </ol>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-body">
              <table id="appLog" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th class="text-left">Date/Time</th>
                    <th class="text-left">Username</th>
                    <th class="text-left">Page Title</th>
                    <th class="text-left">IP</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                    echo getSiteAccessLog();
                  ?>
                </tfoot>
              </table>
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
