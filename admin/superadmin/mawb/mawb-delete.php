<?php

  require_once('../classes/authentication.php');
  include('../static_references/header.php');
  include('../static_references/search.php');
  include('../static_references/navbar.php');
  include_once('../classes/data_clearence.php');
  include_once('../classes/mawb_crud.php');
  include_once('../classes/pagination_class.php');

?>

<section class="content">
        <div class="container-fluid">

<!-- Vertical Layout -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">



                            <h2 style="text-align: left;">
                                Master AWB Delete <br/>
                            </h2>

                          <?php
                              //declare object;
                              $get_mawb = new MAWBCrudOperation();
                              $show_pagination = new PaginationOperation();
                              $clearence = new Clearence();

                              $mawb_id = $_GET['b_id'];

                              $user_id = $_SESSION["plbd_id"];

                              //data clearance;
                              $mawb_id = $clearence->escape_string($mawb_id);
                              $mawb_id = strip_tags(trim($mawb_id));
                              $mawb_id = htmlentities($mawb_id);


                              $delete_mawb = $get_mawb->mawb_delete($mawb_id, $user_id);

                              if($delete_mawb == 'Successfully deleted MAWB'){

                                ?>

                                <div class="alert bg-green">
                                    Successfully deleted MAWB
                                </div>

                                <?php

                              }else{

                                ?>

                                <div class="alert bg-red">
                                    <?php echo $delete_mawb; ?>
                                </div>

                                <?php

                              }

                          ?>



                        </div>

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
