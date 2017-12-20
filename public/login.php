<!DOCTYPE html>
<html>
  <head>
    <?php
      $pageTitle='Login';
      include_once($_SERVER[ "DOCUMENT_ROOT"] . "/lib/header.php");
      include_once($_SERVER[ "DOCUMENT_ROOT"] . "/includes/config.php");
      include_once($_SERVER[ "DOCUMENT_ROOT"] . "/includes/functions.php");
    ?>
  </head>
  <body class="hold-transition login-page">
    <div class="login-box">
      <div class="login-logo">
        <a href="/"><b><?php echo SITE_NAME; ?></b></a>
      </div>
      <div class="login-box-body">
        <p class="login-box-msg">Please sign in below.</p>
        <form action="/lib/checkLogin.php" method="post">
          <div class="form-group has-feedback">
            <input name="loginUsername" id="loginPassword" type="email" class="form-control" placeholder="Email">
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <input name="loginPassword" id="loginPassword" type="password" class="form-control" placeholder="Password">
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>
          <div class="row">
            <div class="col-xs-12">
              <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
            </div>
          </div>
        </form>
      </div>
    </div>

    <script src="/bower_components/jquery/dist/jquery.min.js"></script>
    <script src="/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

  </body>
</html>
