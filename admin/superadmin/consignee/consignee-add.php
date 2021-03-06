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
                                Add New Consignee
                            </h2>

                        </div>
                        <div class="body">
                            <?php

                              $get_consignee = new ConsigneeCrudOperation();

                            ?>
                            <form action="consignee-add" method="POST">
                                <label for="name">Name of the Consignee</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" id="full_name" class="form-control" required aria-required="true" placeholder="Enter Consignee Name here" name="name">
                                    </div>
                                </div>
                                <label for="email_address">Consignee's Email</label>
                                <div class="input-group">
                                    <div class="form-line">
                                        <input type="email" class="form-control" name="email" placeholder="Enter Email Here">
                                    </div>
                                </div>
                                <label for="contact_number">Consignee's Contact Number</label>
                                <div class="input-group">
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="contact_number" placeholder="Enter Contact Number Here">
                                    </div>
                                </div>
                                <label for="country_name">Country Name</label>
                                <div class="form-group">
                                  <select class="form-control show-tick" name="country_id">

                                      <?php

                                        $query = "SELECT `o_id_id`, `full_name` FROM `origin_destination_details` WHERE
                                                  `type`='country' ORDER BY `full_name` ASC";
                                        $result = $get_consignee->getData($query);

                                        foreach ($result as $key => $res)
                                        {
                                          ?>
                                          <option value="<?php echo $res['o_id_id']; ?>"><?php echo $res['full_name']; ?></option>
                                          <?php
                                        }
                                      ?>
                                  </select>
                                </div>
                                <label for="country_name">Related Shipper's Name</label>
                                <div class="form-group">
                                  <select class="form-control show-tick" name="shipper_id">

                                      <?php

                                        $query = "SELECT `shipper_id`, `name` FROM `shipper_details`
                                                  WHERE 1 ORDER BY `name` ASC";
                                        $result = $get_consignee->getData($query);

                                        foreach ($result as $key => $res)
                                        {
                                          ?>
                                          <option value="<?php echo $res['shipper_id']; ?>"><?php echo $res['name']; ?></option>
                                          <?php
                                        }

                                      ?>
                                  </select>
                                </div>
                                <label for="branch_address">Consignee's Address</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <textarea rows="4" class="form-control no-resize" placeholder="Please type address here" name="address"></textarea>
                                    </div>
                                </div>

                                <br>
                                <input type="submit" name="submit" class="btn bg-deep-orange waves-effect m-t-15" value="Add Consignee">
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
