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
                          $next_delivery = $clearence->escape_string($_POST['next_delivery']);

                          //input data triming;
                          $next_delivery = strip_tags(trim($next_delivery));

                          // Escape any html characters;
                          $next_delivery = htmlentities($next_delivery);

                          if($next_delivery == 'third_party'){

                            $next_delivery = 'Third Party';

                          }else{

                            $next_delivery = 'Consignee';

                          }

                          $user_id = $_SESSION["plbd_id"];

                          $awb_id = $_GET['b_id'];

                          $new_awb = $awb_operation->update_delivery_tp($awb_id, $next_delivery, $user_id);

                          if($new_awb == 'Successfully updated'){

                            ?>
                            <div class="alert bg-green">
                                <?php echo 'Successfully updated'; ?>
                            </div>
                            <br/>
                            <a href="/powerlinebd/admin/superadmin/awb/awb-detail/<?php echo $awb_id; ?>/1" type="button" class="btn bg-teal waves-effect">Go back</a>
                            <?php


                            if($next_delivery == 'Third Party'){

                              if($awb_operation->check_thirdparty_exists($awb_id)){

                                ?>
                                <a href="/powerlinebd/admin/superadmin/awb/third-party-edit/<?php echo $awb_id ?>" type="button" class="btn bg-blue waves-effect">Edit Third Party Information</a>
                                <?php

                              }else{

                                ?>

                                <a href="/powerlinebd/admin/superadmin/awb/awb-third-party/<?php echo $awb_id ?>" type="button" class="btn bg-red waves-effect">Add Third Party Delivery</a>

                                <?php

                              }

                            }

                            $check_successful_message++;

                          }else{

                              ?>
                              <div class="alert bg-red">
                                  <?php echo $new_awb; ?>
                              </div>
                              <br>
                              <a href="/powerlinebd/admin/superadmin/awb/awb-detail/<?php echo $awb_id; ?>/1" type="button" class="btn bg-teal waves-effect">Go back</a>
                              <?php



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
            <?php

                if($check_successful_message == 0){


                  ?>

                  <div class="row clearfix">
                      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">


                          <div class="card">
                              <div class="header">
                                  <h2>
                                      AWB Delivery of <?php echo $_GET['b_id']; ?>
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

                                          <label for="awb_number">Next Delivery Place</label>
                                          <div class="form-group">
                                            <div class="form-group">
                                              <select class="form-control show-tick" name="next_delivery">

                                                <option value="consignee">Consignee</option>
                                                <option value="third_party">Third Party</option>


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

                      </div>
                  </div>


                  <?php


                }

             ?>
<!-- #END# Vertical Layout -->




</div>
</section>
<?php

  require_once('../static_references/footer.php');



?>
