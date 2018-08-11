<?php

  require_once('../classes/authentication.php');
  include('../static_references/header.php');
  include('../static_references/search.php');
  include('../static_references/navbar.php');
  include_once('../classes/data_validation.php');
  include_once('../classes/data_clearence.php');
  include_once('../classes/contact_person_crud.php');
  include_once('../classes/place_crud.php');

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
                          $contact_operation = new ContactCrudOperation();

                          $organization_id = $_GET['b_id'];
                          $organization_id = $clearence->escape_string($organization_id);
                          $organization_id = strip_tags(trim($organization_id));
                          $organization_id = htmlentities($organization_id);

                          //mysql escape string clearence;
                          $name = $clearence->escape_string($_POST['name']);
                          $email = $clearence->escape_string($_POST['email']);
                          $contact_number = $clearence->escape_string($_POST['contact_number']);
                          $designation = $clearence->escape_string($_POST['designation']);

                          //input data triming;
                          $name = strip_tags(trim($name));
                          $email = strip_tags(trim($email));
                          $contact_number = strip_tags(trim($contact_number));
                          $designation = strip_tags(trim($designation));

                          // Escape any html characters;
                          $name = htmlentities($name);
                          $email = htmlentities($email);
                          $contact_number = htmlentities($contact_number);
                          $designation = htmlentities($designation);

                          //check refined and input values are empty and valid or not;
                          $msg = $validation->check_empty(array($name, $email, $contact_number, $designation));
                          $check_email = $validation->is_email_valid($email);
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

                            $user_id = $_SESSION["plbd_id"];

                            //sending all variables to branch_crud for creating new branch;
                            $new_contact = $contact_operation->create_contact($name, $email, $contact_number, $designation, $organization_id, 'consignee', $user_id);

                            //check if branch created properly;
                            if($new_contact == 'Successfully created a new contact'){
                              ?>
                              <div class="alert bg-green">
                                  <?php echo 'Successfully created a new contact'; ?>
                              </div>
                              <?php
                            }else{
                              ?>
                              <div class="alert bg-red">
                                  <?php echo $new_contact; ?>
                              </div>
                              <?php
                            }

                          }

                      ?>

                      <a href="/powerlinebd/admin/superadmin/consignee/consignee-detail/<?php echo $organization_id; ?>/1" type="button" class="btn bg-teal waves-effect">Go back</a>

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
                                Add New Contact
                            </h2>

                        </div>
                        <div class="body">
                            <form action="<?php echo $_GET['b_id']; ?>" method="POST">
                                <label for="name">Name of the Contact Person</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" id="full_name" class="form-control" required aria-required="true" placeholder="Enter Name here" name="name">
                                    </div>
                                </div>
                                <label for="email_address">Contact Email</label>
                                <div class="input-group">
                                    <div class="form-line">
                                        <input type="email" class="form-control" name="email" placeholder="Enter Email Here">
                                    </div>
                                </div>
                                <label for="contact_number">Designation</label>
                                <div class="input-group">
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="designation" placeholder="Enter Designation Here">
                                    </div>
                                </div>
                                <label for="contact_number">Contact Number</label>
                                <div class="input-group">
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="contact_number" placeholder="Enter Contact Number Here">
                                    </div>
                                </div>


                                <br>
                                <input type="submit" name="submit" class="btn bg-deep-orange waves-effect m-t-15" value="Add Contact">
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
