<?php

  require_once('../classes/authentication.php');
  include('../static_references/header.php');
  include('../static_references/search.php');
  include('../static_references/navbar.php');
  include_once('../classes/data_validation.php');
  include_once('../classes/data_clearence.php');
  include_once('../classes/shipper_crud.php');
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
                          $shipper_operation = new ShipperCrudOperation();

                          $shipper_id = $_GET['b_id'];
                          $shipper_id = $clearence->escape_string($shipper_id);
                          $shipper_id = strip_tags(trim($shipper_id));
                          $shipper_id = htmlentities($shipper_id);

                          //mysql escape string clearence;
                          $shipper_name = $clearence->escape_string($_POST['shipper_name']);
                          $email_address = $clearence->escape_string($_POST['email_address']);
                          $contact_number = $clearence->escape_string($_POST['contact_number']);
                          $country_id = $clearence->escape_string($_POST['country_id']);
                          $address = $clearence->escape_string($_POST['address']);

                          //input data triming;
                          $shipper_name = strip_tags(trim($shipper_name));
                          $email_address = strip_tags(trim($email_address));
                          $contact_number = strip_tags(trim($contact_number));
                          $country_id = strip_tags(trim($country_id));
                          $address = strip_tags(trim($address));

                          // Escape any html characters;
                          $shipper_name = htmlentities($shipper_name);
                          $email_address = htmlentities($email_address);
                          $contact_number = htmlentities($contact_number);
                          $country_id = htmlentities($country_id);
                          $address = htmlentities($address);

                          //check refined and input values are empty and valid or not;
                          $msg = $validation->check_empty(array($shipper_name, $email_address, $contact_number, $country_id, $address));
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

                            $user_id = $_SESSION['plbd_id'];

                            //sending all variables to branch_crud for creating new branch;
                            $update_shipper_account = $shipper_operation->update_shipper($shipper_id, $shipper_name, $email_address, $contact_number,
                                                                                          $country_id, $address, $user_id);
                            //$new_branch = $branch_operation->create_branch();
                            //check if branch created properly;
                            if($update_shipper_account == 'Successfully updated shipper'){
                              ?>
                              <div class="alert bg-green">
                                  <?php echo 'Successfully updated shipper'; ?>
                              </div>
                              <?php
                            }else{
                              ?>
                              <div class="alert bg-red">
                                  <?php echo $update_shipper_account; ?>
                              </div>
                              <?php
                            }

                            //$c_e_mail = $user_operation->check_user_email($email_address);
                            //echo $c_e_mail;

                          }

                      ?>

                      <a href="/powerlinebd/admin/superadmin/shippers/shipper-detail/<?php echo $shipper_id; ?>/1" type="button" class="btn bg-teal waves-effect">Go back</a>

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
                                Edit Shipper
                            </h2>

                        </div>
                        <div class="body">

                          <?php

                              //populating edit branch form;
                              $shipper_id = $_GET['b_id'];
                              $shipper_operation = new ShipperCrudOperation();
                              $clearence = new Clearence();

                              //data clearance;
                              $shipper_id = $clearence->escape_string($shipper_id);
                              $shipper_id = strip_tags(trim($shipper_id));
                              $shipper_id = htmlentities($shipper_id);

                              $query = "SELECT shipper_details.name, shipper_details.address, shipper_details.contact_number,
                                        shipper_details.country_id, login_info.email
                                        FROM shipper_details INNER JOIN login_info
                                        ON shipper_details.shipper_id = login_info.user_id
                                        WHERE shipper_details.shipper_id='$shipper_id'";

                              $result = $shipper_operation->getData($query);

                              foreach($result as $key => $res){

                                $name = $res['name'];

                                $address = $res['address'];

                                $contact_number = $res['contact_number'];

                                $email = $res['email'];

                                $country_id = $res['country_id'];

                              }

                          ?>

                            <form action="<?php echo $shipper_id; ?>" method="POST">
                                <label for="name">Name</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" id="name" class="form-control" required aria-required="true" placeholder="Enter Shipper's name here" name="shipper_name" value="<?php echo $name;  ?>">
                                    </div>
                                </div>
                                <label for="email_address">Email Address</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="email" id="email_address" class="form-control" placeholder="Enter Shipper's email address" name="email_address" value="<?php echo $email; ?>">
                                    </div>
                                </div>

                                <label for="contact_number">Contact Number</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" id="contact_number" class="form-control" placeholder="Enter Shipper's contact number" name="contact_number" value="<?php echo $contact_number; ?>">
                                    </div>
                                </div>

                                <label for="branch_address">Shipper's Address</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <textarea rows="4" class="form-control no-resize" name="address"><?php echo $address; ?></textarea>
                                    </div>
                                </div>

                                <label for="branch_name">Country Name</label>
                                <div class="form-group">
                                  <select class="form-control show-tick" name="country_id">

                                      <?php

                                        $get_country = new PlaceCrudOperation();
                                        $query = "SELECT `o_id_id`, `full_name` FROM `origin_destination_details`
                                                  WHERE `type`='country' ORDER BY `full_name` ASC";
                                        $result = $get_country->getData($query);

                                        foreach ($result as $key => $res)
                                        {
                                          if($res['o_id_id'] == $country_id){

                                            ?>
                                            <option value="<?php echo $res['o_id_id']; ?>" selected="selected"><?php echo $res['full_name']; ?></option>
                                            <?php

                                          }else{

                                            ?>
                                            <option value="<?php echo $res['o_id_id']; ?>"><?php echo $res['full_name']; ?></option>
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
