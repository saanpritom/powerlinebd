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

            $check_successful_message = 0;

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
                          $mawb_id = $clearence->escape_string($_POST['mawb_id']);
                          $flight_number = $clearence->escape_string($_POST['flight_number']);
                          $next_delivery = $clearence->escape_string($_POST['next_delivery']);

                          //input data triming;
                          $mawb_id = strip_tags(trim($mawb_id));
                          $flight_number = strip_tags(trim($flight_number));
                          $next_delivery = strip_tags(trim($next_delivery));

                          // Escape any html characters;
                          $mawb_id = htmlentities($mawb_id);
                          $flight_number = htmlentities($flight_number);
                          $next_delivery = htmlentities($next_delivery);

                          if($mawb_id == 'no_mawb'){


                            $user_id = $_SESSION["plbd_id"];

                            $awb_id = $_GET['b_id'];

                            //sending all variables to branch_crud for creating new branch;
                            $new_awb = $awb_operation->update_thirdparty_consignee($awb_id, $next_delivery, $user_id);

                            //check if branch created properly;
                            if($new_awb == 'Successfully updated next destination'){
                              ?>
                              <div class="alert bg-green">
                                  <?php echo 'Successfully updated next destination'; ?>
                              </div>
                              <?php
                            }else{
                              ?>
                              <div class="alert bg-red">
                                  <?php echo $new_awb; ?>
                              </div>
                              <?php
                            }


                          }else{

                            //check refined and input values are empty and valid or not;
                            $msg = $validation->check_empty(array($mawb_id, $flight_number, $next_delivery));

                            if($msg != null){
                              ?>
                              <div class="alert bg-red">
                                  <?php echo $msg; ?>
                              </div>
                              <?php

                            }else{

                              $user_id = $_SESSION["plbd_id"];

                              $awb_id = $_GET['b_id'];

                              //sending all variables to branch_crud for creating new branch;
                              $new_awb = $awb_operation->update_mawb_flight($awb_id, $mawb_id, $flight_number, $next_delivery, $user_id);

                              //check if branch created properly;
                              if($new_awb == 'Successfully updated MAWB, Flight and Next Destination'){
                                ?>
                                <div class="alert bg-green">
                                    <?php echo 'Successfully updated MAWB, Flight and Next Destination'; ?>
                                    <?php $check_successful_message++; ?>
                                </div>
                                <?php
                              }else{
                                ?>
                                <div class="alert bg-red">
                                    <?php

                                        echo $new_awb;
                                    ?>
                                </div>
                                <?php
                              }

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

                  <?php

                    //this filter is because the below lock status message was showing on successful page;

                    if($check_successful_message == 0){

                  ?>

                    <div class="card">
                        <div class="header">
                            <h2>
                                Add MAWB and Flight Number of AWB <?php echo $_GET['b_id']; ?>
                            </h2>

                        </div>
                        <div class="body">

                          <?php

                              $awb_id = $_GET['b_id'];

                              $get_data = new AWBCrudOperation();

                              //check if it is in lock state
                              $query = "SELECT lock_status FROM awb_lock WHERE awb_id='$awb_id'";

                              $result = $get_data->getData($query);

                              foreach ($result as $key => $res) {
                                $lock_status = $res['lock_status'];
                              }

                              if($lock_status == 'unlocked'){

                                ?>

                                <form action="<?php echo $awb_id; ?>" method="POST">
                                    <label for="awb_number">MAWB Number</label>
                                    <div class="form-group">
                                      <div class="form-group">
                                        <select class="form-control show-tick" name="mawb_id">

                                          <option value="no_mawb">No MAWB</option>

                                            <?php


                                              $query = "SELECT mawb_details.mawb_id, mawb_details.mawb_number FROM mawb_details
                                                        INNER JOIN creation_details ON
                                                        mawb_details.timer_id=creation_details.timer_id WHERE 1
                                                        ORDER BY creation_details.creation_date DESC";
                                              $result = $get_data->getData($query);

                                              foreach ($result as $key => $res)
                                              {

                                                  ?>

                                                  <option value="<?php echo $res['mawb_id']; ?>"><?php echo $res['mawb_number']; ?></option>

                                                  <?php
                                              }
                                            ?>
                                        </select>
                                      </div>
                                    </div>
                                    <br>

                                    <label for="destination">Flight Number</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" id="destination" class="form-control" placeholder="Flight Number" name="flight_number">
                                        </div>
                                    </div>

                                    <label for="awb_number">Next Delivery Place</label>
                                    <div class="form-group">
                                      <div class="form-group">
                                        <select class="form-control show-tick" name="next_delivery">

                                          <option value="consignee">Consignee</option>
                                          <option value="third_party">Third Party</option>
                                          <hr>

                                            <?php


                                              $query = "SELECT branch_id, name FROM office_branch WHERE 1
                                                        ORDER BY name ASC";
                                              $result = $get_data->getData($query);

                                              foreach ($result as $key => $res)
                                              {

                                                  ?>

                                                  <option value="<?php echo $res['branch_id']; ?>"><?php echo $res['name']; ?></option>

                                                  <?php
                                              }
                                            ?>

                                        </select>
                                      </div>
                                    </div>
                                    <br>


                                    <input type="submit" name="submit" class="btn bg-deep-orange waves-effect m-t-15" value="Update">
                                </form>


                                <?php



                              }else{


                                ?>

                                <div class="alert bg-red">

                                  You shouldn't try this. This AWB is in locked state. This is a warning.

                                </div>


                                <?php
                              }

                           ?>








                        </div>
                    </div>

                    <?php

                      }

                    ?>

                </div>
            </div>
<!-- #END# Vertical Layout -->




</div>
</section>
<?php

  require_once('../static_references/footer.php');



?>
