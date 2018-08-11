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
                          $msg = $validation->check_empty(array($awb_number, $shipper_id, $consignee_name, $destination, $bag_number, $type, $pcs, $a_weight, $b_weight, $price_value));

                          if($msg != null){
                            ?>
                            <div class="alert bg-red">
                                <?php echo $msg; ?>
                            </div>
                            <?php

                          }else{

                            $user_id = $_SESSION["plbd_id"];

                            //sending all variables to branch_crud for creating new branch;
                            $new_awb = $awb_operation->update_awb($awb_number, $shipper_id, $consignee_name, $destination, $bag_number, $type, $pcs, $a_weight, $b_weight, $price_value, $user_id);

                            //check if branch created properly;
                            if($new_awb == 'Successfully updated a new AWB'){
                              ?>
                              <div class="alert bg-green">
                                  <?php echo 'Successfully updated a new AWB'; ?>
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
                                Edit AWB
                            </h2>

                        </div>
                        <div class="body">

                          <?php

                          $get_data = new AWBCrudOperation();

                          $awb_id = $_GET['b_id'];

                          //check if it is in lock state
                          $query = "SELECT lock_status FROM awb_lock WHERE awb_id='$awb_id'";

                          $result = $get_data->getData($query);

                          foreach ($result as $key => $res) {
                            $lock_status = $res['lock_status'];
                          }

                          if($lock_status == 'unlocked'){



                            $consignee_id = '';

                            $destination_id = '';

                            $query = "SELECT `shipper_id`, `consignee_id`, `destination_id`, `bag_number`, `type`, `pcs`,
                                      `a.weight`, `b.weight`, `value`
                                      FROM `awb_details` WHERE awb_id = '$awb_id'";

                            $result = $get_data->getData($query);

                            foreach($result as $key => $res){

                              $shipper_id = $res['shipper_id'];
                              $consignee_id = $res['consignee_id'];
                              $destination_id = $res['destination_id'];
                              $bag_number = $res['bag_number'];
                              $type = $res['type'];
                              $pcs = $res['pcs'];
                              $a_weight = $res['a.weight'];
                              $b_weight = $res['b.weight'];
                              $value = $res['value'];

                            }

                            $consignee_name = '';

                            //check consignee already exists or not;
                            if(is_numeric($consignee_id)){

                              $query = "SELECT consignee_details.name, origin_destination_details.short_form
                                        FROM consignee_details
                                        INNER JOIN origin_destination_details ON
                                        consignee_details.country_id=origin_destination_details.o_id_id
                                        WHERE consignee_details.consignee_id='$consignee_id'";

                              $result = $get_data->getData($query);

                              foreach($result as $key => $res){

                                $consignee_name = $res['name'];

                                $short_form = $res['short_form'];

                              }

                              $consignee_name = $consignee_name . '-' . $short_form;

                            }else{

                              $consignee_name = $consignee_id;

                            }

                            //check destination;
                            if($destination_id == '0'){

                              $destination_id = '';

                            }else{

                              $destination_id = $destination_id;

                            }

                            ?>



                              <form action="<?php echo $awb_id; ?>" method="POST">
                                  <label for="awb_number">AWB Number</label>
                                  <div class="form-group">
                                      <div class="form-line">
                                          <input type="text" id="mawb_number" class="form-control" required aria-required="true" placeholder="Enter AWB number here" name="awb_number" value="<?php echo $awb_id ?>" readonly="readonly">
                                      </div>
                                  </div>
                                  <br>
                                  <label for="shippers">From</label>
                                  <div class="form-group">
                                    <select class="form-control show-tick" name="shipper_id">

                                        <?php


                                          $query = "SELECT `shipper_id`, `name` FROM `shipper_details` WHERE 1 ORDER BY `name` ASC";
                                          $result = $get_data->getData($query);

                                          foreach ($result as $key => $res)
                                          {

                                            if($res['shipper_id'] == $shipper_id){

                                              ?>
                                                <option value="<?php echo $res['shipper_id']; ?>" selected><?php echo $res['name']; ?></option>
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

                                  <label for="consignee">To</label>
                                  <div class="form-group">
                                    <div class="form-line frmSearch">
                                      <input type="text" id="search-box" placeholder="Consignee Name" class="form-control" required aria-required="true" name="consignee_id"  autocomplete="off" value="<?php echo $consignee_name; ?>"/>
                                      <div id="suggesstion-box"></div>
                                    </div>
                                  </div>

                                  <label for="destination">Destination</label>
                                  <div class="form-group">
                                      <div class="form-line">
                                          <input type="text" id="destination" class="form-control" placeholder="Enter Destination" name="destination" required aria-required="true" value="<?php echo $destination_id; ?>">
                                      </div>
                                  </div>

                                  <label for="bag_number">Bag Number</label>
                                  <div class="form-group">
                                      <div class="form-line">
                                          <input type="text" id="bag_number" class="form-control" required aria-required="true" placeholder="Enter Bag number here" name="bag_number" value="<?php echo $bag_number; ?>">
                                      </div>
                                  </div>

                                  <label for="type">Type</label>
                                  <div class="form-group">
                                      <div class="form-line">
                                          <input type="text" id="type" class="form-control" required aria-required="true" placeholder="Enter Type here" name="type" value="<?php echo $type; ?>">
                                      </div>
                                  </div>

                                  <label for="pcs">Pcs</label>
                                  <div class="form-group">
                                      <div class="form-line">
                                          <input type="text" id="pcs" class="form-control" required aria-required="true" placeholder="Enter Quantity" name="pcs" value="<?php echo $pcs; ?>">
                                      </div>
                                  </div>

                                  <label for="a_weight">A. Weight</label>
                                  <div class="form-group">
                                      <div class="form-line">
                                          <input type="text" id="a_weight" class="form-control" required aria-required="true" placeholder="Enter A. Weight" name="a_weight" value="<?php echo $a_weight; ?>">
                                      </div>
                                  </div>

                                  <label for="b_weight">B. Weight</label>
                                  <div class="form-group">
                                      <div class="form-line">
                                          <input type="text" id="b_weight" class="form-control" required aria-required="true" placeholder="Enter B. Weight" name="b_weight" value="<?php echo $b_weight; ?>">
                                      </div>
                                  </div>

                                  <label for="price">Value</label>
                                  <div class="form-group">
                                      <div class="form-line">
                                          <input type="text" id="price" class="form-control" required aria-required="true" placeholder="Enter Value" name="price_value" value="<?php echo $value; ?>">
                                      </div>
                                  </div>

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
<!-- #END# Vertical Layout -->




</div>
</section>
<?php

  require_once('../static_references/footer.php');



?>
