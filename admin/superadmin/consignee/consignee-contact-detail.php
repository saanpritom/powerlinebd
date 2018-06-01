<?php

  require_once('../classes/authentication.php');
  include('../static_references/header.php');
  include('../static_references/search.php');
  include('../static_references/navbar.php');
  include_once('../classes/data_clearence.php');
  include_once('../classes/contact_person_crud.php');
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
                              $get_contact = new ContactCrudOperation();
                              $show_pagination = new PaginationOperation();
                              $clearence = new Clearence();

                              $contact_id = $_GET['b_id'];

                              //data clearance;
                              $contact_id = $clearence->escape_string($contact_id);
                              $contact_id = strip_tags(trim($contact_id));
                              $contact_id = htmlentities($contact_id);

                              //extracting branch_id and page number from complecated URL;
                              /*$exploded_url = explode('/', $shipper_id);

                              $shipper_page_number = end($exploded_url);

                              $shipper_id = prev($exploded_url);*/

                          ?>

                            <h2 style="text-align: left;">
                                Contact Details
                            </h2>
                            <div style="text-align: right;">
                              <a href="/powerlinebd/admin/superadmin/consignee/consignee-contact-edit/<?php echo $contact_id; ?>" type="button" class="btn bg-blue waves-effect">Edit</a>
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

                                <div role="tabpanel" class="tab-pane fade in active" id="profile_with_icon_title">

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


                                              $query = "SELECT contact_person_details.person_from, contact_person_details.parent_organization_id,
                                                        contact_person_details.name, contact_person_details.designation, contact_person_details.contact_number,
                                                        login_info.email, consignee_details.name AS s_name, creation_details.creation_date, creation_details.creation_time
                                                        FROM contact_person_details INNER JOIN login_info
                                                        ON contact_person_details.contact_id=login_info.user_id INNER JOIN consignee_details
                                                        ON contact_person_details.parent_organization_id=consignee_details.consignee_id INNER JOIN creation_details
                                                        ON contact_person_details.timer_id=creation_details.timer_id WHERE
                                                        contact_person_details.contact_id='$contact_id'";

                                              $result = $get_contact->getData($query);

                                              foreach($result as $key => $res){

                                                ?>

                                                <tr>
                                                  <th>1</th>
                                                  <th>Person Name</th>
                                                  <th><?php echo $res['name']; ?></th>
                                                </tr>

                                                <tr>
                                                  <th>2</th>
                                                  <th>Organization Type</th>
                                                  <th><?php echo 'Consignee'; ?></th>
                                                </tr>

                                                <tr>
                                                  <th>3</th>
                                                  <th>Oranization Name</th>
                                                  <th><a href="/powerlinebd/admin/superadmin/consignee/consignee-detail/<?php echo $res['parent_organization_id']; ?>/1"><?php echo $res['s_name']; ?></a></th>
                                                </tr>

                                                <tr>
                                                  <th>4</th>
                                                  <th>Designation</th>
                                                  <th><?php echo $res['designation']; ?></th>
                                                </tr>

                                                <tr>
                                                  <th>5</th>
                                                  <th>Contact Number</th>
                                                  <th><?php echo $res['contact_number']; ?></th>
                                                </tr>

                                                <tr>
                                                  <th>6</th>
                                                  <th>Email</th>
                                                  <th><?php echo $res['email']; ?></th>
                                                </tr>

                                                <tr>
                                                  <th>7</th>
                                                  <th>Creation Date</th>
                                                  <th><?php echo $res['creation_date']; ?></th>
                                                </tr>

                                                <tr>
                                                  <th>8</th>
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
