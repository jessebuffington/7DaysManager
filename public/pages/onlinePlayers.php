<?php
  require($_SERVER["DOCUMENT_ROOT"] . "/lib/loginHeader.php");
?>

<!DOCTYPE html>
<html>
<head>
  <?php
    $pageTitle='Online Players';
    $pageParent='Players';
    include_once($_SERVER[ "DOCUMENT_ROOT"] . "/lib/header.php");
  ?>
</head>
<body class="hold-transition skin-<?php echo HEADER_COLOR ?> sidebar-mini">
<div class="wrapper">
  <?php
    include_once($_SERVER[ "DOCUMENT_ROOT"] . "/lib/sidebar.php");
  ?>
  <div class="content-wrapper">
    <section class="content-header">
      <h1>
        Online Players
        <small>
          Version <?php echo SITE_VERSION;?>
        </small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
        <li>Players</li>
        <li class="active"><?php echo $pageTitle ?></li>
      </ol>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped">
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
                <tbody>
                  <?php
                    echo getOnlinePlayers_List();
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
<script>
  $(function () {
    $('#example1').DataTable()
    $('#example2').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : false,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : true
    })
  })
</script>
</body>
</html>
