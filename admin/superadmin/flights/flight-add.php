<?php

  require_once('../classes/authentication.php');
  include('../static_references/header.php');
  include('../static_references/search.php');
  include('../static_references/navbar.php');
  include_once('../classes/data_validation.php');
  include_once('../classes/data_clearence.php');
  include_once('../classes/flight_crud.php');

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
                          $flight_operation = new FlightCrudOperation();

                          //mysql escape string clearence;
                          $flight_number = $clearence->escape_string($_POST['flight_number']);
                          $flight_date = $clearence->escape_string($_POST['flight_date']);
                          $flight_time = $clearence->escape_string($_POST['flight_time']);

                          //input data triming;
                          $flight_number = strip_tags(trim($flight_number));
                          $flight_date = strip_tags(trim($flight_date));
                          $flight_time = strip_tags(trim($flight_time));

                          // Escape any html characters;
                          $flight_number = htmlentities($flight_number);
                          $flight_date = htmlentities($flight_date);
                          $flight_time = htmlentities($flight_time);

                          //check refined and input values are empty and valid or not;
                          $msg = $validation->check_empty(array($flight_number, $flight_date, $flight_time));

                          if($msg != null){
                            ?>
                            <div class="alert bg-red">
                                <?php echo $msg; ?>
                            </div>
                            <?php

                          }else{

                            $user_id = $_SESSION["plbd_id"];

                            //sending all variables to branch_crud for creating new branch;
                            $new_flight = $flight_operation->create_flight($flight_number, $flight_date, $flight_time, $user_id);

                            //check if branch created properly;
                            if($new_flight == 'Successfully created a new flight'){
                              ?>
                              <div class="alert bg-green">
                                  <?php echo 'Successfully created a new flight'; ?>
                              </div>
                              <?php
                            }else{
                              ?>
                              <div class="alert bg-red">
                                  <?php echo $new_flight; ?>
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
                                Create New Flight
                            </h2>

                        </div>
                        <div class="body">
                            <form action="flight-add" method="POST">
                                <label for="flight_name">Flight Number</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" id="flight_name" class="form-control" required aria-required="true" placeholder="Enter flight number here" name="flight_number">
                                    </div>
                                </div>
                                <label for="email_address">Flight Date</label>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="material-icons">date_range</i>
                                    </span>
                                    <div class="form-line">
                                        <input type="text" class="form-control date" name="flight_date" placeholder="Ex: 30/07/2016">
                                    </div>
                                </div>
                                <label for="email_address">Flight Departure Time</label>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="material-icons">access_time</i>
                                    </span>
                                    <div class="form-line">
                                        <input type="text" class="form-control time24"  name="flight_time" placeholder="Ex: 23:59">
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
