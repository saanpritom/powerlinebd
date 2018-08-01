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


<!-- Vertical Layout -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                                Delete AWB
                            </h2>

                        </div>
                        <div class="body">

                          <?php

                          $get_data = new AWBCrudOperation();

                          $awb_id = $_GET['b_id'];

                          $user_id = $_SESSION["plbd_id"];


                          $delete_awb = $get_data->awb_delete($awb_id, $user_id);




                          if($delete_awb == 'Successfully delete AWB'){

                            ?>

                            <div class="alert bg-green">
                                <?php echo 'Successfully delete AWB'; ?>
                            </div>

                            <?php


                          }else{

                            ?>

                            <div class="alert bg-red">
                                <?php echo $delete_awb; ?>
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
