<?php

  require_once('../classes/authentication.php');
  include('../static_references/header.php');
  include('../static_references/search.php');
  include('../static_references/navbar.php');
  include_once('../classes/data_validation.php');
  include_once('../classes/data_clearence.php');
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

                          $place_id = $_GET['b_id'];
                          $user_id = $_SESSION['plbd_id'];

                          //declare classes for checking;
                          $validation = new Validation();
                          $clearence = new Clearence();
                          $place_operation = new PlaceCrudOperation();

                          //mysql escape string clearence;
                          $full_name = $clearence->escape_string($_POST['full_name']);
                          $short_form = $clearence->escape_string($_POST['short_form']);
                          $place_type = $clearence->escape_string($_POST['place_type']);

                          //input data triming;
                          $full_name = strip_tags(trim($full_name));
                          $short_form = strip_tags(trim($short_form));
                          $place_type = strip_tags(trim($place_type));

                          // Escape any html characters;
                          $full_name = htmlentities($full_name);
                          $short_form = htmlentities($short_form);
                          $place_type = htmlentities($place_type);

                          //check refined and input values are empty and valid or not;
                          $msg = $validation->check_empty(array($full_name, $short_form, $place_type));

                          if($msg != null){
                            ?>
                            <div class="alert bg-red">
                                <?php echo $msg; ?>
                            </div>
                            <?php

                          }else{

                            //sending all variables to branch_crud for creating new branch;
                            $new_place = $place_operation->update_place($place_id, $full_name, $short_form, $place_type, $user_id);

                            //check if branch created properly;
                            if($new_place == 'Place updated Successfully'){
                              ?>
                              <div class="alert bg-green">
                                  <?php echo 'Place updated Successfully'; ?>
                              </div>
                              <?php
                            }else{
                              ?>
                              <div class="alert bg-red">
                                  <?php echo $new_place; ?>
                              </div>
                              <?php
                            }

                          }

                      ?>

                      <a href="/powerlinebd/admin/superadmin/places/place-detail/<?php echo $place_id; ?>/1" type="button" class="btn bg-teal waves-effect">Go back</a>

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
                                Edit Place
                            </h2>

                        </div>
                        <div class="body">
                            <?php

                            $place_id = $_GET['b_id'];

                            $get_place = new PlaceCrudOperation();

                            $query = "SELECT `full_name`, `short_form`, `type`
                                      FROM `origin_destination_details` WHERE
                                      `o_id_id`='$place_id'";

                            $result = $get_place->getData($query);

                            foreach($result as $key => $res){

                              $full_name = $res['full_name'];
                              $short_form = $res['short_form'];
                              $type = $res['type'];

                            }

                            ?>
                            <form action="<?php echo $place_id; ?>" method="POST">
                                <label for="full_name">Name of the Place</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" id="full_name" class="form-control" required aria-required="true" placeholder="Enter Place Name here" name="full_name" value="<?php echo $full_name; ?>">
                                    </div>
                                </div>
                                <label for="email_address">Short Form</label>
                                <div class="input-group">
                                    <div class="form-line">
                                        <input type="text" class="form-control date" name="short_form" placeholder="Enter Short Form" value="<?php echo $short_form; ?>">
                                    </div>
                                </div>
                                <label for="place-type">Place Type</label>
                                <div class="form-group">
                                  <select class="form-control show-tick" name="place_type">
                                      <?php

                                          if($type == 'origin'){

                                            ?>

                                            <option value="origin" selected>Origin</option>

                                            <?php

                                          }else{

                                            ?>

                                            <option value="origin">Origin</option>

                                            <?php

                                          }

                                          if($type == 'destination'){

                                            ?>

                                            <option value="destination" selected>Destination</option>

                                            <?php

                                          }else{

                                            ?>

                                            <option value="destination">Destination</option>

                                            <?php

                                          }

                                          if($type == 'country'){

                                            ?>

                                            <option value="country" selected>Country</option>

                                            <?php

                                          }else{

                                            ?>

                                            <option value="country">Country</option>

                                            <?php

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
