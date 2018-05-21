<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Power Line Bangladesh Tracker System</title>
    <!-- Favicon-->
    <link rel="icon" href="/static/favicon.ico" type="image/x-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

    <!-- Bootstrap Core Css -->
    <link href="/powerlinebd/static/plugins/bootstrap/css/bootstrap.css" rel="stylesheet">

    <!-- Waves Effect Css -->
    <link href="/powerlinebd/static/plugins/node-waves/waves.css" rel="stylesheet" />

    <!-- Animation Css -->
    <link href="/powerlinebd/static/plugins/animate-css/animate.css" rel="stylesheet" />

    <!-- Custom Css -->
    <link href="/powerlinebd/static/css/style.css" rel="stylesheet">
</head>

<body class="login-page">
    <div class="login-box">
        <div class="logo">
            <a href="javascript:void(0);">PowerLine<b>Bangladesh</b></a>
            <small>Only for Power Line Bangladesh Employees</small>
        </div>
        <div class="card">
            <div class="body">
                <form id="sign_in" action="signin" method="POST">
                    <div class="msg">Please Sign in</div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">email</i>
                        </span>
                        <div class="form-line">
                            <input type="email" class="form-control" name="email_address" placeholder="Email" value="<?php if(isset($_COOKIE["member_email"])) { echo $_COOKIE["member_email"]; } ?>" required autofocus>
                        </div>
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">lock</i>
                        </span>
                        <div class="form-line">
                            <input type="password" class="form-control" name="password" placeholder="Password" value="<?php if(isset($_COOKIE["member_password"])) { echo $_COOKIE["member_password"]; } ?>" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-8 p-t-5">
                            <input type="checkbox" name="rememberme" id="rememberme" class="filled-in chk-col-deep-orange">
                            <label for="rememberme">Remember Me</label>
                        </div>
                        <div class="col-xs-4">
                            <input class="btn btn-block bg-deep-orange waves-effect" type="submit" name="submit" value="Log in">
                        </div>
                    </div>
                    <div class="row m-t-15 m-b--20">
                        <div class="col-xs-6">
                            <a href="forgot-password.html">Forgot Password?</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>


        <?php if(isset($_POST['submit'])){
          ?>
          <div class="card">
              <div class="body">
                <?php

                  include_once('super_classes/data_validation_signin.php');
                  include_once('super_classes/data_clearence_signin.php');
                  include_once('super_classes/login_class.php');

                  $validation = new Validation();
                  $clearence = new Clearence();
                  $loginin = new LoginOperation();

                  //mysql escape string;
                  $email_address = $clearence->escape_string($_POST['email_address']);
                  $password = $clearence->escape_string($_POST['password']);
                  $remember_me = isset($_POST['rememberme']);

                  //input data triming;
                  $email_address = strip_tags(trim($email_address));
                  $password = strip_tags(trim($password));

                  // Escape any html characters;
                  $email_address = htmlentities($email_address);
                  $password = htmlentities($password);

                  //check refined and input values are empty and valid or not;
                  $msg = $validation->check_empty(array($email_address, $password));
                  $check_email = $validation->is_email_valid($email_address);



                  if($msg != null){
                    ?>
                    <div class="alert bg-red">
                        <?php echo $msg; ?>
                    </div>
                    <?php

                  }elseif (!$check_email) {
                    ?>
                    <div class="alert bg-red">
                        <?php echo 'email is not valid'; ?>
                    </div>
                    <?php
                  }else{

                    $new_login = $loginin->do_login($email_address, $password, $remember_me);
                    //$new_login = $loginin->password_match($email_address, $password);
                    echo $new_login;

                  }

                ?>
              </div>
          </div>
          <?php
        } ?>





    </div>

    <!-- Jquery Core Js -->
    <script src="/powerlinebd/static/plugins/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core Js -->
    <script src="/powerlinebd/static/plugins/bootstrap/js/bootstrap.js"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="/powerlinebd/static/plugins/node-waves/waves.js"></script>

    <!-- Validation Plugin Js -->
    <script src="/powerlinebd/static/plugins/jquery-validation/jquery.validate.js"></script>

    <!-- Custom Js -->
    <script src="/powerlinebd/static/js/admin.js"></script>
    <script src="/powerlinebd/static/js/pages/examples/sign-in.js"></script>
</body>

</html>
