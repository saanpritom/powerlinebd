<?php

  require_once('../classes/authentication.php');
  include('../static_references/header.php');
  include('../static_references/search.php');
  include('../static_references/navbar.php');
  include_once('../classes/data_clearence.php');
  include_once('../classes/shipper_crud.php');
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
                              $get_shipper = new ShipperCrudOperation();
                              $show_pagination = new PaginationOperation();
                              $clearence = new Clearence();

                              $shipper_id = $_GET['b_id'];

                              $user_id = $_SESSION["plbd_id"];

                              //data clearance;
                              $shipper_id = $clearence->escape_string($shipper_id);
                              $shipper_id = strip_tags(trim($shipper_id));
                              $shipper_id = htmlentities($shipper_id);



                          ?>

                            <h2 style="text-align: left;">
                                Shipper Delete
                            </h2>



                        </div>
                        <div class="body">


                          <?php

                            $shipper_delete = $get_shipper->delete_shipper($shipper_id, $user_id);

                            if($shipper_delete == 'Shipper deleted Successfully'){

                              ?>

                              <div class="alert bg-green">
                                  Shipper deleted Successfully
                              </div>

                              <?php

                            }else{

                              ?>

                              <div class="alert bg-red">
                                  <?php echo $shipper_delete; ?>
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
