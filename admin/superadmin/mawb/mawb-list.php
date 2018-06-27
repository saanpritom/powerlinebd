<?php

  require_once('../classes/authentication.php');
  include('../static_references/header.php');
  include('../static_references/search.php');
  include('../static_references/navbar.php');
  include_once('../classes/data_clearence.php');
  include_once('../classes/mawb_crud.php');
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
                                List of all Master AWBs
                            </h2>

                        </div>
                        <div class="body table-responsive">


                          <table class="table table-hover">
                                <thead>
                                    <tr class="bg-orange">
                                        <th>#</th>
                                        <th>Master AWB Number</th>
                                    </tr>
                                </thead>
                                <tbody>
                                  <?php

                                    //declare objects
                                    $get_mawb = new MAWBCrudOperation();
                                    $show_pagination = new PaginationOperation();
                                    $clearence = new Clearence();


                                    $mawb_page_number = $_GET['b_id'];

                                    //data clearance;
                                    $mawb_page_number = $clearence->escape_string($mawb_page_number);
                                    $mawb_page_number = strip_tags(trim($mawb_page_number));
                                    $mawb_page_number = htmlentities($mawb_page_number);

                                    //calculate lowest point for query database;
                                    $low_point = ($mawb_page_number * 20) - 20;

                                    //fetch branch list view;
                                    $query = "SELECT `mawb_id`, `mawb_number`, `timer_id`
                                              FROM `mawb_details` ORDER BY `sl_num` DESC LIMIT 20 OFFSET $low_point";

                                    $result = $get_mawb->getData($query);

                                    $counter = 1;

                                    foreach ($result as $key => $res)
                                    {
                                      ?>
                                      <tr>
                                          <th scope="row"><?php echo $counter; ?></th>
                                          <td><a href="/powerlinebd/admin/superadmin/mawb/mawb-detail/<?php echo $res['mawb_id']; ?>"><?php echo $res['mawb_number']; ?></a></td>

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
                            $query = "SELECT COUNT(sl_num) AS total_id FROM mawb_details WHERE 1";

                            //calling total page number calculation function;
                            $how_many_show = $show_pagination->counting_pagination($query, '20', $mawb_page_number);

                            ?>

                            <div class="alert alert-info">
                                <?php echo $how_many_show; ?>
                            </div>

                        </div>

                        <div class="body">
                           <?php

                           //calling the pagination showing class, always send current page name;
                           $show_pagination->do_pagination('mawb-list');

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
