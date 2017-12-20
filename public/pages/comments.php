<?php
  require($_SERVER["DOCUMENT_ROOT"] . "/lib/loginHeader.php");
?>

<!DOCTYPE html>
<html>

<head>
  <?php
    $pageTitle='Comments/Suggestions';
    $pageParent = NULL;
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
          Comments/Suggestions
        </h1>
        <ol class="breadcrumb">
          <li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
          <li class="active">Comments/Suggestions</li>
        </ol>
      </section>
      <section class="content">
        <div class="error-page">
          <div class="box box-body box-info">
            <div class="box-header with-border text-center">
              <h3 class="box-title">Do you have a comment or a suggestion? Submit one below!</h3>
            </div>
            <form role="form" name="comment" method="post" action="<?php insertComment();?>" onSubmit="return validation()">
              <div class="box-body">
                <div class="form-group text-center">
                  <label>How would you rate this site?</label>
                  <br />
                  <br />
                  <font size = "2">
                    Crap &nbsp;
                    1 <input type="radio" name="rating" value="1">
                    2 <input type="radio" name="rating" value="2">
    								3 <input type="radio" name="rating" value="3">
                    4 <input type="radio" name="rating" value="4">
                    5 <input type="radio" name="rating" value="5" checked>
                    &nbsp; Awesome
                  </font>
                </div>
              </div>
              <div class="form-group">
                <label>Name</label>
                <input type="text" class="form-control" name="userName" id="userName" placeholder="Don't have to">
              </div>
              <div class="form-group">
                <label>Comment</label>
                <textarea class="form-control" name="comment" id="userName" rows="3" cols="40" placeholder="Comment here plz!" required></textarea>
              </div>
            </div>
            <div class="box-footer">
              <button type="submit" name="submit" id="submit" value="Submit" class="btn btn-primary">Submit</button>
            </div>
          </form>
        </div>
        <br />
        <br />
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
