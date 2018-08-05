<?php

  require_once('../classes/authentication.php');
  include('../static_references/header.php');
  include('../static_references/search.php');
  include('../static_references/navbar.php');
  include_once('../classes/data_clearence.php');
  include_once('../classes/admin_user_crud.php');
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
                              $get_user = new UserCrudOperation();
                              $show_pagination = new PaginationOperation();
                              $clearence = new Clearence();

                              $u_id = $_GET['b_id'];

                              $user_id = $_SESSION["plbd_id"];

                              //data clearance;
                              $u_id = $clearence->escape_string($u_id);
                              $u_id = strip_tags(trim($u_id));
                              $u_id = htmlentities($u_id);



                          ?>

                            <h2 style="text-align: left;">
                                User Delete
                            </h2>



                        </div>
                        <div class="body">


                          <?php

                            $user_delete = $get_user->delete_user($u_id, $user_id);

                            if($user_delete == 'User deleted Successfully'){

                              ?>

                              <div class="alert bg-green">
                                  User deleted Successfully
                              </div>

                              <?php

                            }else{

                              ?>

                              <div class="alert bg-red">
                                  <?php echo $user_delete; ?>
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
