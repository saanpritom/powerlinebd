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
                            <h2>
                                List of all Flights
                            </h2>

                        </div>
                        <div class="body table-responsive">


                          <table class="table table-hover">
                                <thead>
                                    <tr class="bg-orange">
                                        <th>#</th>
                                        <th>Flight Number</th>
                                        <th>Flight Date</th>
                                        <th>Flight Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                  <?php

                                    //declare objects
                                    $get_flight = new FlightCrudOperation();
                                    $show_pagination = new PaginationOperation();
                                    $clearence = new Clearence();


                                    $flight_page_number = $_GET['b_id'];

                                    //data clearance;
                                    $flight_page_number = $clearence->escape_string($flight_page_number);
                                    $flight_page_number = strip_tags(trim($flight_page_number));
                                    $flight_page_number = htmlentities($flight_page_number);

                                    //calculate lowest point for query database;
                                    $low_point = ($flight_page_number * 20) - 20;

                                    //fetch branch list view;
                                    $query = "SELECT `sl_num`, `flight_number`, `flight_date`, `flight_time`
                                              FROM `flight_details` ORDER BY `sl_num` DESC LIMIT 20 OFFSET $low_point";

                                    $result = $get_flight->getData($query);

                                    $counter = 1;

                                    foreach ($result as $key => $res)
                                    {
                                      ?>
                                      <tr>
                                          <th scope="row"><?php echo $counter; ?></th>
                                          <td><a href="/powerlinebd/admin/superadmin/flights/flight-detail/<?php echo $res['sl_num']; ?>"><?php echo $res['flight_number']; ?></a></td>
                                          <td><?php echo $res['flight_date']; ?></td>
                                          <td><?php echo $res['flight_time']; ?></td>
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
                            $query = "SELECT COUNT(sl_num) AS total_id FROM flight_details WHERE 1";

                            //calling total page number calculation function;
                            $how_many_show = $show_pagination->counting_pagination($query, '20', $flight_page_number);

                            ?>

                            <div class="alert alert-info">
                                <?php echo $how_many_show; ?>
                            </div>

                        </div>

                        <div class="body">
                           <?php

                           //calling the pagination showing class, always send current page name;
                           $show_pagination->do_pagination('flight-list');

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
