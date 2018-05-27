<?php

  require_once('../classes/authentication.php');
  include('../static_references/header.php');
  include('../static_references/search.php');
  include('../static_references/navbar.php');
  include_once('../classes/data_clearence.php');
  include_once('../classes/flight_crud.php');
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
                              $get_flight = new FlightCrudOperation();
                              $show_pagination = new PaginationOperation();
                              $clearence = new Clearence();

                              $flight_id = $_GET['b_id'];

                              //data clearance;
                              $flight_id = $clearence->escape_string($flight_id);
                              $flight_id = strip_tags(trim($flight_id));
                              $flight_id = htmlentities($flight_id);

                          ?>

                            <h2 style="text-align: left;">
                                Flight Details
                            </h2>
                            <div style="text-align: right;">

                              <a href="/powerlinebd/admin/superadmin/flights/flight-edit/<?php echo $flight_id ?>" type="button" class="btn bg-blue waves-effect">Edit</a>
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

                                              $query = "SELECT flight_details.flight_number, flight_details.flight_date,
                                                               flight_details.flight_time,
                                                               creation_details.creation_time, creation_details.creation_date
                                                        FROM `flight_details` INNER JOIN `creation_details`
                                                        ON flight_details.timer_id=creation_details.timer_id
                                                        WHERE flight_details.sl_num = '$flight_id'";

                                              $result = $get_flight->getData($query);

                                              foreach($result as $key => $res){

                                                $flight_number = $res['flight_number'];
                                                $flight_date = $res['flight_date'];
                                                $flight_time = $res['flight_time'];
                                                $creation_date = $res['creation_date'];
                                                $creation_time = $res['creation_time'];
                                              }

                                                ?>

                                                <tr>
                                                  <th>1</th>
                                                  <th>Flight Number</th>
                                                  <th><?php echo $flight_number; ?></th>
                                                </tr>

                                                <tr>
                                                  <th>2</th>
                                                  <th>Flight Date</th>
                                                  <th><?php echo $flight_date; ?></th>
                                                </tr>

                                                <tr>
                                                  <th>3</th>
                                                  <th>Flight Time</th>
                                                  <th><?php echo $flight_time; ?></th>
                                                </tr>

                                                <tr>
                                                  <th>4</th>
                                                  <th>Connected MAWBs</th>
                                                  <th>

                                                    <?php

                                                    //check if MAWB created for this Flight;
                                                    $query = "SELECT mawb_details.mawb_id, mawb_details.mawb_number
                                                              FROM mawb_details INNER JOIN awb_details
                                                              ON mawb_details.mawb_id=awb_details.awb_id
                                                              INNER JOIN flight_awb_relation
                                                              ON flight_awb_relation.awb_id=awb_details.awb_id
                                                              INNER JOIN flight_details
                                                              ON flight_awb_relation.flight_id=flight_details.sl_num
                                                              WHERE flight_details.sl_num = '$flight_id'";

                                                    $result = $get_flight->getData($query);
                                                    if($result) {

                                                      foreach($result as $key => $res){

                                                        ?>
                                                        <a href="/powerlinebd/admin/superadmin/mawbs/mawb-detail/<?php echo $res['mawb_id']; ?>">
                                                        <?php
                                                        echo $res['mawb_number'];
                                                        ?>
                                                      </a>
                                                      <?php
                                                      }

                                                    }else{
                                                      echo 'MAWB not created yet';
                                                    }

                                                    ?>

                                                  </th>
                                                </tr>

                                                <tr>
                                                  <th>5</th>
                                                  <th>Creation Date</th>
                                                  <th><?php echo $creation_date; ?></th>
                                                </tr>

                                                <tr>
                                                  <th>6</th>
                                                  <th>Creation Time</th>
                                                  <th><?php echo $creation_time; ?></th>
                                                </tr>


                                          </tbody>
                                      </table>



                                  </div>

                                </div>

                            </div>
                        </div>





                        <div class="modal fade in" id="mdModal" tabindex="-1" role="dialog" >
                        <div class="modal-dialog" role="document">
                            <div class="modal-content modal-col-deep-orange">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="defaultModalLabel">Delete Flight?</h4>
                                </div>
                                <div class="modal-body">
                                  Are you sure you want to delete this flight?
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-link waves-effect">I understand Delete Flight</button>
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
