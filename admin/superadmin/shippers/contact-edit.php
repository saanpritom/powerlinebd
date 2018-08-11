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

                          $user_id = $_SESSION['plbd_id'];
                          $contact_id = $_GET['b_id'];
                          $contact_id = $clearence->escape_string($contact_id);
                          $contact_id = strip_tags(trim($contact_id));
                          $contact_id = htmlentities($contact_id);

                          //mysql escape string clearence;
                          $name = $clearence->escape_string($_POST['name']);
                          $email = $clearence->escape_string($_POST['email']);
                          $contact_number = $clearence->escape_string($_POST['contact_number']);
                          $designation = $clearence->escape_string($_POST['designation']);
                          $organization_id = $clearence->escape_string($_POST['organization_id']);

                          //input data triming;
                          $name = strip_tags(trim($name));
                          $email = strip_tags(trim($email));
                          $contact_number = strip_tags(trim($contact_number));
                          $designation = strip_tags(trim($designation));
                          $organization_id = strip_tags(trim($organization_id));

                          // Escape any html characters;
                          $name = htmlentities($name);
                          $email = htmlentities($email);
                          $contact_number = htmlentities($contact_number);
                          $designation = htmlentities($designation);
                          $organization_id = htmlentities($organization_id);

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

                            //sending all variables to branch_crud for creating new branch;
                            $new_contact = $contact_operation->update_contact($name, $email, $contact_number, $designation, $organization_id, $contact_id, $user_id);

                            //check if branch created properly;
                            if($new_contact == 'Successfully updated contact'){
                              ?>
                              <div class="alert bg-green">
                                  <?php echo 'Successfully updated contact'; ?>
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

                      <a href="/powerlinebd/admin/superadmin/shippers/contact-detail/<?php echo $contact_id; ?>" type="button" class="btn bg-teal waves-effect">Go back</a>

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
                                Edit Contact
                            </h2>

                        </div>
                        <div class="body">
                            <?php

                              $clearence = new Clearence();
                              $contact_operation = new ContactCrudOperation();

                              $contact_id = $_GET['b_id'];

                              $contact_id = $clearence->escape_string($contact_id);
                              $contact_id = strip_tags(trim($contact_id));
                              $contact_id = htmlentities($contact_id);

                              $query = "SELECT contact_person_details.name, contact_person_details.contact_number,
                                        contact_person_details.designation, contact_person_details.parent_organization_id,
                                        login_info.email FROM contact_person_details
                                        INNER JOIN login_info ON contact_person_details.contact_id=login_info.user_id WHERE
                                        contact_person_details.contact_id='$contact_id'";

                              $result = $contact_operation->getData($query);

                              foreach($result as $key => $res){

                                $name = $res['name'];

                                $contact_number = $res['contact_number'];

                                $designation = $res['designation'];

                                $email = $res['email'];

                                $parent_organization_id = $res['parent_organization_id'];

                              }


                            ?>
                            <form action="<?php echo $_GET['b_id']; ?>" method="POST">
                                <label for="name">Name of the Contact Person</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" id="full_name" class="form-control" required aria-required="true" placeholder="Enter Name here" name="name" value="<?php echo $name; ?>">
                                    </div>
                                </div>
                                <label for="email_address">Contact Email</label>
                                <div class="input-group">
                                    <div class="form-line">
                                        <input type="email" class="form-control" name="email" placeholder="Enter Email Here" value="<?php echo $email; ?>">
                                    </div>
                                </div>
                                <label for="contact_number">Designation</label>
                                <div class="input-group">
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="designation" placeholder="Enter Designation Here" value="<?php echo $designation; ?>">
                                    </div>
                                </div>
                                <label for="contact_number">Contact Number</label>
                                <div class="input-group">
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="contact_number" placeholder="Enter Contact Number Here" value="<?php echo $contact_number; ?>">
                                    </div>
                                </div>

                                <label for="organization_name">Organization Name</label>
                                <div class="form-group">
                                  <select class="form-control show-tick" name="organization_id">

                                      <?php

                                        $get_contact = new ContactCrudOperation();
                                        $query = "SELECT shipper_id, name FROM shipper_details ORDER BY name ASC";
                                        $result = $get_contact->getData($query);

                                        foreach ($result as $key => $res)
                                        {
                                          if($res['shipper_id'] == $parent_organization_id){

                                            ?>
                                            <option value="<?php echo $res['shipper_id']; ?>" selected="selected"><?php echo $res['name']; ?></option>
                                            <?php

                                          }else{

                                            ?>
                                            <option value="<?php echo $res['shipper_id']; ?>"><?php echo $res['name']; ?></option>
                                            <?php

                                          }

                                        }
                                      ?>
                                  </select>
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
