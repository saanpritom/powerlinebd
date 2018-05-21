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
                                List of all Branches
                            </h2>

                        </div>
                        <div class="body table-responsive">


                          <table class="table table-hover">
                                <thead>
                                    <tr class="bg-orange">
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Contact Number</th>
                                    </tr>
                                </thead>
                                <tbody>
                                  <?php

                                  //fetch branch list view;

                                    $get_branch = new BranchCrudOperation();

                                    $branch_pegi = $_GET['b_pa'];

                                    $query = "SELECT `branch_id`, `name`, `email`, `contact_number`
                                              FROM `office_branch` ORDER BY `name` ASC";

                                    $result = $get_branch->getData($query);

                                    $counter = 1;

                                    foreach ($result as $key => $res)
                                    {
                                      ?>
                                      <tr>
                                          <th scope="row"><?php echo $counter; ?></th>
                                          <td><a href="/powerlinebd/admin/superadmin/office_management/branch-detail"><?php echo $res['name']; ?></a></td>
                                          <td><?php echo $res['email']; ?></td>
                                          <td><?php echo $res['contact_number']; ?></td>
                                      </tr>
                                      <?php

                                      $counter++;

                                    }
                                  ?>

                                </tbody>
                            </table>


                        </div>

                        <div class="body">
                          <nav>
                                <ul class="pagination">
                                    <li class="disabled">
                                        <a href="javascript:void(0);">
                                            <i class="material-icons">chevron_left</i>
                                        </a>
                                    </li>
                                    <li class="active"><a href="javascript:void(0);">1</a></li>
                                    <li><a href="javascript:void(0);" class="waves-effect">2</a></li>
                                    <li><a href="javascript:void(0);" class="waves-effect">3</a></li>
                                    <li><a href="javascript:void(0);" class="waves-effect">4</a></li>
                                    <li><a href="javascript:void(0);" class="waves-effect">5</a></li>
                                    <li>
                                        <a href="javascript:void(0);" class="waves-effect">
                                            <i class="material-icons">chevron_right</i>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
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
