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
                          $designation = $clearence->escape_string($_POST['destination']);
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
                            $new_awb = $awb_operation->create_awb($awb_number, $shipper_id, $consignee_name, $bag_number, $type, $pcs, $a_weight, $b_weight, $price_value, $user_id);

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
                                Create New AWB
                            </h2>

                        </div>
                        <div class="body">

                          <?php

                          $get_data = new AWBCrudOperation();

                          ?>



                            <form action="awb-add" method="POST">
                                <label for="awb_number">AWB Number</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" id="mawb_number" class="form-control" required aria-required="true" placeholder="Enter AWB number here" name="awb_number">
                                    </div>
                                </div>
                                <br>
                                <label for="shippers">From</label>
                                <div class="form-group">
                                  <select class="form-control show-tick" name="shipper_id">
                                      <option value="0">-- Select Shippers --</option>

                                      <?php


                                        $query = "SELECT `shipper_id`, `name` FROM `shipper_details` WHERE 1 ORDER BY `name` ASC";
                                        $result = $get_data->getData($query);

                                        foreach ($result as $key => $res)
                                        {
                                          ?>
                                          <option value="<?php echo $res['shipper_id']; ?>"><?php echo $res['name']; ?></option>
                                          <?php
                                        }
                                      ?>
                                  </select>
                                </div>
                                <br>

                                <label for="consignee">To</label>
                                <div class="form-group">
                                  <div class="form-line frmSearch">
                                    <input type="text" id="search-box" placeholder="Consignee Name" class="form-control" required aria-required="true" name="consignee_id" />
                                    <div id="suggesstion-box"></div>
                                  </div>
                                </div>

                                <label for="destination">Destination</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" id="destination" class="form-control" required aria-required="true" placeholder="Enter Destination" name="destination">
                                    </div>
                                </div>

                                <label for="bag_number">Bag Number</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" id="bag_number" class="form-control" required aria-required="true" placeholder="Enter Bag number here" name="bag_number">
                                    </div>
                                </div>

                                <label for="type">Type</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" id="type" class="form-control" required aria-required="true" placeholder="Enter Type here" name="type">
                                    </div>
                                </div>

                                <label for="pcs">Pcs</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" id="pcs" class="form-control" required aria-required="true" placeholder="Enter Quantity" name="pcs">
                                    </div>
                                </div>

                                <label for="a_weight">A. Weight</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" id="a_weight" class="form-control" required aria-required="true" placeholder="Enter A. Weight" name="a_weight">
                                    </div>
                                </div>

                                <label for="b_weight">B. Weight</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" id="b_weight" class="form-control" required aria-required="true" placeholder="Enter B. Weight" name="b_weight">
                                    </div>
                                </div>

                                <label for="price">Value</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" id="price" class="form-control" required aria-required="true" placeholder="Enter Value" name="price_value">
                                    </div>
                                </div>

                                <script src="https://code.jquery.com/jquery-2.1.1.min.js" type="text/javascript"></script>



                                <script type="text/javascript">


                                    // AJAX call for autocomplete of Consignee
                                    $(document).ready(function(){
                                      $("#search-box").keyup(function(){
                                        $.ajax({
                                        type: "POST",
                                        url: "ajax-call/fetch-consignee.php",
                                        data:'keyword='+$(this).val(),
                                        beforeSend: function(){
                                          $("#search-box").css("background","#FFF url(LoaderIcon.gif) no-repeat 165px");
                                        },
                                        success: function(data){
                                          $("#suggesstion-box").show();
                                          $("#suggesstion-box").html(data);
                                          $("#search-box").css("background","#FFF");
                                        }
                                        });
                                      });
                                    });
                                    //To select country name
                                    function selectCountry(val) {
                                    $("#search-box").val(val);
                                    $("#suggesstion-box").hide();
                                    }

                                </script>




                                <input type="submit" name="submit" class="btn bg-deep-orange waves-effect m-t-15" value="Create">
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
