<?php
  require($_SERVER["DOCUMENT_ROOT"] . "/lib/loginHeader.php");
?>

<!DOCTYPE html>
<html>
  <head>
    <?php
      $pageTitle='*500 - SERVER ERROR*';
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
          <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
          <li class="active"><?php echo $pageTitle ?></li>
        </ol>
      </section>
      <section class="content">
        <div class="error-page">
          <h2 class="headline text-red">500</h2>
          <div class="error-content">
            <h3><i class="fa fa-warning text-red"></i> Oops! Something went wrong.</h3>
            <p>
              We will work on fixing that right away.
              Meanwhile, you may <a href="/index.php">return to dashboard</a> or try using the search form.
            </p>
            <form class="search-form">
              <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Search">
                <div class="input-group-btn">
                  <button type="submit" name="submit" class="btn btn-danger btn-flat"><i class="fa fa-search"></i>
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </section>
    </div>
    <?php
      include_once($_SERVER[ "DOCUMENT_ROOT"] . "/lib/footer.php");
    ?>
  </body>
</html>
