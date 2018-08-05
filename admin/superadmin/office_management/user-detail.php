<?php

  require_once('../classes/authentication.php');
  include('../static_references/header.php');
  include('../static_references/search.php');
  include('../static_references/navbar.php');
  include_once('../classes/data_clearence.php');
  include_once('../classes/admin_user_crud.php');

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
                              $clearence = new Clearence();


                              $user_id = $_GET['b_id'];

                              //data clearance;
                              $user_id = $clearence->escape_string($user_id);
                              $user_id = strip_tags(trim($user_id));
                              $user_id = htmlentities($user_id);

                              //check if / exist in url
                              if(strpos($user_id, '/')){

                                //extracting branch_id and page number from complecated URL;
                                $exploded_url = explode('/', $user_id);

                                $branch_page_number = end($exploded_url);

                                $user_id = prev($exploded_url);
                              }



                          ?>

                            <h2 style="text-align: left;">
                                User Details
                            </h2>
                            <div style="text-align: right;">
                              <?php

                                //check if user is logged in or not;
                                $query = "SELECT login_status FROM login_info WHERE user_id='$user_id'";
                                $result = $get_user->getData($query);

                                foreach($result as $key => $res){

                                  $login_status = $res['login_status'];

                                }

                                if($login_status == 1){

                                  ?>
                                  <button type="button" data-color="deep-green" class="btn bg-green waves-effect">Logged In</button>
                                  <?php

                                }

                              ?>

                              <a href="/powerlinebd/admin/superadmin/office_management/user-edit/<?php echo $user_id ?>" type="button" class="btn bg-blue waves-effect">Edit</a>
                              <button type="button" data-color="deep-orange" data-toggle="modal" data-target="#mdModal" class="btn bg-deep-orange waves-effect">Delete</button>

                            </div>


                        </div>
                        <div class="body">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs" role="tablist">
                              <li role="presentation" class="active">
                                  <a href="#home_with_icon_title" data-toggle="tab">
                                      <i class="material-icons">library_books</i> Detailed Information
                                  </a>
                              </li>
                              <li role="presentation">
                                  <a href="#profile_with_icon_title" data-toggle="tab">
                                      <i class="material-icons">face</i> Users Latest Log Reports
                                  </a>
                              </li>
                              <li role="presentation">
                                  <a href="#ip_with_icon_title" data-toggle="tab">
                                      <i class="material-icons">face</i> Login Reports
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
                                                  <th>Entity Name</th>
                                                  <th>Entity Details</th>
                                              </tr>
                                          </thead>
                                          <tbody>

                                            <?php


                                              $query = "SELECT admin_details.admin_type, admin_details.name, admin_details.gender,
                                                       admin_details.designation, admin_details.department, admin_details.contact_number,
                                                       login_info.email, creation_details.creation_date, creation_details.creation_time,
                                                       office_branch.branch_id, office_branch.name AS b_name
                                                       FROM admin_details INNER JOIN login_info ON
                                                       admin_details.admin_id=login_info.user_id INNER JOIN creation_details ON
                                                       admin_details.timer_id=creation_details.timer_id INNER JOIN office_branch ON
                                                       admin_details.branch_id=office_branch.branch_id WHERE admin_details.admin_id='$user_id'";

                                              $result = $get_user->getData($query);

                                              foreach($result as $key => $res){

                                                ?>

                                                <tr>
                                                  <th>1</th>
                                                  <th>User's Name</th>
                                                  <th><?php echo $res['name']; ?></th>
                                                </tr>

                                                <tr>
                                                  <th>2</th>
                                                  <th>User Type</th>
                                                  <th><?php echo $res['admin_type']; ?></th>
                                                </tr>

                                                <tr>
                                                  <th>3</th>
                                                  <th>Gender</th>
                                                  <th><?php echo $res['gender']; ?></th>
                                                </tr>

                                                <tr>
                                                  <th>4</th>
                                                  <th>Designation</th>
                                                  <th><?php echo $res['designation']; ?></th>
                                                </tr>

                                                <tr>
                                                  <th>5</th>
                                                  <th>Department</th>
                                                  <th><?php echo $res['department']; ?></th>
                                                </tr>

                                                <tr>
                                                  <th>6</th>
                                                  <th>Contact Number</th>
                                                  <th><?php echo $res['contact_number']; ?></th>
                                                </tr>

                                                <tr>
                                                  <th>7</th>
                                                  <th>Email</th>
                                                  <th><?php echo $res['email']; ?></th>
                                                </tr>

                                                <tr>
                                                  <th>8</th>
                                                  <th>Branch Name</th>
                                                  <th><?php echo $res['b_name']; ?></th>
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
                                                <?php

                                              }

                                            ?>

                                          </tbody>
                                      </table>


                                  </div>


                                </div>

                                <div role="tabpanel" class="tab-pane fade" id="profile_with_icon_title">


                                  <div class="body table-responsive">


                                    <table class="table table-hover">
                                          <thead>
                                              <tr class="bg-orange">
                                                  <th>#</th>
                                                  <th>Action Details</th>
                                                  <th>Action Time</th>
                                                  <th>Action Date</th>
                                              </tr>
                                          </thead>
                                          <tbody>

                                            <?php

                                              $query = "SELECT log_report.log_report, creation_details.creation_date, creation_details.creation_time
                                                        FROM log_report INNER JOIN creation_details ON log_report.timer_id=creation_details.timer_id
                                                        WHERE log_report.user_id='$user_id' ORDER BY creation_details.creation_date DESC LIMIT 50";

                                              $result = $get_user->getData($query);

                                              $counter = 1;

                                              foreach($result as $key => $res){

                                                ?>

                                                <tr>
                                                  <th><?php echo $counter; ?></th>
                                                  <th><?php echo $res['log_report']; ?></th>
                                                  <th><?php echo $res['creation_date']; ?></th>
                                                  <th><?php echo $res['creation_time']; ?></th>
                                                </tr>


                                                <?php
                                                $counter++;
                                              }

                                            ?>

                                          </tbody>
                                      </table>

                                  </div>

                                  <a href="/powerlinebd/admin/superadmin/office_management/excel-download/<?php echo $user_id ?>/log_report" type="button" class="btn bg-blue waves-effect">Download User's Total Activity Report</a>
                                </div>

                                <div role="tabpanel" class="tab-pane fade" id="ip_with_icon_title">
                                  <div class="body table-responsive">


                                    <table class="table table-hover">
                                          <thead>
                                              <tr class="bg-orange">
                                                  <th>#</th>
                                                  <th>IP Address</th>
                                                  <th>Login Time</th>
                                                  <th>Login Date</th>
                                              </tr>
                                          </thead>
                                          <tbody>

                                            <?php

                                              $query = "SELECT login_log.public_ip, creation_details.creation_date,
                                                        creation_details.creation_time FROM login_log INNER JOIN
                                                        creation_details ON login_log.timer_id=creation_details.timer_id
                                                        WHERE login_log.user_id='$user_id'
                                                        ORDER BY creation_details.creation_date DESC LIMIT 50";

                                              $result = $get_user->getData($query);

                                              $counter = 1;

                                              foreach($result as $key => $res){

                                                ?>

                                                <tr>
                                                  <th><?php echo $counter; ?></th>
                                                  <th><?php echo $res['public_ip']; ?></th>
                                                  <th><?php echo $res['creation_date']; ?></th>
                                                  <th><?php echo $res['creation_time']; ?></th>
                                                </tr>


                                                <?php
                                                $counter++;
                                              }

                                            ?>

                                          </tbody>
                                    </table>
                                </div>

                                <a href="/powerlinebd/admin/superadmin/office_management/excel-download/<?php echo $user_id ?>/user_login" type="button" class="btn bg-blue waves-effect">Download User's Total Login Report</a>

                            </div>
                        </div>





                        <div class="modal fade in" id="mdModal" tabindex="-1" role="dialog" >
                        <div class="modal-dialog" role="document">
                            <div class="modal-content modal-col-deep-orange">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="defaultModalLabel">Delete User?</h4>
                                </div>
                                <div class="modal-body">
                                  Are you sure you want to delete this user?
                                </div>
                                <div class="modal-footer">
                                  <a href="/powerlinebd/admin/superadmin/office_management/delete-user/<?php echo $user_id ?>" type="button" class="btn bg-blue waves-effect">I understand, Delete</a>
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
