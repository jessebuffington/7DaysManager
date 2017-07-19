<html>
  <body>
    <head>
      <meta charset="UTF-8">
      <link rel='stylesheet prefetch' href='/css/bootstrap.css'>
      <link rel='stylesheet prefetch' href='/css/mdb.min.css'>
      <link rel='stylesheet prefetch' href='/css/font-awesome.css'>
      <link rel="stylesheet" href="/css/main.css">
      <?php
        define ( 'pageTitle','Banned Users' );
        include_once($_SERVER["DOCUMENT_ROOT"] . "/lib/header.php");
      ?>
    </head>
    <header>
      <?php
        include_once($_SERVER["DOCUMENT_ROOT"] . "/lib/navbar.php");
      ?>
    </header>
    <div class="table-title">
      <h3><?php echo $pageTitle;?></h3>
    </div>
    <div class="table-title">
    <h3>Dummy Data Yo!</h3>
    </div>
      </thead>
    <!--Footer-->
    <?php
      include_once($_SERVER["DOCUMENT_ROOT"] . "/lib/footer.php");
    ?>
  </body>
</html>
