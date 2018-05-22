<?php

  require_once('../classes/authentication.php');
  include('../static_references/header.php');
  include('../static_references/search.php');
  include('../static_references/navbar.php');
  include_once('../classes/data_validation.php');
  include_once('../classes/data_clearence.php');
  include_once('../classes/branch_crud.php');
  include_once('../classes/admin_user_crud.php');
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

                                    //declare objects
                                    $get_branch = new BranchCrudOperation();
                                    $show_pagination = new PaginationOperation();

                                    //fetch branch list view;
                                    $query = "SELECT `branch_id`, `name`, `email`, `contact_number`
                                              FROM `office_branch` ORDER BY `name` ASC";

                                    $result = $get_branch->getData($query);

                                    $counter = 1;

                                    foreach ($result as $key => $res)
                                    {
                                      ?>
                                      <tr>
                                          <th scope="row"><?php echo $counter; ?></th>
                                          <td><a href="/powerlinebd/admin/superadmin/office_management/branch-detail/<?php echo $res['branch_id']; ?>"><?php echo $res['name']; ?></a></td>
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
                          <?php

                            $branch_page_number = $_GET['b_id'];

                            //querying for calculating total page to show;
                            $query = "SELECT COUNT(branch_id) AS total_id FROM office_branch WHERE 1";

                            //calling total page number calculation function;
                            $how_many_show = $show_pagination->counting_pagination($query, '20', $branch_page_number);

                            ?>

                            <div class="alert alert-info">
                                <?php echo $how_many_show; ?>
                            </div>

                        </div>

                        <div class="body">
                           <?php

                           //calling the pagination showing class, always send current page name;
                           $show_pagination->do_pagination('branch-list');

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
