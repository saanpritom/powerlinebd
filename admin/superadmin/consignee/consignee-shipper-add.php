<?php

  require_once('../classes/authentication.php');
  include('../static_references/header.php');
  include('../static_references/search.php');
  include('../static_references/navbar.php');
  include_once('../classes/data_validation.php');
  include_once('../classes/data_clearence.php');
  include_once('../classes/consignee_crud.php');

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
                          $consignee_operation = new ConsigneeCrudOperation();

                          //mysql escape string clearence;
                          $name = $clearence->escape_string($_POST['name']);
                          $email = $clearence->escape_string($_POST['email']);
                          $contact_number = $clearence->escape_string($_POST['contact_number']);
                          $address = $clearence->escape_string($_POST['address']);
                          $country_id = $clearence->escape_string($_POST['country_id']);
                          $shipper_id = $clearence->escape_string($_POST['shipper_id']);

                          //input data triming;
                          $name = strip_tags(trim($name));
                          $email = strip_tags(trim($email));
                          $contact_number = strip_tags(trim($contact_number));
                          $address = strip_tags(trim($address));
                          $country_id = strip_tags(trim($country_id));
                          $shipper_id = strip_tags(trim($shipper_id));

                          // Escape any html characters;
                          $name = htmlentities($name);
                          $email = htmlentities($email);
                          $contact_number = htmlentities($contact_number);
                          $address = htmlentities($address);
                          $country_id = htmlentities($country_id);
                          $shipper_id = htmlentities($shipper_id);

                          //check refined and input values are empty and valid or not;
                          $msg = $validation->check_empty(array($name, $email, $contact_number, $address, $country_id, $shipper_id));
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
                            $new_consignee = $consignee_operation->create_consignee($name, $email, $contact_number, $country_id, $address, $shipper_id, $user_id);

                            //check if branch created properly;
                            if($new_consignee == 'Successfully created a new consignee'){
                              ?>
                              <div class="alert bg-green">
                                  <?php echo 'Successfully created a new consignee'; ?>
                              </div>
                              <?php
                            }else{
                              ?>
                              <div class="alert bg-red">
                                  <?php echo $new_consignee; ?>
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
                                Add New Shipper
                            </h2>

                        </div>
                        <div class="body">
                            <?php

                              $get_consignee = new ConsigneeCrudOperation();

                            ?>

                            <form action="<?php echo $consignee_id; ?>" method="POST">

                              <div class="demo-checkbox">

                                  <input type="checkbox" id="md_checkbox_35" class="filled-in chk-col-deep-orange" checked="">
                                  <label for="md_checkbox_35">ORANGE</label>



                              </div>


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
