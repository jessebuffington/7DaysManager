<?php
  require($_SERVER["DOCUMENT_ROOT"] . "/lib/loginHeader.php");
?>

<!DOCTYPE html>
<html>
<head>
  <?php
    $pageTitle='Site Settings';
    $pageParent='Site Settings';
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
          General Server Settings
        </h1>
        <ol class="breadcrumb">
          <li><a href="/"><i class="fa fa-dashboard"></i>Home</a></li>
          <li> Site Settings</li>
          <li class="active"><?php echo $pageTitle ?></li>
        </ol>
      </section>
    <section class="content">
      <div class="row">
        <div class="col-md-6">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Server Connection</h3>
            </div>
            <form role="form" name="serverSettings"  method="post" action="<?php settingsUpdateServerConnection();?>" onSubmit="return validation()">
              <?php $queryServerConnection = mysql_query("SELECT * FROM servers");
                while($queryServerConnection = mysql_fetch_array($queryServerConnection)) {
                  echo '<div class="box-body">';
                  echo '
                        <div class="form-group">
                          <label form="inputIP">IP/Hostname</label>';
                          if ($queryServerConnection['IP'] == NULL) {
                            echo '<input type="ip" class="form-control" id="inputIP" placeholder="133.133.133.133">';
                          }else{
                            echo '<input type="ip" class="form-control" id="inputIP" value="' . $queryServerConnection['IP'] . '">';
                          }
                  echo '</div>';
                  echo '
                        <div class="form-group">
                          <label form="inputPass">Password</label>';
                          if ($queryServerConnection['password'] == NULL) {
                            echo '<input type="password" class="form-control" id="inputPass" placeholder="***PASSWORD***">';
                          }else{
                            echo '<input type="password" class="form-control" id="inputPass" value="' . $queryServerConnection['password'] . '">';
                          }
                  echo '</div>';
                  echo '
                        <div class="form-group">
                          <label form="inputPort">Telnet Port</label>';
                          if ($queryServerConnection['telnetPort'] == NULL) {
                            echo '<input type="port" class="form-control" id="inputPort" placeholder="8081">';
                          }else{
                            echo '<input type="port" class="form-control" id="inputPort" value="' . $queryServerConnection['telnetPort'] . '">';
                          }
                  echo '</div>';
                  echo '
                        <div class="checkbox">
                          <label>';
                          if ($queryServerConnection['isEnabled'] == 1) {
                            echo '<input type="checkbox" checked> Enabled';
                          }else{
                            echo '<input type="checkbox"> Enabled';
                          }
                          echo '</label>';
                }
              ?>
              <button type="submit" name="submit" id="submit" value="Submit" class="btn btn-primary pull-right">Submit</button>
                            </div>
                </div>
            </form>
          </br>

            <div class="box-header with-border">
              <h3 class="box-title">Server List</h3>
              <div class="box-tools">
                <div class="input-group input-group-sm" style="width: 150px;">
                  <input type="text" name="table_search" class="form-control pull-right" placeholder="Search">
                  <div class="input-group-btn">
                    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                  </div>
                </div>
              </div>
            </div>
            <div class="box-body">
              <table id='metalDeploys' class='table table-bordered'>
                <thead>
                  <tr>
                    <th>Status</th>
                    <th>IP</th>
                    <th>Last Modified</th>
                  </tr>
                </thead>
                <tbody>
                  <?php $queryServers = mysql_query("SELECT * FROM servers");
                    while($queryServers = mysql_fetch_array($queryServers)) {
                      echo '<tr>';
                      if ($queryServers['isEnable'] == 1) {
                        echo '<td><span class="label label-success">Enabled</span></td>
                              <td>' . $queryServers['IP'] . '</td>
                              <td>' . $queryServers['dateUpdated'] . '</td>';
                      }elseif ($queryServers['isEnable'] == 0) {
                        echo '<td><span class="label label-danger">Disabled</span></td>
                              <td>' . $queryServers['IP'] . '</td>
                              <td>' . $queryServers['dateUpdated'] . '</td>';
                      }elseif ($queryServers['isEnable'] == NULL) {
                        echo 'No Data Found';
                      }
                    }
                  ?>

                </tbody>
              </table>
            </div>
          </div>

          <div class="box box-success">
            <div class="box-header with-border">
              <h3 class="box-title">Different Height</h3>
            </div>
            <div class="box-body">
              <input class="form-control input-lg" type="text" placeholder=".input-lg">
              <br>
              <input class="form-control" type="text" placeholder="Default input">
              <br>
              <input class="form-control input-sm" type="text" placeholder=".input-sm">
            </div>
          </div>
          <div class="box box-danger">
            <div class="box-header with-border">
              <h3 class="box-title">Different Width</h3>
            </div>
            <div class="box-body">
              <div class="row">
                <div class="col-xs-3">
                  <input type="text" class="form-control" placeholder=".col-xs-3">
                </div>
                <div class="col-xs-4">
                  <input type="text" class="form-control" placeholder=".col-xs-4">
                </div>
                <div class="col-xs-5">
                  <input type="text" class="form-control" placeholder=".col-xs-5">
                </div>
              </div>
            </div>
          </div>
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Input Addon</h3>
            </div>
            <div class="box-body">
              <div class="input-group">
                <span class="input-group-addon">@</span>
                <input type="text" class="form-control" placeholder="Username">
              </div>
              <br>
              <div class="input-group">
                <input type="text" class="form-control">
                <span class="input-group-addon">.00</span>
              </div>
              <br>
              <div class="input-group">
                <span class="input-group-addon">$</span>
                <input type="text" class="form-control">
                <span class="input-group-addon">.00</span>
              </div>
              <h4>With icons</h4>
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                <input type="email" class="form-control" placeholder="Email">
              </div>
              <br>
              <div class="input-group">
                <input type="text" class="form-control">
                <span class="input-group-addon"><i class="fa fa-check"></i></span>
              </div>
              <br>
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-dollar"></i></span>
                <input type="text" class="form-control">
                <span class="input-group-addon"><i class="fa fa-ambulance"></i></span>
              </div>
              <h4>With checkbox and radio inputs</h4>
              <div class="row">
                <div class="col-lg-6">
                  <div class="input-group">
                        <span class="input-group-addon">
                          <input type="checkbox">
                        </span>
                    <input type="text" class="form-control">
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="input-group">
                        <span class="input-group-addon">
                          <input type="radio">
                        </span>
                    <input type="text" class="form-control">
                  </div>
                </div>
              </div>
              <h4>With buttons</h4>
              <p class="margin">Large: <code>.input-group.input-group-lg</code></p>
              <div class="input-group input-group-lg">
                <div class="input-group-btn">
                  <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown">Action
                    <span class="fa fa-caret-down"></span></button>
                  <ul class="dropdown-menu">
                    <li><a href="#">Action</a></li>
                    <li><a href="#">Another action</a></li>
                    <li><a href="#">Something else here</a></li>
                    <li class="divider"></li>
                    <li><a href="#">Separated link</a></li>
                  </ul>
                </div>
                <input type="text" class="form-control">
              </div>
              <p class="margin">Normal</p>
              <div class="input-group">
                <div class="input-group-btn">
                  <button type="button" class="btn btn-danger">Action</button>
                </div>
                <input type="text" class="form-control">
              </div>
              <p class="margin">Small <code>.input-group.input-group-sm</code></p>
              <div class="input-group input-group-sm">
                <input type="text" class="form-control">
                    <span class="input-group-btn">
                      <button type="button" class="btn btn-info btn-flat">Go!</button>
                    </span>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Horizontal Form</h3>
            </div>
            <form class="form-horizontal">
              <div class="box-body">
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Email</label>
                  <div class="col-sm-10">
                    <input type="email" class="form-control" id="inputEmail3" placeholder="Email">
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputPassword3" class="col-sm-2 control-label">Password</label>
                  <div class="col-sm-10">
                    <input type="password" class="form-control" id="inputPassword3" placeholder="Password">
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-sm-offset-2 col-sm-10">
                    <div class="checkbox">
                      <label>
                        <input type="checkbox"> Remember me
                      </label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="box-footer">
                <button type="submit" class="btn btn-default">Cancel</button>
                <button type="submit" class="btn btn-info pull-right">Sign in</button>
              </div>
            </form>
          </div>
          <div class="box box-warning">
            <div class="box-header with-border">
              <h3 class="box-title">General Elements</h3>
            </div>
            <div class="box-body">
              <form role="form">
                <div class="form-group">
                  <label>Text</label>
                  <input type="text" class="form-control" placeholder="Enter ...">
                </div>
                <div class="form-group">
                  <label>Text Disabled</label>
                  <input type="text" class="form-control" placeholder="Enter ..." disabled>
                </div>
                <div class="form-group">
                  <label>Textarea</label>
                  <textarea class="form-control" rows="3" placeholder="Enter ..."></textarea>
                </div>
                <div class="form-group">
                  <label>Textarea Disabled</label>
                  <textarea class="form-control" rows="3" placeholder="Enter ..." disabled></textarea>
                </div>
                <div class="form-group has-success">
                  <label class="control-label" for="inputSuccess"><i class="fa fa-check"></i> Input with success</label>
                  <input type="text" class="form-control" id="inputSuccess" placeholder="Enter ...">
                  <span class="help-block">Help block with success</span>
                </div>
                <div class="form-group has-warning">
                  <label class="control-label" for="inputWarning"><i class="fa fa-bell-o"></i> Input with
                    warning</label>
                  <input type="text" class="form-control" id="inputWarning" placeholder="Enter ...">
                  <span class="help-block">Help block with warning</span>
                </div>
                <div class="form-group has-error">
                  <label class="control-label" for="inputError"><i class="fa fa-times-circle-o"></i> Input with
                    error</label>
                  <input type="text" class="form-control" id="inputError" placeholder="Enter ...">
                  <span class="help-block">Help block with error</span>
                </div>
                <div class="form-group">
                  <div class="checkbox">
                    <label>
                      <input type="checkbox">
                      Checkbox 1
                    </label>
                  </div>
                  <div class="checkbox">
                    <label>
                      <input type="checkbox">
                      Checkbox 2
                    </label>
                  </div>
                  <div class="checkbox">
                    <label>
                      <input type="checkbox" disabled>
                      Checkbox disabled
                    </label>
                  </div>
                </div>
                <div class="form-group">
                  <div class="radio">
                    <label>
                      <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked>
                      Option one is this and that&mdash;be sure to include why it's great
                    </label>
                  </div>
                  <div class="radio">
                    <label>
                      <input type="radio" name="optionsRadios" id="optionsRadios2" value="option2">
                      Option two can be something else and selecting it will deselect option one
                    </label>
                  </div>
                  <div class="radio">
                    <label>
                      <input type="radio" name="optionsRadios" id="optionsRadios3" value="option3" disabled>
                      Option three is disabled
                    </label>
                  </div>
                </div>
                <div class="form-group">
                  <label>Select</label>
                  <select class="form-control">
                    <option>option 1</option>
                    <option>option 2</option>
                    <option>option 3</option>
                    <option>option 4</option>
                    <option>option 5</option>
                  </select>
                </div>
                <div class="form-group">
                  <label>Select Disabled</label>
                  <select class="form-control" disabled>
                    <option>option 1</option>
                    <option>option 2</option>
                    <option>option 3</option>
                    <option>option 4</option>
                    <option>option 5</option>
                  </select>
                </div>
                <div class="form-group">
                  <label>Select Multiple</label>
                  <select multiple class="form-control">
                    <option>option 1</option>
                    <option>option 2</option>
                    <option>option 3</option>
                    <option>option 4</option>
                    <option>option 5</option>
                  </select>
                </div>
                <div class="form-group">
                  <label>Select Multiple Disabled</label>
                  <select multiple class="form-control" disabled>
                    <option>option 1</option>
                    <option>option 2</option>
                    <option>option 3</option>
                    <option>option 4</option>
                    <option>option 5</option>
                  </select>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
  <?php
    include_once($_SERVER[ "DOCUMENT_ROOT"] . "/lib/footer.php");
  ?>
</body>
</html>
