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
                            $update_user_account = $user_operation->update_user($user_name, $email_address, $contact_number, $designation,
                                                                            $department, $gender, $branch_id, $user_role, $user_id);
                            //$new_branch = $branch_operation->create_branch();
                            //check if branch created properly;
                            if($update_user_account == 'Successfully updated a new user'){
                              ?>
                              <div class="alert bg-green">
                                  <?php echo 'Successfully updated a new user'; ?>
                              </div>
                              <?php
                            }else{
                              ?>
                              <div class="alert bg-red">
                                  <?php echo $update_user_account; ?>
                              </div>
                              <?php
                            }

                            //$c_e_mail = $user_operation->check_user_email($email_address);
                            //echo $c_e_mail;

                          }

                      ?>

                      <a href="/powerlinebd/admin/superadmin/office_management/user-detail/<?php echo $user_id; ?>" type="button" class="btn bg-teal waves-effect">Go back</a>

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
                                Edit User
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

                              $query = "SELECT admin_details.admin_type, admin_details.name, admin_details.gender,
                                       admin_details.designation, admin_details.department, admin_details.contact_number,
                                       login_info.email,
                                       office_branch.branch_id
                                       FROM admin_details INNER JOIN login_info ON
                                       admin_details.admin_id=login_info.user_id INNER JOIN office_branch ON
                                       admin_details.branch_id=office_branch.branch_id WHERE admin_details.admin_id='$user_id'";

                              $result = $user_operation->getData($query);

                              foreach($result as $key => $res){

                                $admin_type = $res['admin_type'];

                                $name = $res['name'];

                                $gender = $res['gender'];

                                $designation = $res['designation'];

                                $department = $res['department'];

                                $contact_number = $res['contact_number'];

                                $email = $res['email'];

                                $branch_id = $res['branch_id'];

                              }

                          ?>

                            <form action="<?php echo $user_id; ?>" method="POST">
                                <label for="name">Name</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" id="name" class="form-control" required aria-required="true" placeholder="Enter user's name here" name="user_name" value="<?php echo $name;  ?>">
                                    </div>
                                </div>
                                <label for="email_address">Email Address</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="email" id="email_address" class="form-control" placeholder="Enter user's email address" name="email_address" value="<?php echo $email; ?>">
                                    </div>
                                </div>
                                <label for="name">Designation</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" id="designation" class="form-control" required aria-required="true" placeholder="Enter user's designation here" name="designation" value="<?php echo $designation; ?>">
                                    </div>
                                </div>
                                <label for="name">Department</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" id="department" class="form-control" required aria-required="true" placeholder="Enter user's department here" name="department" value="<?php echo $department; ?>">
                                    </div>
                                </div>
                                <label for="contact_number">Contact Number</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" id="contact_number" class="form-control" placeholder="Enter user's contact number" name="contact_number" value="<?php echo $contact_number; ?>">
                                    </div>
                                </div>
                                <label for="gender">Gender</label>
                                <div class="form-group">
                                    <?php

                                      if($gender == 'Male'){
                                        ?>
                                        <input type="radio" name="gender" id="male" class="with-gap radio-col-deep-orange" value="Male" checked>
                                        <?php
                                      }else{
                                        ?>
                                        <input type="radio" name="gender" id="male" class="with-gap radio-col-deep-orange" value="Male">
                                        <?php
                                      }
                                    ?>
                                    <label for="male">Male</label>

                                    <?php

                                      if($gender == 'Female'){
                                        ?>
                                        <input type="radio" name="gender" id="female" class="with-gap radio-col-deep-orange" value="Female" checked>
                                        <?php
                                      }else{
                                        ?>
                                      <input type="radio" name="gender" id="female" class="with-gap radio-col-deep-orange" value="Female">
                                        <?php
                                      }
                                    ?>

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
                                          if($res['branch_id'] == $branch_id){

                                            ?>
                                            <option value="<?php echo $res['branch_id']; ?>" selected="selected"><?php echo $res['name']; ?></option>
                                            <?php

                                          }else{

                                            ?>
                                            <option value="<?php echo $res['branch_id']; ?>"><?php echo $res['name']; ?></option>
                                            <?php

                                          }

                                        }
                                      ?>
                                  </select>
                                </div>

                                <label for="user_role">User Role</label>
                                <div class="form-group">
                                  <select class="form-control show-tick" name="user_role">
                                      <?php

                                        if($admin_type == 'super_admin'){
                                          ?>
                                          <option value="super_admin" selected="selected">Super Admin</option>
                                          <?php
                                        }else{
                                          ?>
                                          <option value="super_admin">Super Admin</option>
                                          <?php
                                        }

                                        if($admin_type == 'finance_user'){
                                          ?>
                                          <option value="finance_user" selected="selected">Finance User</option>
                                          <?php
                                        }else{
                                          ?>
                                          <option value="finance_user">Finance User</option>
                                          <?php
                                        }

                                        if($admin_type == 'business_user'){
                                          ?>
                                          <option value="business_user" selected="selected">Business User</option>
                                          <?php
                                        }else{
                                          ?>
                                          <option value="business_user">Business User</option>
                                          <?php
                                        }

                                      ?>

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
