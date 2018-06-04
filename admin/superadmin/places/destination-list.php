<?php

  require_once('../classes/authentication.php');
  include('../static_references/header.php');
  include('../static_references/search.php');
  include('../static_references/navbar.php');
  include_once('../classes/data_clearence.php');
  include_once('../classes/place_crud.php');
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
                                List of all Destinations
                            </h2>

                        </div>
                        <div class="body table-responsive">


                          <table class="table table-hover">
                                <thead>
                                    <tr class="bg-orange">
                                        <th>#</th>
                                        <th>Destination Name</th>
                                        <th>Short Form</th>
                                    </tr>
                                </thead>
                                <tbody>
                                  <?php

                                    //declare objects
                                    $get_place = new PlaceCrudOperation();
                                    $show_pagination = new PaginationOperation();
                                    $clearence = new Clearence();


                                    $place_page_number = $_GET['b_id'];

                                    //data clearance;
                                    $place_page_number = $clearence->escape_string($place_page_number);
                                    $place_page_number = strip_tags(trim($place_page_number));
                                    $place_page_number = htmlentities($place_page_number);

                                    //calculate lowest point for query database;
                                    $low_point = ($place_page_number * 20) - 20;

                                    //fetch branch list view;
                                    $query = "SELECT `o_id_id`, `full_name`, `short_form`
                                              FROM `origin_destination_details` WHERE `type`='destination' ORDER BY `full_name` ASC LIMIT 20 OFFSET $low_point";

                                    $result = $get_place->getData($query);

                                    $counter = ($place_page_number * 20) - 19;

                                    foreach ($result as $key => $res)
                                    {
                                      ?>
                                      <tr>
                                          <th scope="row"><?php echo $counter; ?></th>
                                          <td><a href="/powerlinebd/admin/superadmin/places/place-detail/<?php echo $res['o_id_id']; ?>/1"><?php echo $res['full_name']; ?></a></td>
                                          <td><?php echo $res['short_form']; ?></td>
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
                            $query = "SELECT COUNT(sl_num) AS total_id FROM origin_destination_details WHERE `type`='destination'";

                            //calling total page number calculation function;
                            $how_many_show = $show_pagination->counting_pagination($query, '20', $place_page_number);

                            ?>

                            <div class="alert alert-info">
                                <?php echo $how_many_show; ?>
                            </div>

                        </div>

                        <div class="body">
                           <?php

                           //calling the pagination showing class, always send current page name;
                           $show_pagination->do_pagination('destination-list');

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
