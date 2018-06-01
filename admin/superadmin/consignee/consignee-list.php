<?php

  require_once('../classes/authentication.php');
  include('../static_references/header.php');
  include('../static_references/search.php');
  include('../static_references/navbar.php');
  include_once('../classes/data_clearence.php');
  include_once('../classes/consignee_crud.php');
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
                                List of all Consignee
                            </h2>

                        </div>
                        <div class="body table-responsive">


                          <table class="table table-hover">
                                <thead>
                                      <tr class="bg-orange">
                                          <th>#</th>
                                          <th>Consignee Name</th>
                                          <th>Email</th>
                                          <th>Country</th>
                                      </tr>
                                </thead>
                                <tbody>
                                  <?php

                                    //declare objects
                                    $get_consignee = new ConsigneeCrudOperation();
                                    $show_pagination = new PaginationOperation();
                                    $clearence = new Clearence();


                                    $consignee_page_number = $_GET['b_id'];

                                    //data clearance;
                                    $consignee_page_number = $clearence->escape_string($consignee_page_number);
                                    $consignee_page_number = strip_tags(trim($consignee_page_number));
                                    $consignee_page_number = htmlentities($consignee_page_number);

                                    //calculate lowest point for query database;
                                    $low_point = ($consignee_page_number * 20) - 20;

                                    //fetch branch list view;
                                    $query = "SELECT consignee_details.consignee_id, consignee_details.name,
                                              consignee_details.email,
                                              origin_destination_details.full_name FROM consignee_details
                                              INNER JOIN origin_destination_details
                                              ON consignee_details.country_id=origin_destination_details.o_id_id
                                              WHERE 1
                                              ORDER BY consignee_details.name ASC LIMIT 20 OFFSET $low_point";

                                    $result = $get_consignee->getData($query);

                                    $counter = 1;

                                    foreach ($result as $key => $res)
                                    {
                                      ?>
                                      <tr>
                                          <th scope="row"><?php echo $counter; ?></th>
                                          <td><a href="/powerlinebd/admin/superadmin/consignee/consignee-detail/<?php echo $res['consignee_id']; ?>/1"><?php echo $res['name']; ?></a></td>
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
                            $query = "SELECT COUNT(consignee_details.consignee_id) AS total_id FROM consignee_details
                                      INNER JOIN origin_destination_details ON
                                      consignee_details.country_id=origin_destination_details.o_id_id
                                      WHERE origin_destination_details.type='country'";

                            //calling total page number calculation function;
                            $how_many_show = $show_pagination->counting_pagination($query, '20', $consignee_page_number);

                            ?>

                            <div class="alert alert-info">
                                <?php echo $how_many_show; ?>
                            </div>

                        </div>

                        <div class="body">
                           <?php

                           //calling the pagination showing class, always send current page name;
                           $show_pagination->do_pagination('consignee-list');

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
