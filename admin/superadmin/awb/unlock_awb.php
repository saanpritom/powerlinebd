<?php

  require_once('../classes/authentication.php');
  include('../static_references/header.php');
  include('../static_references/search.php');
  include('../static_references/navbar.php');
  include_once('../classes/data_clearence.php');
  include_once('../classes/awb_crud.php');
  include_once('../classes/pagination_class.php');

?>

<section class="content">
        <div class="container-fluid">

<!-- Vertical Layout -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                                Unlock AWB
                            </h2>

                        </div>


                        <div class="body">

                          <?php

                          $get_data = new AWBCrudOperation();

                          $awb_id = $_GET['b_id'];

                          $exploded_url = explode('/', $awb_id);

                          $awb_page_number = end($exploded_url);

                          $awb_id = prev($exploded_url);

                          if(!is_numeric($awb_id) and is_numeric($awb_page_number)){



                            $awb_id = $awb_page_number;
                            //check if it is in lock state
                            $query = "SELECT lock_status FROM awb_lock WHERE awb_id='$awb_id'";

                            $result = $get_data->getData($query);

                            foreach ($result as $key => $res) {

                              $lock_status = $res['lock_status'];

                            }

                            if($lock_status == 'locked'){

                              ?>

                              <div class="alert bg-red">

                                Do you really want to unlock this?

                              </div>

                              <a href="/powerlinebd/admin/superadmin/awb/unlock_awb/<?php echo $awb_id ?>/yes" type="button" class="btn bg-green waves-effect">Yes</a>
                              <a href="/powerlinebd/admin/superadmin/awb/awb-detail/<?php echo $awb_id ?>/1" type="button" class="btn bg-red waves-effect">No</a>


                              <?php

                            }else{
                              ?>

                              <div class="alert bg-red">

                                You shouldn't try this. This AWB is in unlocked state. This is a warning.

                              </div>


                              <?php


                            }

                          }elseif(is_numeric($awb_id) and $awb_page_number='yes'){



                            $user_id = $_SESSION["plbd_id"];

                            $awb_unlock = $get_data->unlock_awb($awb_id, $user_id);

                            if($awb_unlock == 'Successfully unlocked AWB'){

                              ?>

                              <div class="alert bg-green">

                                <?php echo $awb_unlock; ?>

                              </div>

                              <?php

                            }else{

                              ?>

                              <div class="alert bg-red">

                                <?php echo $awb_unlock; ?>

                              </div>

                              <?php

                            }


                          }else{

                            ?>

                            <div class="alert bg-red">

                              You shouldn't try this. Just follow the conventional method. This is a warning.

                            </div>

                            <?php

                          }


                          ?>

                          <a href="/powerlinebd/admin/superadmin/awb/awb-detail/<?php echo $awb_id; ?>/1" type="button" class="btn bg-teal waves-effect">Go back</a>


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
