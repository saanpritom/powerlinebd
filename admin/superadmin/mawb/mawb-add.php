<?php

  require_once('../classes/authentication.php');
  include('../static_references/header.php');
  include('../static_references/search.php');
  include('../static_references/navbar.php');
  include_once('../classes/data_validation.php');
  include_once('../classes/data_clearence.php');
  include_once('../classes/mawb_crud.php');

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
                          $mawb_operation = new MAWBCrudOperation();

                          //mysql escape string clearence;
                          $mawb_number = $clearence->escape_string($_POST['mawb_number']);

                          //input data triming;
                          $mawb_number = strip_tags(trim($mawb_number));

                          // Escape any html characters;
                          $mawb_number = htmlentities($mawb_number);

                          //check refined and input values are empty and valid or not;
                          $msg = $validation->check_empty(array($mawb_number));

                          if($msg != null){
                            ?>
                            <div class="alert bg-red">
                                <?php echo $msg; ?>
                            </div>
                            <?php

                          }else{

                            $user_id = $_SESSION["plbd_id"];

                            //sending all variables to branch_crud for creating new branch;
                            $new_mawb = $mawb_operation->create_mawb($mawb_number, $user_id);

                            //check if branch created properly;
                            if($new_mawb == 'Successfully created a new MAWB'){
                              ?>
                              <div class="alert bg-green">
                                  <?php echo 'Successfully created a new MAWB'; ?>
                              </div>
                              <?php
                            }else{
                              ?>
                              <div class="alert bg-red">
                                  <?php echo $new_mawb; ?>
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
                                Create New Master AWB
                            </h2>

                        </div>
                        <div class="body">
                            <form action="mawb-add" method="POST">
                                <label for="flight_name">MAWB Number</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" id="mawb_number" class="form-control" required aria-required="true" placeholder="Enter MAWB number here" name="mawb_number">
                                    </div>
                                </div>
                                <br>

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
