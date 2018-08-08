
<?php

  require_once('../classes/authentication.php');
  require_once('../static_references/header.php');
  require_once('../static_references/search.php');
  require_once('../static_references/navbar.php');
  require_once('../classes/dashboard-data.php');

?>


    <section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>DASHBOARD</h2>
            </div>

            <?php

              $get_data = new DashboardData();

              $total_awb = $get_data->total_awb();

              $total_un_awb = $get_data->total_undelivered();

              $total_shippers = $get_data->total_shippers();

              $total_users = $get_data->total_users();

              $top_five_shippers = $get_data->top_shippers();

              $top_five_branches = $get_data->top_branches();

              $top_five_users = $get_data->top_users();

              $lt_awbs = $get_data->latest_awbs();


            ?>

            <!-- Widgets -->
            <div class="row clearfix">
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box bg-pink hover-expand-effect">
                        <div class="icon">
                            <i class="material-icons">playlist_add_check</i>
                        </div>
                        <div class="content">
                            <div class="text">Total AWBs</div>
                            <div class="number count-to" data-from="0" data-to="<?php echo $total_awb; ?>" data-speed="15" data-fresh-interval="20"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box bg-cyan hover-expand-effect">
                        <div class="icon">
                            <i class="material-icons">help</i>
                        </div>
                        <div class="content">
                            <div class="text">Total Undelivered AWB</div>
                            <div class="number count-to" data-from="0" data-to="<?php echo $total_un_awb; ?>" data-speed="1000" data-fresh-interval="20"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box bg-light-green hover-expand-effect">
                        <div class="icon">
                            <i class="material-icons">forum</i>
                        </div>
                        <div class="content">
                            <div class="text">Total Shippers</div>
                            <div class="number count-to" data-from="0" data-to="<?php echo $total_shippers; ?>" data-speed="1000" data-fresh-interval="20"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box bg-orange hover-expand-effect">
                        <div class="icon">
                            <i class="material-icons">person_add</i>
                        </div>
                        <div class="content">
                            <div class="text">Total Employee</div>
                            <div class="number count-to" data-from="0" data-to="<?php echo $total_users; ?>" data-speed="1000" data-fresh-interval="20"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# Widgets -->
            <div class="row clearfix">
              <!-- Answered Tickets -->
              <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                  <div class="card">
                      <div class="body bg-teal">
                          <div class="font-bold m-b--35">Top Five Users</div>
                          <ul class="dashboard-stat-list">
                            <?php

                              foreach ($top_five_users as $key => $value) {

                                ?>

                                <li>
                                    <?php echo $value; ?>
                                    <span class="pull-right">
                                        <i class="material-icons">trending_up</i>
                                    </span>
                                </li>

                                <?php

                              }

                             ?>
                          </ul>
                      </div>
                  </div>
              </div>
              <!-- #END# Answered Tickets -->
                <!-- Latest Social Trends -->
                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    <div class="card">
                        <div class="body bg-cyan">
                            <div class="m-b--35 font-bold">Top Five Shippers</div>
                            <ul class="dashboard-stat-list">

                              <?php

                                foreach ($top_five_shippers as $key => $value) {

                                  ?>

                                  <li>
                                      <?php echo $value; ?>
                                      <span class="pull-right">
                                          <i class="material-icons">trending_up</i>
                                      </span>
                                  </li>

                                  <?php

                                }

                               ?>



                            </ul>
                        </div>
                    </div>
                </div>
                <!-- #END# Latest Social Trends -->
                <!-- Answered Tickets -->
                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    <div class="card">
                        <div class="body bg-teal">
                            <div class="font-bold m-b--35">Top Five Branches</div>
                            <ul class="dashboard-stat-list">
                              <?php

                                foreach ($top_five_branches as $key => $value) {

                                  ?>

                                  <li>
                                      <?php echo $value; ?>
                                      <span class="pull-right">
                                          <i class="material-icons">trending_up</i>
                                      </span>
                                  </li>

                                  <?php

                                }

                               ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- #END# Answered Tickets -->
            </div>

            <div class="row clearfix">
                <!-- Task Info -->
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="header">
                            <h2>Most Recent AWBs</h2>
                            
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-hover dashboard-task-infos">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>AWB Number</th>
                                            <th>Status</th>
                                            <th>Branch</th>
                                            <th>Destination</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                      <?php

                                          $count = 1;

                                          for($i=0; $i<sizeof($lt_awbs)-3; $i=$i+4){


                                            ?>

                                              <tr>

                                                <td><?php echo $count; ?></td>
                                                <td><?php echo $lt_awbs[$i]; ?></td>
                                                <td><span class="label bg-green"><?php echo $lt_awbs[$i+1]; ?></span></td>
                                                <td><?php echo $lt_awbs[$i+2]; ?></td>
                                                <td>
                                                    <?php echo $lt_awbs[$i+3]; ?>
                                                </td>


                                              </tr>
                                            <?php

                                            $count++;

                                          }


                                       ?>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- #END# Task Info -->
                <!-- Browser Usage -->

                <!-- #END# Browser Usage -->
            </div>
        </div>
    </section>

    <?php

      require_once('../static_references/footer.php');

    ?>
