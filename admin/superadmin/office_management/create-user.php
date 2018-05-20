<?php

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

                          //mysql escape string clearence;
                          $user_name = $clearence->escape_string($_POST['user_name']);
                          $email_address = $clearence->escape_string($_POST['email_address']);
                          $contact_number = $clearence->escape_string($_POST['contact_number']);
                          $designation = $clearence->escape_string($_POST['designation']);
                          $department = $clearence->escape_string($_POST['department']);
                          $gender = $clearence->escape_string($_POST['gender']);
                          $branch_id = $clearence->escape_string($_POST['branch_id']);
                          $user_role = $clearence->escape_string($_POST['user_role']);

                          //input data triming;
                          $user_name = strip_tags(trim($user_name));
                          $email_address = strip_tags(trim($email_address));
                          $contact_number = strip_tags(trim($contact_number));
                          $designation = strip_tags(trim($designation));
                          $department = strip_tags(trim($department));
                          $gender = strip_tags(trim($gender));
                          $branch_id = strip_tags(trim($branch_id));
                          $user_role = strip_tags(trim($user_role));

                          // Escape any html characters;
                          $user_name = htmlentities($user_name);
                          $email_address = htmlentities($email_address);
                          $contact_number = htmlentities($contact_number);
                          $designation = htmlentities($designation);
                          $department = htmlentities($department);
                          $gender = htmlentities($gender);
                          $branch_id = htmlentities($branch_id);
                          $user_role = htmlentities($user_role);

                          //check refined and input values are empty and valid or not;
                          $msg = $validation->check_empty(array($user_name, $email_address, $contact_number, $designation,
                                                                $department, $gender, $branch_id, $user_role));
                          $check_email = $validation->is_email_valid($email_address);
                          $check_contact_number = $validation->is_contact_number_valid($contact_number);

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
                          }elseif (!$check_contact_number) {
                            ?>
                            <div class="alert bg-red">
                                <?php echo 'contact number is not valid'; ?>
                            </div>
                            <?php
                          }else{

                            //sending all variables to branch_crud for creating new branch;
                            $new_user_account = $user_operation->create_user($user_name, $email_address, $contact_number, $designation,
                                                                            $department, $gender, $branch_id, $user_role);
                            //$new_branch = $branch_operation->create_branch();
                            //check if branch created properly;
                            if($new_user_account == 'Successfully created a new user'){
                              ?>
                              <div class="alert bg-green">
                                  <?php echo 'Successfully created a new user'; ?>
                              </div>
                              <?php
                            }else{
                              ?>
                              <div class="alert bg-red">
                                  <?php echo $new_user_account; ?>
                              </div>
                              <?php
                            }

                            //$c_e_mail = $user_operation->check_user_email($email_address);
                            //echo $c_e_mail;

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
                                Create New User
                            </h2>

                        </div>
                        <div class="body">
                            <form action="create-user" method="POST">
                                <label for="name">Name</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" id="name" class="form-control" required aria-required="true" placeholder="Enter user's name here" name="user_name">
                                    </div>
                                </div>
                                <label for="email_address">Email Address</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="email" id="email_address" class="form-control" placeholder="Enter user's email address" name="email_address">
                                    </div>
                                </div>
                                <label for="name">Designation</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" id="designation" class="form-control" required aria-required="true" placeholder="Enter user's designation here" name="designation">
                                    </div>
                                </div>
                                <label for="name">Department</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" id="department" class="form-control" required aria-required="true" placeholder="Enter user's department here" name="department">
                                    </div>
                                </div>
                                <label for="contact_number">Contact Number</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" id="contact_number" class="form-control" placeholder="Enter user's contact number" name="contact_number">
                                    </div>
                                </div>
                                <label for="gender">Gender</label>
                                <div class="form-group">
                                    <input type="radio" name="gender" id="male" class="with-gap radio-col-deep-orange" value="Male">
                                    <label for="male">Male</label>

                                    <input type="radio" name="gender" id="female" class="with-gap radio-col-deep-orange" value="Female">
                                    <label for="female" class="m-l-20">Female</label>
                                </div>

                                <label for="branch_name">Branch Name</label>
                                <div class="form-group">
                                  <select class="form-control show-tick" name="branch_id">
                                      <option value="0">-- Select Branch --</option>

                                      <?php

                                        $get_branch = new BranchCrudOperation();
                                        $query = "SELECT `branch_id`, `name` FROM `office_branch` ORDER BY `name` ASC";
                                        $result = $get_branch->getData($query);

                                        foreach ($result as $key => $res)
                                        {
                                          ?>
                                          <option value="<?php echo $res['branch_id']; ?>"><?php echo $res['name']; ?></option>
                                          <?php
                                        }
                                      ?>
                                  </select>
                                </div>

                                <label for="user_role">User Role</label>
                                <div class="form-group">
                                  <select class="form-control show-tick" name="user_role">
                                      <option value="0">-- Select User Role --</option>
                                      <option value="super_admin">Super Admin</option>
                                      <option value="finance_user">Finance User</option>
                                      <option value="business_user">Business User</option>
                                  </select>
                                </div>

                                <br>
                                <input type="submit" name="submit" class="btn bg-deep-orange waves-effect m-t-15" value="Create">
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
