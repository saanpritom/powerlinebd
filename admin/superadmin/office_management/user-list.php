<?php

  require_once('../classes/authentication.php');
  include('../static_references/header.php');
  include('../static_references/search.php');
  include('../static_references/navbar.php');
  include_once('../classes/data_clearence.php');
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
                                List of all Users
                            </h2>

                        </div>
                        <div class="body table-responsive">


                          <table class="table table-hover">
                                <thead>
                                      <tr class="bg-orange">
                                          <th>#</th>
                                          <th>User Name</th>
                                          <th>User Type</th>
                                          <th>Contact Number</th>
                                          <th>Currently Loged in</th>
                                      </tr>
                                </thead>
                                <tbody>
                                  <?php

                                    //declare objects
                                    $get_user = new UserCrudOperation();
                                    $show_pagination = new PaginationOperation();
                                    $clearence = new Clearence();


                                    $user_page_number = $_GET['b_id'];

                                    //data clearance;
                                    $user_page_number = $clearence->escape_string($user_page_number);
                                    $user_page_number = strip_tags(trim($user_page_number));
                                    $user_page_number = htmlentities($user_page_number);

                                    //calculate lowest point for query database;
                                    $low_point = ($user_page_number * 20) - 20;

                                    //fetch branch list view;
                                    $query = "SELECT admin_details.admin_id, admin_details.name, admin_details.admin_type,
                                              admin_details.contact_number, login_info.login_status
                                              FROM admin_details INNER JOIN login_info ON
                                              admin_details.admin_id = login_info.user_id
                                              WHERE 1 ORDER BY admin_details.name ASC LIMIT 20 OFFSET $low_point";

                                    $result = $get_user->getData($query);

                                    $counter = 1;

                                    foreach ($result as $key => $res)
                                    {
                                      ?>
                                      <tr>
                                          <th scope="row"><?php echo $counter; ?></th>
                                          <td><a href="/powerlinebd/admin/superadmin/office_management/user-detail/<?php echo $res['admin_id']; ?>"><?php echo $res['name']; ?></a></td>
                                          <td><?php echo $res['admin_type']; ?></td>
                                          <td><?php echo $res['contact_number']; ?></td>
                                          <td>

                                            <?php

                                              if($res['login_status'] == 1){

                                                ?>

                                                <button type="button" data-color="deep-green" class="btn bg-green waves-effect">Logged In</button>

                                                <?php

                                              }else{

                                                ?>

                                                <button type="button" data-color="deep-default" class="btn bg-default waves-effect">Not logged in</button>

                                                <?php

                                              }

                                            ?>

                                          </td>
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
                            $query = "SELECT COUNT(admin_id) AS total_id FROM admin_details WHERE 1";

                            //calling total page number calculation function;
                            $how_many_show = $show_pagination->counting_pagination($query, '20', $user_page_number);

                            ?>

                            <div class="alert alert-info">
                                <?php echo $how_many_show; ?>
                            </div>

                        </div>

                        <div class="body">
                           <?php

                           //calling the pagination showing class, always send current page name;
                           $show_pagination->do_pagination('user-list');

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
