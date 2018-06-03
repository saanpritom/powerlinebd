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

                          <?php
                              //declare object;
                              $get_consignee = new ConsigneeCrudOperation();
                              $show_pagination = new PaginationOperation();
                              $clearence = new Clearence();

                              $consignee_id = $_GET['b_id'];

                              //data clearance;
                              $consignee_id = $clearence->escape_string($consignee_id);
                              $consignee_id = strip_tags(trim($consignee_id));
                              $consignee_id = htmlentities($consignee_id);

                              //extracting branch_id and page number from complecated URL;
                              $exploded_url = explode('/', $consignee_id);

                              $consignee_page_number = end($exploded_url);

                              $consignee_id = prev($exploded_url);

                          ?>

                            <h2 style="text-align: left;">
                                Consignee Details
                            </h2>
                            <div style="text-align: right;">
                              <a href="/powerlinebd/admin/superadmin/consignee/contact-add/<?php echo $consignee_id ?>" type="button" class="btn bg-red waves-effect">Add Contract</a>
                              <a href="/powerlinebd/admin/superadmin/consignee/consignee-shipper-add/<?php echo $consignee_id ?>" type="button" class="btn bg-green waves-effect">Add another Shipper</a>
                              <a href="/powerlinebd/admin/superadmin/consignee/consignee-edit/<?php echo $consignee_id ?>" type="button" class="btn bg-blue waves-effect">Edit</a>
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
                              <li role="presentation" class="active">
                                  <a href="#home_with_icon_title" data-toggle="tab">
                                      <i class="material-icons">face</i> Consignee's Contacts
                                  </a>
                              </li>
                              <li role="presentation">
                                  <a href="#shipper_with_icon_title" data-toggle="tab">
                                      <i class="material-icons">list</i> Connected Shippers
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
                                                  <th>Name</th>
                                                  <th>Designation</th>
                                                  <th>Email</th>
                                              </tr>
                                          </thead>
                                          <tbody>

                                            <?php

                                            //calculate lowest point for query database;
                                            $low_point = ($consignee_page_number * 20) - 20;

                                              $query = "SELECT contact_person_details.contact_id, contact_person_details.name,
                                                        contact_person_details.designation, login_info.email
                                                        FROM contact_person_details INNER JOIN login_info
                                                        ON contact_person_details.contact_id = login_info.user_id
                                                        WHERE contact_person_details.parent_organization_id = '$consignee_id' AND
                                                        contact_person_details.person_from='consignee'
                                                        ORDER BY contact_person_details.name ASC LIMIT 20 OFFSET $low_point";

                                              $result = $get_consignee->getData($query);

                                              $counter = 1;

                                              foreach($result as $key => $res){

                                                ?>

                                                <tr>
                                                  <th><?php echo $counter; ?></th>
                                                  <th><a href="/powerlinebd/admin/superadmin/consignee/consignee-contact-detail/<?php echo $res['contact_id']; ?>"><?php echo $res['name']; ?></a></th>
                                                  <th><?php echo $res['designation']; ?></th>
                                                  <th><?php echo $res['email']; ?></th>
                                                </tr>


                                                <?php
                                                $counter++;
                                              }

                                            ?>

                                          </tbody>
                                      </table>

                                      <?php

                                        //pagination;

                                        //querying for calculating total page to show;
                                        $query = "SELECT COUNT(contact_person_details.contact_id) AS total_id FROM contact_person_details
                                                  WHERE parent_organization_id='$consignee_id' AND person_from='consignee'";

                                        //calling total page number calculation function;
                                        $how_many_show = $show_pagination->counting_pagination($query, '20', $consignee_page_number);

                                        ?>

                                        <div class="alert alert-info">
                                            <?php echo $how_many_show; ?>
                                        </div>

                                        <?php

                                        //calling the pagination showing class, always send current page name;
                                        $show_pagination->do_pagination('consignee-detail');

                                         ?>

                                  </div>

                                </div>

                                <div role="tabpanel" class="tab-pane fade" id="profile_with_icon_title">

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


                                              $query = "SELECT consignee_details.name, consignee_details.address, consignee_details.contact_number,
                                                        consignee_details.email, origin_destination_details.full_name, creation_details.creation_date,
                                                        creation_details.creation_time
                                                        FROM consignee_details
                                                        INNER JOIN origin_destination_details ON
                                                        consignee_details.country_id = origin_destination_details.o_id_id
                                                        INNER JOIN creation_details ON
                                                        consignee_details.timer_id=creation_details.timer_id
                                                        WHERE consignee_details.consignee_id='$consignee_id'";

                                              $result = $get_consignee->getData($query);

                                              foreach($result as $key => $res){

                                                ?>

                                                <tr>
                                                  <th>1</th>
                                                  <th>Shipper Name</th>
                                                  <th><?php echo $res['name']; ?></th>
                                                </tr>

                                                <tr>
                                                  <th>2</th>
                                                  <th>Shipper Email</th>
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
                                                  <th>Country</th>
                                                  <th><?php echo $res['full_name']; ?></th>
                                                </tr>

                                                <tr>
                                                  <th>6</th>
                                                  <th>Creation Date</th>
                                                  <th><?php echo $res['creation_date']; ?></th>
                                                </tr>

                                                <tr>
                                                  <th>7</th>
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

                                <div role="tabpanel" class="tab-pane fade in" id="shipper_with_icon_title">


                                  <div class="body table-responsive">


                                    <table class="table table-hover">
                                          <thead>
                                              <tr class="bg-orange">
                                                  <th>#</th>
                                                  <th>Name</th>
                                                  <th>Email</th>
                                                  <th>Country</th>
                                              </tr>
                                          </thead>
                                          <tbody>

                                            <?php

                                            //calculate lowest point for query database;
                                            $low_point = ($consignee_page_number * 20) - 20;

                                              $query = "SELECT shipper_details.shipper_id, shipper_details.name,
                                                        login_info.email, origin_destination_details.full_name FROM consignee_details
                                                        INNER JOIN consignee_shipper_relation
                                                        ON consignee_details.consignee_id=consignee_shipper_relation.consignee_id
                                                        INNER JOIN shipper_details
                                                        ON consignee_shipper_relation.shipper_id=shipper_details.shipper_id
                                                        INNER JOIN login_info
                                                        ON shipper_details.shipper_id=login_info.user_id
                                                        INNER JOIN origin_destination_details
                                                        ON shipper_details.country_id=origin_destination_details.o_id_id
                                                        WHERE consignee_details.consignee_id='$consignee_id'
                                                        ORDER BY shipper_details.name ASC LIMIT 20 OFFSET $low_point";

                                              $result = $get_consignee->getData($query);

                                              $counter = 1;

                                              foreach($result as $key => $res){

                                                ?>

                                                <tr>
                                                  <th><?php echo $counter; ?></th>
                                                  <th><a href="/powerlinebd/admin/superadmin/shippers/shipper-detail/<?php echo $res['shipper_id']; ?>/1"><?php echo $res['name']; ?></a></th>
                                                  <th><?php echo $res['email']; ?></th>
                                                  <th><?php echo $res['full_name']; ?></th>
                                                </tr>


                                                <?php
                                                $counter++;
                                              }

                                            ?>

                                          </tbody>
                                      </table>

                                      <?php

                                        //pagination;

                                        //querying for calculating total page to show;
                                        $query = "SELECT COUNT(shipper_details.shipper_id) AS total_id FROM consignee_details
                                                  INNER JOIN consignee_shipper_relation
                                                  ON consignee_details.consignee_id=consignee_shipper_relation.consignee_id
                                                  INNER JOIN shipper_details
                                                  ON consignee_shipper_relation.shipper_id=shipper_details.shipper_id
                                                  WHERE consignee_details.consignee_id='$consignee_id'";

                                        //calling total page number calculation function;
                                        $how_many_show = $show_pagination->counting_pagination($query, '20', $consignee_page_number);

                                        ?>

                                        <div class="alert alert-info">
                                            <?php echo $how_many_show; ?>
                                        </div>

                                        <?php

                                        //calling the pagination showing class, always send current page name;
                                        $show_pagination->do_pagination('consignee-detail');

                                         ?>

                                  </div>

                                </div>


                            </div>
                        </div>





                        <div class="modal fade in" id="mdModal" tabindex="-1" role="dialog" >
                        <div class="modal-dialog" role="document">
                            <div class="modal-content modal-col-deep-orange">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="defaultModalLabel">Delete Shipper?</h4>
                                </div>
                                <div class="modal-body">
                                  Are you sure you want to delete this shipper?
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-link waves-effect">I understand Delete Shipper</button>
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
