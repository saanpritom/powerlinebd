<?php

  require_once('../classes/authentication.php');
  include('../static_references/header.php');
  include('../static_references/search.php');
  include('../static_references/navbar.php');
  include_once('../classes/data_clearence.php');
  include_once('../classes/shipper_crud.php');
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
                                List of all Shippers
                            </h2>

                        </div>
                        <div class="body table-responsive">


                          <table class="table table-hover">
                                <thead>
                                      <tr class="bg-orange">
                                          <th>#</th>
                                          <th>Shipper Name</th>
                                          <th>Email</th>
                                          <th>Country</th>
                                      </tr>
                                </thead>
                                <tbody>
                                  <?php

                                    //declare objects
                                    $get_shipper = new ShipperCrudOperation();
                                    $show_pagination = new PaginationOperation();
                                    $clearence = new Clearence();


                                    $shipper_page_number = $_GET['b_id'];

                                    //data clearance;
                                    $shipper_page_number = $clearence->escape_string($shipper_page_number);
                                    $shipper_page_number = strip_tags(trim($shipper_page_number));
                                    $shipper_page_number = htmlentities($shipper_page_number);

                                    //calculate lowest point for query database;
                                    $low_point = ($shipper_page_number * 20) - 20;

                                    //fetch branch list view;
                                    $query = "SELECT shipper_details.shipper_id, shipper_details.name,
                                              login_info.email, origin_destination_details.full_name
                                              FROM shipper_details INNER JOIN login_info
                                              ON shipper_details.shipper_id = login_info.user_id
                                              INNER JOIN origin_destination_details ON
                                              shipper_details.country_id = origin_destination_details.o_id_id
                                              WHERE origin_destination_details.type='country'
                                              ORDER BY shipper_details.name ASC LIMIT 20 OFFSET $low_point";

                                    $result = $get_shipper->getData($query);

                                    $counter = ($shipper_page_number * 20) - 19;

                                    foreach ($result as $key => $res)
                                    {
                                      ?>
                                      <tr>
                                          <th scope="row"><?php echo $counter; ?></th>
                                          <td><a href="/powerlinebd/admin/superadmin/shippers/shipper-detail/<?php echo $res['shipper_id']; ?>/1"><?php echo $res['name']; ?></a></td>
                                          <td><?php echo $res['email']; ?></td>
                                          <td><?php echo $res['full_name']; ?></td>
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
                            $query = "SELECT COUNT(shipper_details.shipper_id) AS total_id FROM shipper_details
                                      INNER JOIN origin_destination_details ON
                                      shipper_details.country_id=origin_destination_details.o_id_id
                                      WHERE origin_destination_details.type='country'";

                            //calling total page number calculation function;
                            $how_many_show = $show_pagination->counting_pagination($query, '20', $shipper_page_number);

                            ?>

                            <div class="alert alert-info">
                                <?php echo $how_many_show; ?>
                            </div>

                        </div>

                        <div class="body">
                           <?php

                           //calling the pagination showing class, always send current page name;
                           $show_pagination->do_pagination('shipper-list');

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
