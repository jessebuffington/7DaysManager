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
  <body class="hold-transition skin-<?php echo $headerColor ?> sidebar-mini">
    <div class="wrapper">
      <?php
        include_once($_SERVER[ "DOCUMENT_ROOT"] . "/lib/sidebar.php");
      ?>
  <div class="content-wrapper">
    <section class="content-header">
      <h1>
        Blank page
        <small>it all starts here</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Examples</a></li>
        <li class="active">Blank page</li>
      </ol>
    </section>
    <section class="content">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Title</h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                    title="Collapse">
              <i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="box-body">
          Start creating your amazing application!
        </div>
        <div class="box-footer">
          Footer
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
