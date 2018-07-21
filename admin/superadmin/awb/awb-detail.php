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

                          <?php
                              //declare object;
                              $get_awb = new AWBCrudOperation();
                              $show_pagination = new PaginationOperation();
                              $clearence = new Clearence();

                              $awb_id = $_GET['b_id'];

                              //data clearance;
                              $awb_id = $clearence->escape_string($awb_id);
                              $awb_id = strip_tags(trim($awb_id));
                              $awb_id = htmlentities($awb_id);

                              //extracting branch_id and page number from complecated URL;
                              $exploded_url = explode('/', $awb_id);

                              $awb_page_number = end($exploded_url);

                              $awb_id = prev($exploded_url);

                          ?>

                            <h2 style="text-align: left;">
                                AWB Details
                            </h2>
                            <div style="text-align: right;">
                              <a href="/powerlinebd/admin/superadmin/awb/third-party/<?php echo $awb_id ?>" type="button" class="btn bg-red waves-effect">Add Third Party Delivery</a>

                              <?php

                                //check if AWB is locked state or not;
                                $query = "SELECT lock_status FROM awb_lock WHERE awb_id='$awb_id'";

                                $result = $get_awb->getData($query);

                                foreach ($result as $key => $res){

                                  $lock_status = $res['lock_status'];

                                }

                                if($lock_status == 'unlocked'){

                                  ?>
                                  <a href="/powerlinebd/admin/superadmin/awb/awb-edit/<?php echo $awb_id ?>" type="button" class="btn bg-blue waves-effect">Edit</a>

                                  <?php
                                }else{
                                  ?>
                                  <a href="#" type="button" class="btn bg-blue waves-effect">Locked</a>

                                  <?php
                                }

                              ?>

                              <button type="button" data-color="deep-orange" data-toggle="modal" data-target="#mdModal" class="btn bg-deep-orange waves-effect">Delete</button>

                            </div>


                        </div>
                        <div class="body">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs" role="tablist">
                              <li role="presentation">
                                  <a href="#profile_with_icon_title" data-toggle="tab">
                                      <i class="material-icons">library_books</i> Detailed Information
                                  </a>
                              </li>
                              <li role="presentation" class="active">
                                  <a href="#home_with_icon_title" data-toggle="tab">
                                      <i class="material-icons">face</i> Connected MAWB's & Flights
                                  </a>
                              </li>
                              <li role="presentation">
                                  <a href="#shipper_with_icon_title" data-toggle="tab">
                                      <i class="material-icons">list</i> AWB Transaction History
                                  </a>
                              </li>
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content">

                                <div role="tabpanel" class="tab-pane fade in active" id="home_with_icon_title">


                                  <div class="body table-responsive">


                                    <table class="table table-hover">
                                          <thead>
                                              <tr class="bg-orange">
                                                  <th>#</th>
                                                  <th>MAWB Number</th>
                                                  <th>Flight Number</th>
                                                  <th>Next Branch</th>
                                                  <th>Employer Name</th>
                                                  <th>Branch Name</th>
                                                  <th>Creation Date</th>
                                                  <th>Creation Time</th>
                                              </tr>
                                          </thead>
                                          <tbody>

                                            <?php

                                            //calculate lowest point for query database;
                                            $low_point = ($awb_page_number * 20) - 20;

                                              $query = "SELECT awb_mawb_flight_relation.mawb_id, mawb_details.mawb_number, awb_mawb_flight_relation.flight_id, awb_mawb_flight_relation.next_branch,
                                                        admin_details.name AS admin_name, admin_details.admin_id, office_branch.name AS office_branch,
                                                        office_branch.branch_id, creation_details.creation_date,
                                                        creation_details.creation_time FROM awb_mawb_flight_relation
                                                        INNER JOIN awb_status ON awb_mawb_flight_relation.awb_id=awb_status.awb_id
                                                        INNER JOIN mawb_details ON awb_mawb_flight_relation.mawb_id=mawb_details.mawb_id
                                                        INNER JOIN admin_details ON awb_status.user_id=admin_details.admin_id
                                                        INNER JOIN office_branch ON admin_details.branch_id=office_branch.branch_id
                                                        INNER JOIN creation_details ON awb_status.timer_id=creation_details.timer_id WHERE
                                                        awb_mawb_flight_relation.awb_id = '$awb_id'
                                                        ORDER BY creation_details.creation_date DESC LIMIT 20 OFFSET $low_point";

                                              $result = $get_awb->getData($query);

                                              $counter = ($awb_page_number * 20) - 19;

                                              foreach($result as $key => $res){

                                                ?>

                                                <tr>
                                                  <td><?php echo $counter; ?></td>
                                                  <td><a href="/powerlinebd/admin/superadmin/mawb/mawb-detail/<?php echo $res['mawb_id']; ?>"><?php echo $res['mawb_id']; ?></a></td>
                                                  <td><a href="/powerlinebd/admin/superadmin/flights/flight-detail/<?php echo $res['flight_id']; ?>"><?php echo $res['flight_id']; ?></a></td>
                                                  <td>

                                                    <?php

                                                      //check if next branch is a branch or direct consignee;
                                                      if(is_numeric($res['next_branch'])){

                                                        $t_b_id = $res['next_branch'];

                                                        $query2 = "SELECT name AS b_name FROM office_branch WHERE branch_id='$t_b_id'";

                                                        $result2 = $get_awb->getData($query2);

                                                        foreach($result2 as $key2 => $res2){

                                                          ?>

                                                          <a href="/powerlinebd/admin/superadmin/office_management/branch-detail/<?php echo $t_b_id; ?>/1"><?php echo $res2['b_name']; ?></a>

                                                          <?php

                                                        }


                                                      }else{

                                                        $res['next_branch'];

                                                      }


                                                    ?>

                                                  </td>
                                                  <td><a href="/powerlinebd/admin/superadmin/office_management/user-detail/<?php echo $res['admin_id']; ?>"><?php echo $res['admin_name']; ?></a></td>
                                                  <td><a href="/powerlinebd/admin/superadmin/office_management/branch-detail/<?php echo $res['branch_id']; ?>/1"><?php echo $res['office_branch']; ?></a></td>
                                                  <td><?php echo $res['creation_date']; ?></td>
                                                  <td><?php echo $res['creation_time']; ?></td>
                                                </tr>


                                                <?php
                                                $counter++;
                                              }

                                            ?>

                                          </tbody>
                                      </table>

                                      <?php

                                        //pagination;

                                        //querying for calculating total page to show;
                                        $query = "SELECT COUNT(mawb_id) AS total_id FROM awb_mawb_flight_relation WHERE awb_id='$awb_id'";

                                        //calling total page number calculation function;
                                        $how_many_show = $show_pagination->counting_pagination($query, '20', $awb_page_number);

                                        ?>

                                        <div class="alert alert-info">
                                            <?php echo $how_many_show; ?>
                                        </div>

                                        <?php

                                        //calling the pagination showing class, always send current page name;
                                        $show_pagination->do_pagination('awb-detail');

                                         ?>

                                  </div>

                                </div>

                                <div role="tabpanel" class="tab-pane fade" id="profile_with_icon_title">

                                  <div class="body table-responsive">


                                    <table class="table table-hover">
                                          <thead>
                                              <tr class="bg-orange">
                                                  <th>#</th>
                                                  <th>Field Name</th>
                                                  <th>Field Data</th>
                                              </tr>
                                          </thead>
                                          <tbody>

                                            <?php

                                              //fetch a.weight and b.weight
                                              $query = "SELECT `a.weight`, `b.weight` FROM awb_details WHERE awb_id='$awb_id'";

                                              $result = $get_awb->getData($query);

                                              foreach($result as $key => $res){

                                                $a_weight = $res['a.weight'];

                                                $b_weight = $res['b.weight'];

                                              }

                                              $query = "SELECT
                                                        awb_details.shipper_id,
                                                        shipper_details.name AS shipper_name,
                                                        awb_details.consignee_id,
                                                        awb_details.destination_id,
                                                        awb_details.bag_number,
                                                        awb_details.type,
                                                        awb_details.pcs,
                                                        awb_details.value,
                                                        creation_details.creation_date,
                                                        creation_details.creation_time,
                                                        admin_details.name AS admin_name,
                                                        office_branch.name AS branch_name,
                                                        awb_lock.lock_status,
                                                        awb_mawb_flight_relation.mawb_id,
                                                        awb_mawb_flight_relation.flight_id,
                                                        awb_mawb_flight_relation.next_branch,
                                                        mawb_details.mawb_number,
                                                        awb_status.delivery_status,
                                                        awb_third_party.third_party_name,
                                                        awb_third_party.third_party_address,
                                                        awb_third_party.third_party_number,
                                                        awb_third_party.third_party_destination
                                                        FROM awb_details
                                                        INNER JOIN shipper_details ON awb_details.shipper_id=shipper_details.shipper_id
                                                        INNER JOIN awb_status ON awb_details.awb_id=awb_status.awb_id
                                                        INNER JOIN creation_details ON awb_details.timer_id=creation_details.timer_id
                                                        INNER JOIN admin_details ON awb_details.user_id=admin_details.admin_id
                                                        INNER JOIN office_branch ON admin_details.branch_id=office_branch.branch_id
                                                        INNER JOIN awb_lock ON awb_details.awb_id=awb_lock.awb_id
                                                        LEFT JOIN awb_mawb_flight_relation ON awb_details.awb_id=awb_mawb_flight_relation.awb_id
                                                        LEFT JOIN mawb_details ON awb_mawb_flight_relation.mawb_id=mawb_details.mawb_id
                                                        LEFT JOIN awb_third_party ON awb_details.awb_id=awb_third_party.awb_id
                                                        WHERE awb_details.awb_id='$awb_id' AND awb_status.status_active='1'";

                                              $result = $get_awb->getData($query);

                                              foreach($result as $key => $res){

                                                ?>

                                                <tr>
                                                  <th>1</th>
                                                  <th>AWB Number</th>
                                                  <th><?php echo $awb_id; ?></th>
                                                </tr>

                                                <tr>
                                                  <th>2</th>
                                                  <th>Shipper Name</th>
                                                  <th><a href="/powerlinebd/admin/superadmin/shippers/shipper-detail/<?php echo $res['shipper_id']; ?>/1"><?php echo $res['shipper_name']; ?></a></th>
                                                </tr>

                                                <tr>
                                                  <th>3</th>
                                                  <th>Consignee Name</th>
                                                  <th>

                                                    <?php

                                                      if(is_numeric($res['consignee_id'])){

                                                        $consignee_id = $res['consignee_id'];

                                                        $query2 = "SELECT name, address FROM consignee_details WHERE consignee_id='$consignee_id'";

                                                        $result2 = $get_awb->getData($query2);

                                                        foreach($result2 as $key2 => $res2){

                                                          $consignee_name = $res2['name'];

                                                          $consignee_address = $res2['address'];


                                                        }

                                                        ?>

                                                        <a href="/powerlinebd/admin/superadmin/consignee/consignee-detail/<?php echo $consignee_id; ?>/1"><?php echo $consignee_name; ?></a>

                                                        <?php


                                                      }else{

                                                        echo $res['consignee_id'];

                                                      }


                                                    ?>

                                                  </th>
                                                </tr>

                                                <tr>
                                                  <th>4</th>
                                                  <th>Destination</th>
                                                  <th>

                                                    <?php


                                                      if($res['destination_id'] == '0'){

                                                        echo $consignee_address;


                                                      }else{

                                                        echo $res['destination_id'];

                                                      }


                                                    ?>

                                                  </th>
                                                </tr>

                                                <tr>
                                                  <th>5</th>
                                                  <th>Bag Number</th>
                                                  <th><?php echo $res['bag_number']; ?></th>
                                                </tr>

                                                <tr>
                                                  <th>6</th>
                                                  <th>Type</th>
                                                  <th><?php echo $res['type']; ?></th>
                                                </tr>

                                                <tr>
                                                  <th>7</th>
                                                  <th>Pcs</th>
                                                  <th><?php echo $res['pcs']; ?></th>
                                                </tr>

                                                <tr>
                                                  <th>8</th>
                                                  <th>A. Weight</th>
                                                  <th><?php echo $a_weight; ?></th>
                                                </tr>

                                                <tr>
                                                  <th>9</th>
                                                  <th>B. Weight</th>
                                                  <th><?php echo $b_weight; ?></th>
                                                </tr>

                                                <tr>
                                                  <th>8</th>
                                                  <th>Value</th>
                                                  <th><?php echo $res['value']; ?></th>
                                                </tr>

                                                <tr>
                                                  <th>9</th>
                                                  <th>Creation Date</th>
                                                  <th><?php echo $res['creation_date']; ?></th>
                                                </tr>

                                                <tr>
                                                  <th>10</th>
                                                  <th>Creation Time</th>
                                                  <th><?php echo $res['creation_time']; ?></th>
                                                </tr>

                                                <tr>
                                                  <th>11</th>
                                                  <th>Created by</th>
                                                  <th><a href="/powerlinebd/admin/superadmin/office_management/user-detail/<?php echo $res['admin_id']; ?>"><?php echo $res['admin_name']; ?></a></th>
                                                </tr>

                                                <tr>
                                                  <th>12</th>
                                                  <th>Origin Branch</th>
                                                  <th><a href="/powerlinebd/admin/superadmin/office_management/branch-detail/<?php echo $res['branch_id']; ?>/1"><?php echo $res['branch_name']; ?></a></th>
                                                </tr>

                                                <tr>
                                                  <th>13</th>
                                                  <th>Lock Status</th>
                                                  <th><?php echo $res['lock_status']; ?></th>
                                                </tr>

                                                <tr>
                                                  <th>14</th>
                                                  <th>Current MAWB</th>
                                                  <th>

                                                    <?php

                                                      if($res['lock_status'] == 'unlocked'){

                                                        echo 'No MAWB Assigned';

                                                      }else{

                                                        ?>

                                                        <a href="/powerlinebd/admin/superadmin/mawb/mawb-detail/<?php echo $res['mawb_id']; ?>"><?php echo $res['mawb_number']; ?></a>

                                                        <?php

                                                      }

                                                    ?>

                                                  </th>
                                                </tr>

                                                <tr>
                                                  <th>15</th>
                                                  <th>Flight Number</th>
                                                  <th><?php echo $res['flight_id']; ?></th>
                                                </tr>

                                                <tr>
                                                  <th>16</th>
                                                  <th>Next Branch</th>
                                                  <th>

                                                    <?php

                                                      if($res['lock_status'] == 'unlocked'){

                                                        echo 'Not set yet';

                                                      }else{

                                                        echo $res['next_branch'];

                                                      }

                                                     ?>

                                                  </th>
                                                </tr>

                                                <tr>
                                                  <th>17</th>
                                                  <th>Delivery Status</th>
                                                  <th><?php echo $res['delivery_status']; ?></th>
                                                </tr>

                                                <tr>
                                                  <th>18</th>
                                                  <th>Third Party Name</th>
                                                  <th><?php echo $res['third_party_name']; ?></th>
                                                </tr>

                                                <tr>
                                                  <th>19</th>
                                                  <th>Third Party Address</th>
                                                  <th><?php echo $res['third_party_address']; ?></th>
                                                </tr>

                                                <tr>
                                                  <th>20</th>
                                                  <th>Third Party Number</th>
                                                  <th><?php echo $res['third_party_number']; ?></th>
                                                </tr>

                                                <tr>
                                                  <th>21</th>
                                                  <th>Third Party Destination</th>
                                                  <th><?php echo $res['third_party_destination']; ?></th>
                                                </tr>
                                                <?php

                                              }

                                            ?>

                                          </tbody>
                                      </table>


                                  </div>

                                </div>

                                <div role="tabpanel" class="tab-pane fade in" id="shipper_with_icon_title">


                                  <div class="body table-responsive">


                                    <table class="table table-hover">
                                          <thead>
                                            <tr class="bg-orange">
                                                <th>#</th>
                                                <th>Delivery Status</th>
                                                <th>Date</th>
                                                <th>Time</th>
                                                <th>Employee Name</th>
                                                <th>Branch Name</th>
                                            </tr>
                                          </thead>
                                          <tbody>

                                            <?php

                                            //calculate lowest point for query database;
                                            $low_point = ($awb_page_number * 20) - 20;

                                            $query = "SELECT awb_status.delivery_status, admin_details.name AS admin_name, admin_details.admin_id,
                                                      office_branch.name AS branch_name, office_branch.branch_id, creation_details.creation_date,
                                                      creation_details.creation_time FROM awb_status
                                                      INNER JOIN admin_details ON awb_status.user_id=admin_details.admin_id
                                                      INNER JOIN office_branch ON admin_details.branch_id=office_branch.branch_id
                                                      INNER JOIN creation_details ON awb_status.timer_id=creation_details.timer_id
                                                      WHERE awb_status.awb_id='$awb_id'
                                                      ORDER BY creation_details.creation_date ASC LIMIT 20 OFFSET $low_point";

                                              $result = $get_awb->getData($query);

                                              $counter = ($awb_page_number * 20) - 19;

                                              foreach($result as $key => $res){

                                                ?>

                                                <tr>
                                                  <td><?php echo $counter; ?></td>
                                                  <td><?php echo $res['delivery_status']; ?></td>
                                                  <td><?php echo $res['creation_date']; ?></td>
                                                  <td><?php echo $res['creation_time']; ?></td>
                                                  <td><a href="/powerlinebd/admin/superadmin/office_management/user-detail/<?php echo $res['admin_id']; ?>"><?php echo $res['admin_name']; ?></a></td>
                                                  <td><a href="/powerlinebd/admin/superadmin/office_management/branch-detail/<?php echo $res['branch_id']; ?>/1"><?php echo $res['branch_name']; ?></a></td>

                                                </tr>


                                                <?php
                                                $counter++;
                                              }

                                            ?>

                                          </tbody>
                                      </table>

                                      <?php

                                        //pagination;

                                        //querying for calculating total page to show;
                                        $query = "SELECT count(delivery_status) AS total_id FROM awb_status WHERE awb_id='$awb_id'";

                                        //calling total page number calculation function;
                                        $how_many_show = $show_pagination->counting_pagination($query, '20', $awb_page_number);

                                        ?>

                                        <div class="alert alert-info">
                                            <?php echo $how_many_show; ?>
                                        </div>

                                        <?php

                                        //calling the pagination showing class, always send current page name;
                                        $show_pagination->do_pagination('awb-detail');

                                         ?>

                                  </div>

                                </div>

                            </div>
                        </div>





                        <div class="modal fade in" id="mdModal" tabindex="-1" role="dialog" >
                        <div class="modal-dialog" role="document">
                            <div class="modal-content modal-col-deep-orange">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="defaultModalLabel">Delete Shipper?</h4>
                                </div>
                                <div class="modal-body">
                                  Are you sure you want to delete this shipper?
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-link waves-effect">I understand Delete Shipper</button>
                                  <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">Don't Delete</button>
                                </div>
                            </div>
                        </div>
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
