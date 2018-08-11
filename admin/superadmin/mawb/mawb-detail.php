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

                          <?php
                              //declare object;
                              $get_mawb = new MAWBCrudOperation();
                              $show_pagination = new PaginationOperation();
                              $clearence = new Clearence();

                              $mawb_id = $_GET['b_id'];

                              //data clearance;
                              $mawb_id = $clearence->escape_string($mawb_id);
                              $mawb_id = strip_tags(trim($mawb_id));
                              $mawb_id = htmlentities($mawb_id);

                          ?>

                            <h2 style="text-align: left;">
                                Master AWB Details
                            </h2>
                            <div style="text-align: right;">

                              <a href="/powerlinebd/admin/superadmin/mawb/excel-download/<?php echo $mawb_id ?>" type="button" class="btn bg-teal waves-effect">Download Manifest Report</a>
                              <a href="/powerlinebd/admin/superadmin/mawb/mawb-edit/<?php echo $mawb_id ?>" type="button" class="btn bg-blue waves-effect">Edit</a>
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

                                              $query = "SELECT mawb_details.mawb_number, office_branch.name,
                                                               creation_details.creation_time, creation_details.creation_date
                                                        FROM `mawb_details` INNER JOIN `creation_details`
                                                        ON mawb_details.timer_id=creation_details.timer_id
                                                        INNER JOIN office_branch ON mawb_details.mawb_from=office_branch.branch_id
                                                        WHERE mawb_details.mawb_id = '$mawb_id'";

                                              $result = $get_mawb->getData($query);

                                              foreach($result as $key => $res){

                                                $mawb_number = $res['mawb_number'];
                                                $mawb_from = $res['name'];
                                                $creation_date = $res['creation_date'];
                                                $creation_time = $res['creation_time'];
                                              }

                                                ?>

                                                <tr>
                                                  <th>1</th>
                                                  <th>MAWB Number</th>
                                                  <th><?php echo $mawb_number; ?></th>
                                                </tr>

                                                <tr>
                                                  <th>2</th>
                                                  <th>MAWB Origin Branch</th>
                                                  <th><?php echo $mawb_from; ?></th>
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
                                    <h4 class="modal-title" id="defaultModalLabel">Delete MAWB?</h4>
                                </div>
                                <div class="modal-body">
                                  Are you sure you want to delete this MAWB?
                                </div>
                                <div class="modal-footer">
                                  <a href="/powerlinebd/admin/superadmin/mawb/mawb-delete/<?php echo $mawb_id ?>" type="button" class="btn bg-blue waves-effect">I understand Delete MAWB</a>
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
