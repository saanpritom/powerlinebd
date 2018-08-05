<?php

  require_once('../classes/authentication.php');
  include('../static_references/header.php');
  include('../static_references/search.php');
  include('../static_references/navbar.php');
  include_once('../classes/data_clearence.php');
  include_once('../classes/consignee_crud.php');
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
                              $get_consignee = new ConsigneeCrudOperation();
                              $show_pagination = new PaginationOperation();
                              $clearence = new Clearence();

                              $consignee_id = $_GET['b_id'];

                              $user_id = $_SESSION["plbd_id"];

                              //data clearance;
                              $consignee_id = $clearence->escape_string($consignee_id);
                              $consignee_id = strip_tags(trim($consignee_id));
                              $consignee_id = htmlentities($consignee_id);



                          ?>

                            <h2 style="text-align: left;">
                                Consignee Delete
                            </h2>



                        </div>
                        <div class="body">


                          <?php

                            $consignee_delete = $get_consignee->delete_consignee($consignee_id, $user_id);

                            if($consignee_delete == 'Consignee deleted Successfully'){

                              ?>

                              <div class="alert bg-green">
                                  Consignee deleted Successfully
                              </div>

                              <?php

                            }else{

                              ?>

                              <div class="alert bg-red">
                                  <?php echo $consignee_delete; ?>
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
