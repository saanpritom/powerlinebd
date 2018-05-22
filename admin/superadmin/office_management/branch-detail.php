<?php

  require_once('../classes/authentication.php');
  include('../static_references/header.php');
  include('../static_references/search.php');
  include('../static_references/navbar.php');
  include_once('../classes/data_validation.php');
  include_once('../classes/data_clearence.php');
  include_once('../classes/branch_crud.php');
  include_once('../classes/admin_user_crud.php');

?>

<section class="content">
        <div class="container-fluid">

<!-- Vertical Layout -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                                Branch Details
                            </h2>
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
                                      <i class="material-icons">face</i> Connected Users
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

                                              $branch_id = $_GET['b_id'];

                                              //declare object;
                                              $get_branch = new BranchCrudOperation();

                                              $query = "SELECT office_branch.name, office_branch.email,
                                                               office_branch.contact_number, office_branch.address,
                                                               creation_details.creation_time, creation_details.creation_date
                                                        FROM `office_branch` INNER JOIN `creation_details`
                                                        ON office_branch.timer_id=creation_details.timer_id
                                                        WHERE office_branch.branch_id = '$branch_id'";

                                              $result = $get_branch->getData($query);

                                              foreach($result as $key => $res){

                                                ?>

                                                <tr>
                                                  <th>1</th>
                                                  <th>Branch Name</th>
                                                  <th><?php echo $res['name']; ?></th>
                                                </tr>

                                                <tr>
                                                  <th>2</th>
                                                  <th>Branch Email</th>
                                                  <th><?php echo $res['email']; ?></th>
                                                </tr>

                                                <tr>
                                                  <th>3</th>
                                                  <th>Contact Number</th>
                                                  <th><?php echo $res['contact_number']; ?></th>
                                                </tr>

                                                <tr>
                                                  <th>4</th>
                                                  <th>Address</th>
                                                  <th><?php echo $res['address']; ?></th>
                                                </tr>

                                                <tr>
                                                  <th>5</th>
                                                  <th>Creation Date</th>
                                                  <th><?php echo $res['creation_date']; ?></th>
                                                </tr>

                                                <tr>
                                                  <th>6</th>
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
                                                  <th>User Name</th>
                                                  <th>User Type</th>
                                                  <th>Contact Number</th>
                                              </tr>
                                          </thead>
                                          <tbody>

                                            <?php

                                              $query = "SELECT admin_id, name, admin_type, contact_number
                                                        FROM admin_details
                                                        WHERE branch_id = '$branch_id'";

                                              $result = $get_branch->getData($query);

                                              $counter = 1;

                                              foreach($result as $key => $res){

                                                ?>

                                                <tr>
                                                  <th><?php echo $counter; ?></th>
                                                  <th><a href="user-detail/<?php echo $res['admin_id']; ?>"><?php echo $res['name']; ?></a></th>
                                                  <th><?php echo $res['admin_type']; ?></th>
                                                  <th><?php echo $res['contact_number']; ?></th>
                                                </tr>


                                                <?php
                                                $counter++;
                                              }

                                            ?>

                                          </tbody>
                                      </table>


                                  </div>
                                </div>

                            </div>
                        </div>



                        <div class="body">
                          <a href="/powerlinebd/admin/superadmin/office_management/branch-edit/<?php echo $branch_id ?>" type="button" class="btn bg-blue waves-effect">Edit</a>
                          <button type="button" data-color="deep-orange" data-toggle="modal" data-target="#mdModal" class="btn bg-deep-orange waves-effect">Delete</button>




                        </div>

                        <div class="modal fade in" id="mdModal" tabindex="-1" role="dialog" >
                        <div class="modal-dialog" role="document">
                            <div class="modal-content modal-col-deep-orange">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="defaultModalLabel">Delete Branch?</h4>
                                </div>
                                <div class="modal-body">
                                  Are you sure you want to delete this branch? If you delete then all the Users and Data
                                  associated with it will be deleted and can't be recovered.
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-link waves-effect">I understand Delete Branch</button>
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
