<?php

  require_once('../classes/authentication.php');
  include('../static_references/header.php');
  include('../static_references/search.php');
  include('../static_references/navbar.php');
  include_once('../classes/data_validation.php');
  include_once('../classes/data_clearence.php');
  include_once('../classes/awb_crud.php');

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
                          $awb_operation = new AWBCrudOperation();

                          $awb_id = $_GET['b_id'];

                          //mysql escape string clearence;
                          $awb_id = $clearence->escape_string($awb_id);
                          $third_party_name = $clearence->escape_string($_POST['third_party_name']);
                          $third_party_address = $clearence->escape_string($_POST['third_party_address']);
                          $third_party_contact_number = $clearence->escape_string($_POST['third_party_contact_number']);
                          $third_party_awb_number = $clearence->escape_string($_POST['third_party_awb_number']);
                          $third_party_destination = $clearence->escape_string($_POST['third_party_destination']);

                          //input data triming;
                          $awb_id = strip_tags(trim($awb_id));
                          $third_party_name = strip_tags(trim($third_party_name));
                          $third_party_address = strip_tags(trim($third_party_address));
                          $third_party_contact_number = strip_tags(trim($third_party_contact_number));
                          $third_party_awb_number = strip_tags(trim($third_party_awb_number));
                          $third_party_destination = strip_tags(trim($third_party_destination));

                          // Escape any html characters;
                          $awb_id = htmlentities($awb_id);
                          $third_party_name = htmlentities($third_party_name);
                          $third_party_address = htmlentities($third_party_address);
                          $third_party_contact_number = htmlentities($third_party_contact_number);
                          $third_party_awb_number = htmlentities($third_party_awb_number);
                          $third_party_destination = htmlentities($third_party_destination);

                          //check refined and input values are empty and valid or not;
                          $msg = $validation->check_empty(array($awb_id, $third_party_name, $third_party_awb_number, $third_party_destination));

                          if($msg != null){
                            ?>
                            <div class="alert bg-red">
                                <?php echo $msg; ?>
                            </div>
                            <?php

                          }else{

                            $user_id = $_SESSION["plbd_id"];

                            //sending all variables to branch_crud for creating new branch;
                            $new_awb = $awb_operation->update_third_party($awb_id, $third_party_name, $third_party_address, $third_party_contact_number, $third_party_awb_number, $third_party_destination, $user_id);

                            //check if branch created properly;
                            if($new_awb == 'Successfully updated Third Party for AWB'){
                              ?>
                              <div class="alert bg-green">
                                  <?php echo 'Successfully updated Third Party for AWB'; ?>
                              </div>
                              <?php
                            }else{
                              ?>
                              <div class="alert bg-red">
                                  <?php echo $new_awb; ?>
                              </div>
                              <?php
                            }

                          }

                      ?>

                      <a href="/powerlinebd/admin/superadmin/awb/awb-detail/<?php echo $awb_id; ?>/1" type="button" class="btn bg-teal waves-effect">Go back</a>

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
                                Edit Third Party Details to AWB
                            </h2>

                        </div>
                        <div class="body">

                          <?php

                          $get_data = new AWBCrudOperation();

                          $awb_id = $_GET['b_id'];

                          $query = "SELECT `third_party_name`, `third_party_address`, `third_party_contact_number`,
                                    `third_party_number`, `third_party_destination` FROM `awb_third_party` WHERE awb_id='$awb_id'";

                          $result = $get_data->getData($query);

                          foreach ($result as $key => $res) {

                            $third_party_name = $res['third_party_name'];
                            $third_party_address = $res['third_party_address'];
                            $third_party_contact_number = $res['third_party_contact_number'];
                            $third_party_awb_number = $res['third_party_number'];
                            $third_party_destination = $res['third_party_destination'];

                          }

                          ?>

                          <form action="<?php echo $awb_id; ?>" method="POST">
                              <label for="awb_number">Third Party Name</label>
                              <div class="form-group">
                                  <div class="form-line">
                                      <input type="text" id="mawb_number" class="form-control" required aria-required="true" placeholder="Enter Third Party Name Here" name="third_party_name" value="<?php echo $third_party_name; ?>">
                                  </div>
                              </div>
                              <br>
                              <label for="bag_number">Third Party Address</label>
                              <div class="form-group">
                                  <div class="form-line">
                                      <input type="text" id="bag_number" class="form-control" placeholder="Enter Third Party Address" name="third_party_address" value="<?php echo $third_party_address; ?>">
                                  </div>
                              </div>
                              <label for="bag_number">Third Party Contact Number</label>
                              <div class="form-group">
                                  <div class="form-line">
                                      <input type="text" id="bag_number" class="form-control" placeholder="Enter Third Party Contact Number" name="third_party_contact_number" value="<?php echo $third_party_contact_number; ?>">
                                  </div>
                              </div>

                              <label for="bag_number">Third Party AWB Number</label>
                              <div class="form-group">
                                  <div class="form-line">
                                      <input type="text" id="bag_number" class="form-control" placeholder="Enter Third Party Given Unique Number" name="third_party_awb_number" value="<?php echo $third_party_awb_number; ?>">
                                  </div>
                              </div>

                              <label for="bag_number">Third Party Destination</label>
                              <div class="form-group">
                                  <div class="form-line">
                                      <input type="text" id="bag_number" class="form-control" placeholder="Enter Third Party Delivery Place" name="third_party_destination" value="<?php echo $third_party_destination; ?>">
                                  </div>
                              </div>

                              <input type="submit" name="submit" class="btn bg-deep-orange waves-effect m-t-15" value="Update Third Party">
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
