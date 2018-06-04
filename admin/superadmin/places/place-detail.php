<?php

  require_once('../classes/authentication.php');
  include('../static_references/header.php');
  include('../static_references/search.php');
  include('../static_references/navbar.php');
  include_once('../classes/data_clearence.php');
  include_once('../classes/place_crud.php');
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
                              $get_place = new PlaceCrudOperation();
                              $show_pagination = new PaginationOperation();
                              $clearence = new Clearence();

                              $place_id = $_GET['b_id'];

                              //data clearance;
                              $place_id = $clearence->escape_string($place_id);
                              $place_id = strip_tags(trim($place_id));
                              $place_id = htmlentities($place_id);

                              //extracting branch_id and page number from complecated URL;
                              $exploded_url = explode('/', $place_id);

                              $place_page_number = end($exploded_url);

                              $place_id = prev($exploded_url);

                          ?>

                            <h2 style="text-align: left;">
                                Place Details
                            </h2>
                            <div style="text-align: right;">

                              <a href="/powerlinebd/admin/superadmin/places/place-edit/<?php echo $place_id ?>" type="button" class="btn bg-blue waves-effect">Edit</a>
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
                                      <i class="material-icons">list</i> Connected MAWB Lists
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
                                                  <th>MAWB Number</th>
                                              </tr>
                                          </thead>
                                          <tbody>

                                            <?php

                                            //calculate lowest point for query database;
                                            $low_point = ($place_page_number * 20) - 20;

                                            //fetch origin-destination-coutry type to determine what it is;

                                            $query = "SELECT `type` FROM `origin_destination_details` WHERE `o_id_id`='$place_id'";

                                            $result = $get_place->getData($query);

                                            foreach($result as $key => $res){

                                              $place_type = $res['type'];

                                            }

                                            //condition to check which place type it is so that to join with table;

                                            if($place_type == 'country'){

                                              $query = "SELECT mawb_details.mawb_id, mawb_details.mawb_number
                                                        FROM mawb_details
                                                        INNER JOIN creation_details
                                                        ON mawb_details.timer_id=creation_details.timer_id
                                                        INNER JOIN awb_details
                                                        ON mawb_details.mawb_id=awb_details.mawb_id
                                                        INNER JOIN freight_transport_relation
                                                        ON awb_details.awb_id=freight_transport_relation.awb_id
                                                        INNER JOIN origin_destination_details
                                                        ON freight_transport_relation.country_id=origin_destination_details.o_id_id
                                                        WHERE origin_destination_details.o_id_id = '$place_id' AND
                                                        origin_destination_details.type='country' ORDER BY creation_details.creation_date DESC
                                                        LIMIT 20 OFFSET $low_point";

                                            }elseif($place_type='origin'){

                                              $query = "SELECT mawb_details.mawb_id, mawb_details.mawb_number
                                                        FROM mawb_details
                                                        INNER JOIN creation_details
                                                        ON mawb_details.timer_id=creation_details.timer_id
                                                        INNER JOIN awb_details
                                                        ON mawb_details.mawb_id=awb_details.mawb_id
                                                        INNER JOIN freight_transport_relation
                                                        ON awb_details.awb_id=freight_transport_relation.awb_id
                                                        INNER JOIN origin_destination_details
                                                        ON freight_transport_relation.origin_d_id=origin_destination_details.o_id_id
                                                        WHERE origin_destination_details.o_id_id = '$place_id' AND
                                                        origin_destination_details.type='origin' ORDER BY creation_details.creation_date DESC
                                                        LIMIT 20 OFFSET $low_point";

                                            }else{

                                              $query = "SELECT mawb_details.mawb_id, mawb_details.mawb_number
                                                        FROM mawb_details
                                                        INNER JOIN creation_details
                                                        ON mawb_details.timer_id=creation_details.timer_id
                                                        INNER JOIN awb_details
                                                        ON mawb_details.mawb_id=awb_details.mawb_id
                                                        INNER JOIN freight_transport_relation
                                                        ON awb_details.awb_id=freight_transport_relation.awb_id
                                                        INNER JOIN origin_destination_details
                                                        ON freight_transport_relation.destination_d_id=origin_destination_details.o_id_id
                                                        WHERE origin_destination_details.o_id_id = '$place_id' AND
                                                        origin_destination_details.type='destination' ORDER BY creation_details.creation_date DESC
                                                        LIMIT 20 OFFSET $low_point";

                                            }



                                              $result = $get_place->getData($query);

                                              $counter = ($place_page_number * 20) - 19;

                                              foreach($result as $key => $res){

                                                ?>

                                                <tr>
                                                  <th><?php echo $counter; ?></th>
                                                  <th>
                                                    <a href="/powerlinebd/admin/superadmin/places/place-detail/<?php echo $res['mawb_id']; ?>">
                                                      <?php echo $res['mawb_number']; ?>
                                                    </a>
                                                  </th>
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

                                        if($place_type == 'country'){

                                          $query = "SELECT COUNT(mawb_details.mawb_id) AS total_id FROM mawb_details
                                                    INNER JOIN awb_details
                                                    ON mawb_details.mawb_id=awb_details.mawb_id
                                                    INNER JOIN freight_transport_relation
                                                    ON awb_details.awb_id=freight_transport_relation.awb_id
                                                    INNER JOIN origin_destination_details
                                                    ON freight_transport_relation.country_id=origin_destination_details.o_id_id
                                                    WHERE origin_destination_details.o_id_id='$place_id' AND
                                                    origin_destination_details.type='country'";

                                        }elseif($place_type == 'origin'){

                                          $query = "SELECT COUNT(mawb_details.mawb_id) AS total_id FROM mawb_details
                                                    INNER JOIN awb_details
                                                    ON mawb_details.mawb_id=awb_details.mawb_id
                                                    INNER JOIN freight_transport_relation
                                                    ON awb_details.awb_id=freight_transport_relation.awb_id
                                                    INNER JOIN origin_destination_details
                                                    ON freight_transport_relation.origin_d_id=origin_destination_details.o_id_id
                                                    WHERE origin_destination_details.o_id_id='$place_id' AND
                                                    origin_destination_details.type='origin'";

                                        }else{

                                          $query = "SELECT COUNT(mawb_details.mawb_id) AS total_id FROM mawb_details
                                                    INNER JOIN awb_details
                                                    ON mawb_details.mawb_id=awb_details.mawb_id
                                                    INNER JOIN freight_transport_relation
                                                    ON awb_details.awb_id=freight_transport_relation.awb_id
                                                    INNER JOIN origin_destination_details
                                                    ON freight_transport_relation.destination_d_id=origin_destination_details.o_id_id
                                                    WHERE origin_destination_details.o_id_id='$place_id' AND
                                                    origin_destination_details.type='destination'";

                                        }



                                        //calling total page number calculation function;
                                        $how_many_show = $show_pagination->counting_pagination($query, '20', $place_page_number);

                                        ?>

                                        <div class="alert alert-info">
                                            <?php echo $how_many_show; ?>
                                        </div>

                                        <?php

                                        //calling the pagination showing class, always send current page name;
                                        $show_pagination->do_pagination('place-detail');

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


                                              $query = "SELECT origin_destination_details.full_name, origin_destination_details.short_form,
                                                        origin_destination_details.type, creation_details.creation_date, creation_details.creation_time
                                                        FROM origin_destination_details INNER JOIN creation_details
                                                        ON origin_destination_details.timer_id=creation_details.timer_id
                                                        WHERE origin_destination_details.o_id_id='$place_id'";

                                              $result = $get_place->getData($query);

                                              foreach($result as $key => $res){

                                                ?>

                                                <tr>
                                                  <th>1</th>
                                                  <th>Place Name</th>
                                                  <th><?php echo $res['full_name']; ?></th>
                                                </tr>

                                                <tr>
                                                  <th>2</th>
                                                  <th>Short Name</th>
                                                  <th><?php echo $res['short_form']; ?></th>
                                                </tr>

                                                <tr>
                                                  <th>3</th>
                                                  <th>Type</th>
                                                  <th><?php echo $res['type']; ?></th>
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

                            </div>
                        </div>





                        <div class="modal fade in" id="mdModal" tabindex="-1" role="dialog" >
                        <div class="modal-dialog" role="document">
                            <div class="modal-content modal-col-deep-orange">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="defaultModalLabel">Delete Place?</h4>
                                </div>
                                <div class="modal-body">
                                  Are you sure you want to delete this place?
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-link waves-effect">I understand Delete Place</button>
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
