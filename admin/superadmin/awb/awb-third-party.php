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

                          //mysql escape string clearence;
                          $awb_number = $clearence->escape_string($_POST['awb_number']);
                          $shipper_id = $clearence->escape_string($_POST['shipper_id']);
                          $consignee_name = $clearence->escape_string($_POST['consignee_id']);
                          $destination = $clearence->escape_string($_POST['destination']);
                          $bag_number = $clearence->escape_string($_POST['bag_number']);
                          $type = $clearence->escape_string($_POST['type']);
                          $pcs = $clearence->escape_string($_POST['pcs']);
                          $a_weight = $clearence->escape_string($_POST['a_weight']);
                          $b_weight = $clearence->escape_string($_POST['b_weight']);
                          $price_value = $clearence->escape_string($_POST['price_value']);

                          //input data triming;
                          $awb_number = strip_tags(trim($awb_number));
                          $shipper_id = strip_tags(trim($shipper_id));
                          $consignee_name = strip_tags(trim($consignee_name));
                          $destination = strip_tags(trim($destination));
                          $bag_number = strip_tags(trim($bag_number));
                          $type = strip_tags(trim($type));
                          $pcs = strip_tags(trim($pcs));
                          $a_weight = strip_tags(trim($a_weight));
                          $b_weight = strip_tags(trim($b_weight));
                          $price_value = strip_tags(trim($price_value));

                          // Escape any html characters;
                          $awb_number = htmlentities($awb_number);
                          $shipper_id = htmlentities($shipper_id);
                          $consignee_name = htmlentities($consignee_name);
                          $destination = htmlentities($destination);
                          $bag_number = htmlentities($bag_number);
                          $type = htmlentities($type);
                          $pcs = htmlentities($pcs);
                          $a_weight = htmlentities($a_weight);
                          $b_weight = htmlentities($b_weight);
                          $price_value = htmlentities($price_value);

                          //check refined and input values are empty and valid or not;
                          $msg = $validation->check_empty(array($awb_number, $shipper_id, $consignee_name, $bag_number, $type, $pcs, $a_weight, $b_weight, $price_value));

                          if($msg != null){
                            ?>
                            <div class="alert bg-red">
                                <?php echo $msg; ?>
                            </div>
                            <?php

                          }else{

                            $user_id = $_SESSION["plbd_id"];

                            //sending all variables to branch_crud for creating new branch;
                            $new_awb = $awb_operation->create_awb($awb_number, $shipper_id, $consignee_name, $destination, $bag_number, $type, $pcs, $a_weight, $b_weight, $price_value, $user_id);

                            //check if branch created properly;
                            if($new_awb == 'Successfully created a new AWB'){
                              ?>
                              <div class="alert bg-green">
                                  <?php echo 'Successfully created a new AWB'; ?>
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
                                Add Third Party Details to AWB
                            </h2>

                        </div>
                        <div class="body">

                          <?php

                          $get_data = new AWBCrudOperation();

                          $awb_id = $_GET['b_id'];

                          $query = "SELECT next_branch FROM awb_mawb_flight_relation WHERE awb_id='$awb_id'";

                          $result = $get_data->getData($query);

                          foreach ($result as $key => $res) {

                            $next_branch = $res['next_branch'];

                          }

                          if($next_branch == 'third_party'){

                            ?>

                            <form action="awb-third-party" method="POST">
                                <label for="awb_number">Third Party Name</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" id="mawb_number" class="form-control" required aria-required="true" placeholder="Enter Third Party Name Here" name="third_party_name">
                                    </div>
                                </div>
                                <br>
                                <label for="bag_number">Third Party Address</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" id="bag_number" class="form-control" placeholder="Enter Third Party Address" name="third_party_address">
                                    </div>
                                </div>
                                <label for="bag_number">Third Party Contact Number</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" id="bag_number" class="form-control" placeholder="Enter Third Party Contact Number" name="third_party_contact_number">
                                    </div>
                                </div>

                                <label for="bag_number">Third Party AWB Number</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" id="bag_number" class="form-control" placeholder="Enter Third Party Given Unique Number" name="third_party_awb_number">
                                    </div>
                                </div>

                                <label for="bag_number">Third Party Destination</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" id="bag_number" class="form-control" placeholder="Enter Third Party Delivery Place" name="third_party_destination">
                                    </div>
                                </div>

                                <input type="submit" name="submit" class="btn bg-deep-orange waves-effect m-t-15" value="Add Third Party">
                            </form>

                            <?php

                          }else{

                            ?>

                            <div class="alert bg-red">

                              You can only set Third Party details if Third Party is set as the next destination of this AWB.

                            </div>

                            <?php

                          }

                          ?>








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
