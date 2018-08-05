<?php

  require_once('../classes/authentication.php');
  include('../static_references/header.php');
  include('../static_references/search.php');
  include('../static_references/navbar.php');
  include_once('../classes/data_clearence.php');
  include_once('../classes/place_crud.php');
  include_once('../classes/pagination_class.php');

?>

<section class="content">
        <div class="container-fluid">

<!-- Vertical Layout -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">

                          <?php
                              //declare object;
                              $get_place = new PlaceCrudOperation();
                              $show_pagination = new PaginationOperation();
                              $clearence = new Clearence();

                              $place_id = $_GET['b_id'];

                              $user_id = $_SESSION["plbd_id"];

                              //data clearance;
                              $place_id = $clearence->escape_string($place_id);
                              $place_id = strip_tags(trim($place_id));
                              $place_id = htmlentities($place_id);

                              

                          ?>

                            <h2 style="text-align: left;">
                                Place Delete
                            </h2>



                        </div>
                        <div class="body">


                          <?php

                            $place_delete = $get_place->delete_place($place_id, $user_id);

                            if($place_delete == 'Place deleted Successfully'){

                              ?>

                              <div class="alert bg-green">
                                  Place deleted Successfully
                              </div>

                              <?php

                            }else{

                              ?>

                              <div class="alert bg-red">
                                  <?php echo $place_delete; ?>
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
