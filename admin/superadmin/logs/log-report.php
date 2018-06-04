<?php

  require_once('../classes/authentication.php');
  include('../static_references/header.php');
  include('../static_references/search.php');
  include('../static_references/navbar.php');
  include_once('../classes/data_clearence.php');
  include_once('../classes/log_crud.php');
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
                                List of all Log Reprots
                            </h2>

                        </div>
                        <div class="body table-responsive">


                          <table class="table table-hover">
                                <thead>
                                      <tr class="bg-orange">
                                          <th>#</th>
                                          <th>User Name</th>
                                          <th>Report</th>
                                          <th>Report Date</th>
                                          <th>Report Time</th>
                                      </tr>
                                </thead>
                                <tbody>
                                  <?php

                                    //declare objects
                                    $get_log = new LogCrudOperation();
                                    $show_pagination = new PaginationOperation();
                                    $clearence = new Clearence();


                                    $log_page_number = $_GET['b_id'];

                                    //data clearance;
                                    $log_page_number = $clearence->escape_string($log_page_number);
                                    $log_page_number = strip_tags(trim($log_page_number));
                                    $log_page_number = htmlentities($log_page_number);

                                    //calculate lowest point for query database;
                                    $low_point = ($log_page_number * 20) - 20;

                                    //fetch all the log reports;
                                    $query = "SELECT `user_id`, `log_report`, `timer_id` FROM `log_report` WHERE 1";

                                    $query = "SELECT log_report.user_id, log_report.log_report,
                                              creation_details.creation_date, creation_details.creation_time
                                              FROM log_report INNER JOIN creation_details
                                              ON log_report.timer_id=creation_details.timer_id WHERE 1
                                              ORDER BY creation_details.creation_date DESC LIMIT 20 OFFSET $low_point";

                                    $result = $get_log->getData($query);

                                    $counter = ($log_page_number * 20) - 19;

                                    foreach ($result as $key => $res)
                                    {
                                      ?>
                                      <tr>
                                          <th scope="row"><?php echo $counter; ?></th>
                                          <td>


                                            <?php

                                              //detect what type of user it is;

                                              $temp_user_id= $res['user_id'];

                                              if($temp_user_id != 0){

                                                $sub_query = "SELECT user_type FROM login_info WHERE user_id='$temp_user_id'";

                                                $sub_result = $get_log->getData($sub_query);

                                                foreach ($sub_result as $key => $sub_res) {

                                                  $temp_user_type = $sub_res['user_type'];

                                                }

                                                //redirect user profile according to type;
                                                if($temp_user_type == 'admin'){

                                                  $sub_query = "SELECT name FROM admin_details WHERE admin_id='$temp_user_id'";

                                                  $sub_result = $get_log->getData($sub_query);

                                                  foreach ($sub_result as $key => $sub_res) {

                                                    $temp_user_name = $sub_res['name'];

                                                  }

                                                  ?>

                                                  <a href="/powerlinebd/admin/superadmin/office_management/user-detail/<?php echo $temp_user_id; ?>/1"><?php echo $temp_user_name; ?></a>

                                                  <?php

                                                }else if($temp_user_type == 'shipper'){

                                                  $sub_query = "SELECT name FROM shipper_details WHERE shipper_id='$temp_user_id'";

                                                  $sub_result = $get_log->getData($sub_query);

                                                  foreach ($sub_result as $key => $sub_res) {

                                                    $temp_user_name = $sub_res['name'];

                                                  }

                                                  ?>

                                                  <a href="/powerlinebd/admin/superadmin/shippers/shipper-detail/<?php echo $temp_user_id; ?>/1"><?php echo $temp_user_name; ?></a>

                                                  <?php

                                                }else{

                                                  //user is a contact person;

                                                  //check if he is from shipper or from consignee;

                                                  $sub_query = "SELECT name, person_from FROM contact_person_details WHERE contact_id='$temp_user_id'";

                                                  $sub_result = $get_log->getData($sub_query);

                                                  foreach ($sub_result as $key => $sub_res) {

                                                    $temp_user_name = $sub_res['name'];

                                                    $temp_person_from = $sub_res['person_from'];

                                                  }

                                                  if($temp_person_from == 'shipper'){

                                                    ?>

                                                    <a href="/powerlinebd/admin/superadmin/shippers/contact-detail/<?php echo $temp_user_id; ?>"><?php echo $temp_user_name; ?></a>

                                                    <?php

                                                  }else{

                                                    //person from consignee;
                                                    ?>

                                                    <a href="/powerlinebd/admin/superadmin/consignee/consignee-contact-detail/<?php echo $temp_user_id; ?>"><?php echo $temp_user_name; ?></a>

                                                    <?php

                                                  }

                                                }


                                              }else{

                                                echo 'No User Found';

                                              }


                                              ?>

                                          </td>
                                          <td><?php echo $res['log_report']; ?></td>
                                          <td><?php echo $res['creation_date']; ?></td>
                                          <td><?php echo $res['creation_time']; ?></td>
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
                            $query = "SELECT COUNT(log_report.sl_num) AS total_id FROM log_report WHERE 1";

                            //calling total page number calculation function;
                            $how_many_show = $show_pagination->counting_pagination($query, '20', $log_page_number);

                            ?>

                            <div class="alert alert-info">
                                <?php echo $how_many_show; ?>
                            </div>

                        </div>

                        <div class="body">
                           <?php

                           //calling the pagination showing class, always send current page name;
                           $show_pagination->do_pagination('log-report');

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
