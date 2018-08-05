<?php

  require_once('../classes/authentication.php');
  include('../static_references/header.php');
  include('../static_references/search.php');
  include('../static_references/navbar.php');
  include_once('../classes/data_clearence.php');
  include_once('../classes/branch_crud.php');
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
                              $get_branch = new BranchCrudOperation();
                              $show_pagination = new PaginationOperation();
                              $clearence = new Clearence();

                              $branch_id = $_GET['b_id'];

                              $user_id = $_SESSION["plbd_id"];

                              //data clearance;
                              $branch_id = $clearence->escape_string($branch_id);
                              $branch_id = strip_tags(trim($branch_id));
                              $branch_id = htmlentities($branch_id);



                          ?>

                            <h2 style="text-align: left;">
                                Branch Delete
                            </h2>



                        </div>
                        <div class="body">


                          <?php

                            $branch_delete = $get_branch->delete_branch($branch_id, $user_id);

                            if($branch_delete == 'Branch deleted Successfully'){

                              ?>

                              <div class="alert bg-green">
                                  Branch deleted Successfully
                              </div>

                              <?php

                            }else{

                              ?>

                              <div class="alert bg-red">
                                  <?php echo $branch_delete; ?>
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
