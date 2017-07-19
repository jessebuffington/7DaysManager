<!DOCTYPE html>
<html >
  <head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="/css/login.css">
    <link rel="stylesheet" href="/css/main.css">
    <?php
      include($_SERVER["DOCUMENT_ROOT"] . "/lib/header.php");
      define ( 'pageTitle','Login' );
    ?>
    <?php
      include($_SERVER["DOCUMENT_ROOT"] . "/includes/dbConfig.php");
     ?>
  </head>
  <body>
    <div class="table-title">
      <h3><?php echo $pageTitle;?></h3>
    </div>
    <div class="login-page">
      <div class="form">
        <form class="register-form" action="/lib/register.php">
          <input type="text" placeholder="name"/>
          <input type="password" placeholder="password"/>
          <input type="text" placeholder="email address"/>
          <button>Register!</button>
          <p class="message">Already registered? <a href="#">Sign In!</a></p>
        </form>
        <form class="login-form" action="/lib/processLogin.php">
          <input type="text" id="user" name="user" placeholder="username"/>
          <input type="password" id="pass" name="pass" placeholder="password"/>
          <button>Login!</button>
          <p class="message">Not registered? <a href="#">Create an account!</a></p>
        </form>
      </div>
    </div>
    <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
    <script src="/js/login.js"></script>
  </body>
</html>
