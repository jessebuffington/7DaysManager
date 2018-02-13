<?php
  require($_SERVER["DOCUMENT_ROOT"] . "/lib/loginHeader.php");
?>

<!DOCTYPE html>
<html>
  <head>
    <?php
      $pageTitle='Web Console';
      $pageParent='Utilities';
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
        <?php echo $pageTitle ?>
      </h1>
      <ol class="breadcrumb">
        <li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
        <?php if($pageParent) {echo "<li></i> " . $pageParent . "</li>";} ?>
        <li class="active"><?php echo $pageTitle ?></li>
      </ol>
    </section>
    <section class="content">
      <div class="box">
        <div class="box-body">
          <div id="fTelnetContainer"></div>
          <!-- Looks weird, but this ensures your users always get the latest version of fTelnet and not an old version from their cache -->
          <script>
            document.write('<script src="//embed.ftelnet.ca/js/ftelnet-loader.js?v=' + (new Date()).getTime() + '"><\/script>');
          </script>
          <script>
            fTelnet.Hostname = "7dm.bcomps.net";
            fTelnet.Port = 18081;
            fTelnet.ConnectionType = "telnet";
            fTelnet.Emulation = "ansi-bbs";
            fTelnet.VirtualKeyboardVisible = false;
            fTelnet.Init();
          </script>
        </div>
      </div>
    </section>
  </div>
  <?php
    include_once($_SERVER[ "DOCUMENT_ROOT"] . "/lib/footer.php");
  ?>
<script>
  $(document).ready(function () {
    $('.sidebar-menu').tree()
  })
</script>
</body>
</html>
