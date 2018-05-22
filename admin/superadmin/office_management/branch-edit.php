<?php

  require_once('../classes/authentication.php');
  include('../static_references/header.php');
  include('../static_references/search.php');
  include('../static_references/navbar.php');
  include_once('../classes/data_validation.php');
  include_once('../classes/data_clearence.php');
  include_once('../classes/branch_crud.php');

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

                          $branch_id = $_GET['b_id'];

                          //declare classes for checking;
                          $validation = new Validation();
                          $clearence = new Clearence();
                          $branch_operation = new BranchCrudOperation();

                          //mysql escape string clearence;
                          $branch_name = $clearence->escape_string($_POST['branch_name']);
                          $email_address = $clearence->escape_string($_POST['email_address']);
                          $contact_number = $clearence->escape_string($_POST['contact_number']);
                          $address = $clearence->escape_string($_POST['address']);

                          //input data triming;
                          $branch_name = strip_tags(trim($branch_name));
                          $email_address = strip_tags(trim($email_address));
                          $contact_number = strip_tags(trim($contact_number));
                          $address = strip_tags(trim($address));

                          // Escape any html characters;
                          $branch_name = htmlentities($branch_name);
                          $email_address = htmlentities($email_address);
                          $contact_number = htmlentities($contact_number);
                          $address = htmlentities($address);

                          //check refined and input values are empty and valid or not;
                          $msg = $validation->check_empty(array($branch_name, $email_address, $contact_number, $address));
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
                            $new_branch = $branch_operation->update_branch($branch_id, $branch_name, $email_address, $contact_number, $address);

                            //check if branch created properly;
                            if($new_branch == 'Branch updated Successfully'){
                              ?>
                              <div class="alert bg-green">
                                  <?php echo 'Branch updated Successfully'; ?>
                              </div>
                              <?php
                            }else{
                              ?>
                              <div class="alert bg-red">
                                  <?php echo $new_branch; ?>
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
                                Edit Branch Information
                            </h2>

                        </div>
                        <div class="body">
                            <?php

                              //populating edit branch form;
                              $branch_id = $_GET['b_id'];
                              $branch_operation = new BranchCrudOperation();

                              $query = "SELECT `name`, `address`, `email`, `contact_number` FROM `office_branch` WHERE `branch_id`='$branch_id'";

                              $result = $branch_operation->getData($query);

                              foreach($result as $key => $res){

                                $name = $res['name'];

                                $address = $res['address'];

                                $email = $res['email'];

                                $contact_number = $res['contact_number'];

                              }

                            ?>
                            <form action="<?php echo $branch_id; ?>" method="POST">
                                <label for="name">Branch Name</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" id="name" class="form-control" required aria-required="true" placeholder="Enter branch name here" name="branch_name" value="<?php echo $name; ?>">
                                    </div>
                                </div>
                                <label for="email_address">Email Address</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="email" id="email_address" class="form-control" placeholder="Enter email address" name="email_address" value="<?php echo $email; ?>">
                                    </div>
                                </div>
                                <label for="email_address">Contact Number</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" id="contact_number" class="form-control" placeholder="Enter contact number" name="contact_number" value="<?php echo $contact_number; ?>">
                                    </div>
                                </div>

                                <label for="branch_address">Branch Address</label>
                                <div class="form-group">
                                        <div class="form-line">
                                            <textarea rows="4" class="form-control no-resize" placeholder="Please type address here" name="address"><?php echo $address; ?></textarea>
                                        </div>
                                    </div>

                                <br>
                                <input type="submit" name="submit" class="btn bg-deep-orange waves-effect m-t-15" value="Update">
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
