<html>
  <body>
    <head>
      <meta charset="UTF-8">
      <link rel='stylesheet prefetch' href='/css/bootstrap.css'>
      <link rel='stylesheet prefetch' href='/css/mdb.min.css'>
      <link rel='stylesheet prefetch' href='/css/font-awesome.css'>
      <link rel="stylesheet" href="/css/main.css">
      <?php
        define ( '$pageTitle','Home' );
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
      <main>
         <div class="container">
            <div class="row wow fadeIn" data-wow-delay="0.2s">
               <div class="col-lg-7">
                  <div class="view overlay hm-white-light z-depth-1-half">
                     <img src="http://mdbootstrap.com/img/Photos/Slides/img%20%2877%29.jpg" class="img-fluid " alt="">
                     <div class="mask">
                     </div>
                  </div>
                  <br>
               </div>
               <div class="col-lg-5">
                  <h2 class="h2-responsive">We are professionals</h2>
                  <hr>
                  <p>Leverage agile frameworks to provide a robust synopsis for high level overviews. Iterative approaches to corporate strategy foster collaborative thinking to further the overall value proposition.</p>
                  <a href="" class="btn btn-info">Contact</a>
               </div>
            </div>
            <hr class="extra-margins">
            <div class="row">
               <div class="col-lg-4">
                  <div class="card wow fadeIn" data-wow-delay="0.4s">
                     <div class="view overlay hm-white-slight">
                        <img src="http://mdbootstrap.com/img/Photos/Horizontal/Work/4-col/img%20(37).jpg" class="img-fluid" alt="">
                        <a href="#">
                           <div class="mask"></div>
                        </a>
                     </div>
                     <div class="card-block">
                        <h4 class="card-title">Content Managing</h4>
                        <p class="card-text">User generated content in real-time will have multiple touchpoints for offshoring.</p>
                        <a href="#" class="btn btn-info">Read more</a>
                     </div>
                  </div>
               </div>
               <div class="col-lg-4">
                  <div class="card wow fadeIn" data-wow-delay="0.6s">
                     <div class="view overlay hm-white-slight">
                        <img src="http://mdbootstrap.com/img/Photos/Horizontal/Work/4-col/img%20(21).jpg" class="img-fluid" alt="">
                        <a href="#">
                           <div class="mask"></div>
                        </a>
                     </div>
                     <div class="card-block">
                        <h4 class="card-title">Startegy Planning</h4>
                        <p class="card-text">Bring to the table win-win survival strategies to ensure proactive domination.</p>
                        <a href="#" class="btn btn-info">Read more</a>
                     </div>
                  </div>
               </div>
               <div class="col-lg-4">
                  <div class="card wow fadeIn" data-wow-delay="0.8s">
                     <div class="view overlay hm-white-slight">
                        <img src="http://mdbootstrap.com/img/Photos/Horizontal/Work/4-col/img%20(12).jpg" class="img-fluid" alt="">
                        <a href="#">
                           <div class="mask"></div>
                        </a>
                     </div>
                     <div class="card-block">
                        <h4 class="card-title">Cloud Solutions</h4>
                        <p class="card-text">A new normal that has X is on the heading towards a streamlined cloud solution.</p>
                        <a href="#" class="btn btn-info">Read more</a>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </main>
      <?php
        include_once($_SERVER["DOCUMENT_ROOT"] . "/lib/footer.php");
      ?>
   </body>
</html>
