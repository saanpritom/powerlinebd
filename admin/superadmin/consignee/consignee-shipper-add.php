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
                          $consignee_operation = new ConsigneeCrudOperation();

                          $user_id = $_SESSION["plbd_id"];

                          $consignee_id = $_GET['b_id'];

                          //sending all variables to branch_crud for creating new branch;
                          $new_shipper = $consignee_operation->newShipper($_POST['shipper'], $user_id, $consignee_id);

                          //check if branch created properly;
                          if($new_shipper == 'Successfully added new Shippers'){
                            ?>
                            <div class="alert bg-green">
                                <?php echo 'Successfully added new Shippers'; ?>
                            </div>
                            <?php
                          }else{
                            ?>
                            <div class="alert bg-red">
                                <?php echo $new_shipper; ?>
                            </div>
                            <?php
                          }



                      ?>

                      <a href="/powerlinebd/admin/superadmin/consignee/consignee-detail/<?php echo $consignee_id; ?>/1" type="button" class="btn bg-teal waves-effect">Go back</a>

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

                              $consignee_id = $_GET['b_id'];
                              $get_consignee = new ConsigneeCrudOperation();

                            ?>

                            <form action="<?php echo $consignee_id; ?>" method="POST">

                              <div class="demo-checkbox">

                                <?php

                                  $counter = 1;

                                  //get all the shipper;
                                  $query = "SELECT shipper_id, name FROM shipper_details WHERE 1 ORDER BY name ASC";
                                  $result = $get_consignee->getdata($query);

                                  foreach($result as $key => $res){

                                    $temp_shipper_id = $res['shipper_id'];

                                    //check if the shipper is already related to the consignee;
                                    $sub_query = "SELECT sl_num FROM consignee_shipper_relation WHERE
                                                  shipper_id='$temp_shipper_id' AND consignee_id='$consignee_id'";

                                                  //echo $sub_query;

                                    $sub_result = $get_consignee->getShipper($sub_query);

                                    if($sub_result){

                                      ?>
                                      <li>
                                      <input type="checkbox" id="<?php echo $counter; ?>" class="filled-in chk-col-deep-orange" value="<?php echo $res['shipper_id']; ?>" name="shipper[]" checked>
                                      <label for="<?php echo $counter; ?>"><?php echo $res['name']; ?></label>
                                    </li>
                                      <?php

                                    }else{

                                      ?>
                                      <li>
                                      <input type="checkbox" id="<?php echo $counter; ?>" class="filled-in chk-col-deep-orange" value="<?php echo $res['shipper_id']; ?>" name="shipper[]">
                                      <label for="<?php echo $counter; ?>"><?php echo $res['name']; ?></label>
                                    </li>
                                      <?php

                                    }

                                    $counter++;

                                  }


                                 ?>


                              </div>

                              <br>
                              <input type="submit" name="submit" class="btn bg-deep-orange waves-effect m-t-15" value="Add">

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
