<?php

  require_once('../classes/authentication.php');
  include('../static_references/header.php');
  include('../static_references/search.php');
  include('../static_references/navbar.php');
  include_once('../classes/data_clearence.php');
  include_once('../classes/search_query.php');
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
                                Search Results for <?php echo $_GET['keyword']; ?>
                            </h2>

                        </div>
                        <div class="body table-responsive">


                          <table class="table table-hover">
                                <thead>
                                    <tr class="bg-orange">
                                        <th>#</th>
                                        <th>Data Link</th>
                                        <th>Data Form</th>
                                    </tr>
                                </thead>
                                <tbody>
                                  <?php

                                    $keyword = $_GET['keyword'];

                                    $counter = 1;

                                    $get_search = new SearchOperation();

                                    $search_data = $get_search->text_search_result($keyword);

                                    for ($i=0; $i<sizeof($search_data); $i=$i+3) {

                                      echo '<tr>';

                                      if($search_data[$i+2] == 'AWB'){

                                        echo '<td>' . $counter . '</td>';

                                        echo '<td>';

                                        echo '<a href="/powerlinebd/admin/superadmin/awb/awb-detail/' . $search_data[$i] .'">' . $search_data[$i+1] . '</a>';

                                        echo '</td>';

                                        echo '<td>AWB Number</td>';

                                      }elseif($search_data[$i+2] == 'MAWB'){

                                        echo '<td>' . $counter . '</td>';

                                        echo '<td>';

                                        echo '<a href="/powerlinebd/admin/superadmin/mawb/mawb-detail/' . $search_data[$i] .'">' . $search_data[$i+1] . '</a>';

                                        echo '</td>';

                                        echo '<td>MAWB Number</td>';

                                      }elseif($search_data[$i+2] == 'office_branch'){

                                        echo '<td>' . $counter . '</td>';

                                        echo '<td>';

                                        echo '<a href="/powerlinebd/admin/superadmin/office_management/branch-detail/' . $search_data[$i] .'">' . $search_data[$i+1] . '</a>';

                                        echo '</td>';

                                        echo '<td>Office Branch</td>';

                                      }elseif($search_data[$i+2] == 'username'){

                                        echo '<td>' . $counter . '</td>';

                                        echo '<td>';

                                        echo '<a href="/powerlinebd/admin/superadmin/office_management/user-detail/' . $search_data[$i] .'">' . $search_data[$i+1] . '</a>';

                                        echo '</td>';

                                        echo '<td>Admin User</td>';

                                      }elseif($search_data[$i+2] == 'shipper'){

                                        echo '<td>' . $counter . '</td>';

                                        echo '<td>';

                                        echo '<a href="/powerlinebd/admin/superadmin/shippers/shipper-detail/' . $search_data[$i] .'">' . $search_data[$i+1] . '</a>';

                                        echo '</td>';

                                        echo '<td>Shipper Data</td>';

                                      }elseif($search_data[$i+2] == 'consignee'){

                                        echo '<td>' . $counter . '</td>';

                                        echo '<td>';

                                        echo '<a href="/powerlinebd/admin/superadmin/consignee/consignee-detail/' . $search_data[$i] .'">' . $search_data[$i+1] . '</a>';

                                        echo '</td>';

                                        echo '<td>Consignee Data</td>';

                                      }elseif($search_data[$i+2] == 'shipper_contact'){

                                        echo '<td>' . $counter . '</td>';

                                        echo '<td>';

                                        echo '<a href="/powerlinebd/admin/superadmin/shippers/contact-detail/' . $search_data[$i] .'">' . $search_data[$i+1] . '</a>';

                                        echo '</td>';

                                        echo '<td>Shipper Contact Person</td>';

                                      }elseif($search_data[$i+2] == 'consignee_contact'){

                                        echo '<td>' . $counter . '</td>';

                                        echo '<td>';

                                        echo '<a href="/powerlinebd/admin/superadmin/consignee/consignee-contact-detail/' . $search_data[$i] .'">' . $search_data[$i+1] . '</a>';

                                        echo '</td>';

                                        echo '<td>Consignee Contact Person</td>';

                                      }else{

                                        echo '<td>' . $counter . '</td>';

                                        echo '<td> Nothing Found </td>';

                                        echo '<td> Nothing Found </td>';

                                      }

                                      echo '</tr>';

                                      $counter++;

                                      if($counter == 100){

                                        break;

                                      }

                                    }

                                  ?>

                                </tbody>
                            </table>


                        </div>

                        <div class="body">
                          <?php

                            if($counter == 1){

                              ?>

                              <div class="alert alert-danger">
                                  <?php echo 'No results found'; ?>
                              </div>

                              <?php

                            }elseif($counter < 100){

                              $counter = $counter - 1;

                              ?>

                              <div class="alert alert-info">
                                  <?php echo 'Total ' . $counter . ' results found'; ?>
                              </div>

                              <?php

                            }else{

                              ?>

                              <div class="alert alert-info">
                                  <?php echo 'Total 100 results found'; ?>
                              </div>

                              <?php

                            }

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
