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
                                List of all AWB
                            </h2>

                        </div>
                        <div class="body table-responsive">


                          <table class="table table-hover">
                                <thead>
                                    <tr class="bg-orange">
                                        <th>#</th>
                                        <th>AWB Number</th>
                                        <th>Shipper Name</th>
                                        <th>Consignee Name</th>
                                        <th>Status</th>
                                        <th>Lock Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                  <?php

                                    //declare objects
                                    $get_awb = new AWBCrudOperation();
                                    $show_pagination = new PaginationOperation();
                                    $clearence = new Clearence();


                                    $awb_page_number = $_GET['b_id'];

                                    //data clearance;
                                    $awb_page_number = $clearence->escape_string($awb_page_number);
                                    $awb_page_number = strip_tags(trim($awb_page_number));
                                    $awb_page_number = htmlentities($awb_page_number);

                                    //calculate lowest point for query database;
                                    $low_point = ($awb_page_number * 20) - 20;

                                    //fetch awb list view;
                                    $query = "SELECT awb_details.awb_id, awb_details.shipper_id, shipper_details.name, awb_details.consignee_id,
                                              awb_status.delivery_status, awb_lock.lock_status FROM awb_details INNER JOIN shipper_details ON
                                              awb_details.shipper_id=shipper_details.shipper_id INNER JOIN awb_status ON
                                              awb_details.awb_id=awb_status.awb_id INNER JOIN awb_lock ON
                                              awb_details.awb_id=awb_lock.awb_id INNER JOIN creation_details ON
                                              awb_details.timer_id=creation_details.timer_id WHERE awb_status.status_active='1' ORDER BY
                                              creation_details.creation_date DESC LIMIT 20 OFFSET $low_point";

                                    $result = $get_awb->getData($query);

                                     $counter = ($awb_page_number * 20) - 19;

                                    foreach ($result as $key => $res)
                                    {
                                      ?>
                                      <tr>
                                          <th scope="row"><?php echo $counter; ?></th>
                                          <td><a href="/powerlinebd/admin/superadmin/awb/awb-detail/<?php echo $res['awb_id']; ?>/1"><?php echo $res['awb_id']; ?></a></td>
                                          <td><a href="/powerlinebd/admin/superadmin/shippers/shipper-detail/<?php echo $res['shipper_id']; ?>/1"><?php echo $res['name']; ?></a></td>
                                          <td>
                                            <?php

                                              //check if a new or existing consignee;
                                              if(is_numeric($res['consignee_id'])){

                                                $consignee_id = $res['consignee_id'];

                                                $query2 = "SELECT name AS c_name FROM consignee_details WHERE consignee_id='$consignee_id'";

                                                $result2 = $get_awb->getData($query2);

                                                foreach ($result2 as $key2 => $res2){
                                                  ?>

                                                  <a href="/powerlinebd/admin/superadmin/consignee/consignee-detail/<?php echo $consignee_id; ?>/1"><?php echo $res2['c_name']; ?></a>

                                                  <?php
                                                }

                                              }else{

                                                ?>

                                                <?php echo $res['consignee_id'] ?>

                                                <?php

                                              }

                                            ?>
                                          </td>
                                          <td>

                                            <?php

                                              if($res['delivery_status'] == 'Created'){

                                                ?>

                                                <button type="button" data-color="deep-red" class="btn bg-red waves-effect">Created</button>

                                                <?php


                                              }elseif($res['delivery_status'] == 'On the way'){

                                                ?>

                                                <button type="button" data-color="deep-orange" class="btn bg-orange waves-effect">On The Way</button>

                                                <?php

                                              }elseif($res['delivery_status'] == 'Third Party'){

                                                ?>

                                                <button type="button" data-color="deep-blue" class="btn bg-blue waves-effect">Third Party</button>

                                                <?php

                                              }elseif($res['delivery_status'] == 'Received by branch'){

                                                ?>

                                                <button type="button" data-color="deep-blue" class="btn bg-blue waves-effect">Received by branch</button>

                                                <?php

                                              }else{

                                                ?>

                                                <button type="button" data-color="deep-green" class="btn bg-green waves-effect">Delivered</button>

                                                <?php

                                              }


                                            ?>

                                          </td>
                                          <td>

                                            <?php

                                              if($res['lock_status'] == 'locked'){

                                                ?>

                                                <button type="button" data-color="deep-red" class="btn bg-red waves-effect">Locked</button>

                                                <?php


                                              }else{

                                                ?>

                                                <button type="button" data-color="deep-green" class="btn bg-green waves-effect">Unlocked</button>

                                                <?php

                                              }


                                            ?>

                                          </td>
                                      </tr>
                                      <?php

                                      $counter++;

                                    }
                                  ?>

                                </tbody>
                            </table>


                        </div>

                        <div class="body">
                          <?php

                            //querying for calculating total page to show;
                            $query = "SELECT COUNT(awb_id) AS total_id FROM awb_details WHERE 1";

                            //calling total page number calculation function;
                            $how_many_show = $show_pagination->counting_pagination($query, '20', $awb_page_number);

                            ?>

                            <div class="alert alert-info">
                                <?php echo $how_many_show; ?>
                            </div>

                        </div>

                        <div class="body">
                           <?php

                           //calling the pagination showing class, always send current page name;
                           $show_pagination->do_pagination('awb-list');

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
