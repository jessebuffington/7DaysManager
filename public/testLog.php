<?php
if (isset($_GET['ajax'])) {
  session_start();
  $handle = fopen('/home/steam/Steam/servers/7days/log/server/output_log__2018-01-03__04-00-36.txt', 'r');
  if (isset($_SESSION['offset'])) {
    $data = stream_get_contents($handle, -1, $_SESSION['offset']);
    echo nl2br($data);
  } else {
    fseek($handle, 0, SEEK_END);
    $_SESSION['offset'] = ftell($handle);
  }
  exit();
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <script src="http://code.jquery.com/jquery-1.8.2.min.js"></script>
  <script src="http://creativecouple.github.com/jquery-timing/jquery-timing.min.js"></script>
  <script>
  $(function() {
    $.repeat(1000, function() {
      $.get('testLog.php?ajax', function(data) {
        $('#testLog').append(data);
      });
    });
  });
  </script>
</head>
<body>
  <div id="tail">Starting up...</div>
</body>
</html>
