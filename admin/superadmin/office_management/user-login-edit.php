<?php

  require_once('../classes/authentication.php');
  include('../static_references/header.php');
  include('../static_references/search.php');
  include('../static_references/navbar.php');
  include_once('../classes/data_validation.php');
  include_once('../classes/data_clearence.php');
  include_once('../classes/branch_crud.php');
  include_once('../classes/admin_user_crud.php');

?>

<section class="content">
        <div class="container-fluid">


          <?php

            if(isset($_POST['submit'])){

          ?>
          <div class="row clearfix">
              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="body">

                      <?php

                          //declare classes for checking;
                          $validation = new Validation();
                          $clearence = new Clearence();
                          $user_operation = new UserCrudOperation();

                          $user_id = $_GET['b_id'];
                          $user_id = $clearence->escape_string($user_id);
                          $user_id = strip_tags(trim($user_id));
                          $user_id = htmlentities($user_id);

                          //mysql escape string clearence;
                          $password = $clearence->escape_string($_POST['password']);
                          $confirm_password = $clearence->escape_string($_POST['confirm_password']);

                          //input data triming;
                          $password = strip_tags(trim($password));
                          $confirm_password = strip_tags(trim($confirm_password));

                          // Escape any html characters;
                          $password = htmlentities($password);
                          $confirm_password = htmlentities($confirm_password);

                          //check refined and input values are empty and valid or not;
                          $msg = $validation->check_empty(array($password, $confirm_password));

                          if($msg != null){
                            ?>
                            <div class="alert bg-red">
                                <?php echo $msg; ?>
                            </div>
                            <?php

                          }else{

                            if($password == $confirm_password){

                              //sending all variables to branch_crud for creating new branch;
                              $update_user_password = $user_operation->update_password($password, $user_id, $usn_id);

                              if($update_user_password == 'Successfully changed password'){

                                ?>

                                <div class="alert bg-green">

                                    <?php echo 'Successfully changed password'; ?>

                                </div>

                                <?php

                              }else{

                                ?>

                                <div class="alert bg-red">

                                    <?php echo $update_user_password; ?>

                                </div>

                                <?php

                              }

                            }else{

                              ?>
                              <div class="alert bg-red">
                                  Password not matched. Please try again.
                              </div>
                              <?php

                            }

                          }

                      ?>

                    </div>
                  </div>
              </div>
            </div>

            <?php
              }

            ?>


<!-- Vertical Layout -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                                Edit User Password
                            </h2>

                        </div>
                        <div class="body">

                          <?php

                              //populating edit branch form;
                              $user_id = $_GET['b_id'];
                              $user_operation = new UserCrudOperation();
                              $clearence = new Clearence();

                              //data clearance;
                              $user_id = $clearence->escape_string($user_id);
                              $user_id = strip_tags(trim($user_id));
                              $user_id = htmlentities($user_id);

                          ?>

                            <form action="<?php echo $user_id; ?>" method="POST">
                                <label for="name">New Password</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="password" id="name" class="form-control" required aria-required="true" placeholder="Enter user's password here" name="password">
                                    </div>
                                </div>

                                <label for="name">Confirm Password</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="password" id="name" class="form-control" required aria-required="true" placeholder="Confirm Password" name="confirm_password">
                                    </div>
                                </div>

                                <br>
                                <input type="submit" name="submit" class="btn bg-deep-orange waves-effect m-t-15" value="Change Password">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
<!-- #END# Vertical Layout -->


</div>
</section>
<?php

  require_once('../static_references/footer.php');

?>
