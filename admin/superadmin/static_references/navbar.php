
<?php

include('../classes/navigation-active.php');
include_once('../classes/user-topbar-info.php');

$url_active = new NavigationActive();
$url_active->url_detection($_SERVER['REQUEST_URI']);

 ?>

<!-- Top Bar -->
<nav class="navbar">
    <div class="container-fluid">
        <div class="navbar-header">
            <a href="javascript:void(0);" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false"></a>
            <a href="javascript:void(0);" class="bars"></a>
            <a class="navbar-brand" href="index.html">Power Line Bangladesh</a>
        </div>
        <div class="collapse navbar-collapse" id="navbar-collapse">
            <ul class="nav navbar-nav navbar-right">
                <!-- Call Search -->
                <li><a href="javascript:void(0);" class="js-search" data-close="true"><i class="material-icons">search</i></a></li>
                <!-- #END# Call Search -->

            </ul>
        </div>
    </div>
</nav>
<!-- #Top Bar -->
<section>
    <!-- Left Sidebar -->
    <aside id="leftsidebar" class="sidebar">
        <!-- User Info -->
        <div class="user-info">
            <div class="image">
                <img src="/powerlinebd/static/images/user.png" width="48" height="48" alt="User" />
            </div>
            <div class="info-container">
                <?php

                  $usn_id = $_SESSION["plbd_id"];

                  $usn_detail = new UserInfo();

                  $usn_name = $usn_detail->fetch_user_details($usn_id);

                ?>
                <div class="name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo $usn_name; ?></div>
                <div class="email">Superuser Panel</div>
                <div class="btn-group user-helper-dropdown">
                    <i class="material-icons" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">keyboard_arrow_down</i>
                    <ul class="dropdown-menu pull-right">
                        <li><a href="/powerlinebd/admin/superadmin/office_management/user-detail/<?php echo $usn_id; ?>"><i class="material-icons">person</i>Profile</a></li>
                        <li role="seperator" class="divider"></li>
                        <li><a href="/powerlinebd/signout"><i class="material-icons">input</i>Sign Out</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- #User Info -->
        <!-- Menu -->
        <div class="menu">
            <ul class="list">
                <li class="header">MAIN NAVIGATION</li>
                <?php

                  if($url_active->child_menu == 'dashboard'){
                    echo '<li class="active">';
                  }else{
                    echo '<li>';
                  }

                 ?>
                    <a href="/powerlinebd/admin/superadmin/home/dashboard">
                        <i class="material-icons">dashboard</i>
                        <span>Dashboard</span>
                    </a>
                </li>


                <?php

                if($url_active->child_menu == 'awb-add' or $url_active->child_menu == 'awb-list' or $url_active->child_menu == 'awb-detail' or $url_active->child_menu == 'awb-edit' or $url_active->child_menu == 'update_mawb_flight'
                    or $url_active->child_menu == 'unlock_awb' or $url_active->child_menu == 'awb-third-party' or $url_active->child_menu == 'third-party-edit' or $url_active->child_menu == 'awb-delivery'){
                  echo '<li class="active">';
                }else{
                  echo '<li>';
                }

                 ?>
                    <a href="javascript:void(0);" class="menu-toggle">
                        <i class="material-icons">flight_takeoff</i>
                        <span>AWBs</span>
                    </a>
                    <ul class="ml-menu">
                      <?php

                        if($url_active->child_menu == 'awb-list' or $url_active->child_menu == 'awb-detail' or $url_active->child_menu == 'awb-edit' or $url_active->child_menu == 'update_mawb_flight'
                            or $url_active->child_menu == 'unlock_awb' or $url_active->child_menu == 'awb-third-party' or $url_active->child_menu == 'third-party-edit' or $url_active->child_menu == 'awb-delivery'){
                          echo '<li class="active">';
                        }else{
                          echo '<li>';
                        }

                       ?>
                          <a href="/powerlinebd/admin/superadmin/awb/awb-list/1" >
                              <i class="material-icons">view_stream</i>
                              <span>All AWBs</span>
                          </a>
                      </li>
                        <?php

                          if($url_active->child_menu == 'awb-add'){
                            echo '<li class="active">';
                          }else{
                            echo '<li>';
                          }

                         ?>
                            <a href="/powerlinebd/admin/superadmin/awb/awb-add" >
                                <i class="material-icons">add_box</i>
                                <span>Create New AWB</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="/powerlinebd/admin/superadmin/manifest/report">
                        <i class="material-icons">description</i>
                        <span>Manifest Report</span>
                    </a>
                </li>


                <?php

                  if($url_active->child_menu == 'mawb-add' or $url_active->child_menu == 'mawb-list' or $url_active->child_menu == 'mawb-detail' or $url_active->child_menu == 'mawb-edit' or $url_active->child_menu == 'bulk-mawb'
                      or $url_active->child_menu == 'bulk-awb-unlock'){
                    echo '<li class="active">';
                  }else{
                    echo '<li>';
                  }

                 ?>
                    <a href="javascript:void(0);" class="menu-toggle">
                        <i class="material-icons">group_work</i>
                        <span>Master AWB</span>
                    </a>
                    <ul class="ml-menu">
                      <?php

                        if($url_active->child_menu == 'mawb-list' or $url_active->child_menu == 'mawb-detail' or $url_active->child_menu == 'mawb-edit' or $url_active->child_menu == 'bulk_awb_unlock'){
                          echo '<li class="active">';
                        }else{
                          echo '<li>';
                        }

                       ?>
                          <a href="/powerlinebd/admin/superadmin/mawb/mawb-list/1" >
                              <i class="material-icons">view_stream</i>
                              <span>All MAWB</span>
                          </a>
                      </li>
                      <?php

                        if($url_active->child_menu == 'bulk-mawb'){
                          echo '<li class="active">';
                        }else{
                          echo '<li>';
                        }

                       ?>
                            <a href="/powerlinebd/admin/superadmin/mawb/bulk-mawb" >
                                <i class="material-icons">cancel</i>
                                <span>Bulk AWB Lock</span>
                            </a>
                        </li>
                        <?php

                          if($url_active->child_menu == 'bulk-awb-unlock'){
                            echo '<li class="active">';
                          }else{
                            echo '<li>';
                          }

                         ?>
                              <a href="/powerlinebd/admin/superadmin/mawb/bulk-awb-unlock" >
                                  <i class="material-icons">cancel</i>
                                  <span>Bulk AWB Unlock</span>
                              </a>
                          </li>
                        <?php

                          if($url_active->child_menu == 'mawb-add'){
                            echo '<li class="active">';
                          }else{
                            echo '<li>';
                          }

                         ?>
                            <a href="/powerlinebd/admin/superadmin/mawb/mawb-add" >
                                <i class="material-icons">add_box</i>
                                <span>Create New MAWB</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <?php

                  if($url_active->child_menu == 'flight-add' or $url_active->child_menu == 'flight-list' or $url_active->child_menu == 'flight-detail' or $url_active->child_menu == 'flight-edit'){
                    echo '<li class="active">';
                  }else{
                    echo '<li>';
                  }

                 ?>
                    <a href="javascript:void(0);" class="menu-toggle">
                        <i class="material-icons">flight</i>
                        <span>Flights</span>
                    </a>
                    <ul class="ml-menu">
                      <?php

                        if($url_active->child_menu == 'flight-list' or $url_active->child_menu == 'flight-detail' or $url_active->child_menu == 'flight-edit'){
                          echo '<li class="active">';
                        }else{
                          echo '<li>';
                        }

                       ?>
                          <a href="/powerlinebd/admin/superadmin/flights/flight-list/1" >
                              <i class="material-icons">view_stream</i>
                              <span>All Flight List</span>
                          </a>
                      </li>
                      <?php

                        if($url_active->child_menu == 'flight-add'){
                          echo '<li class="active">';
                        }else{
                          echo '<li>';
                        }

                       ?>
                            <a href="/powerlinebd/admin/superadmin/flights/flight-add" >
                                <i class="material-icons">add_box</i>
                                <span>Add New Flight</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <?php

                  if($url_active->child_menu == 'place-add' or $url_active->child_menu == 'origin-list' or $url_active->child_menu == 'destination-list' or $url_active->child_menu == 'country-list' or $url_active->child_menu == 'place-detail' or $url_active->child_menu == 'place-edit'
                      or $url_active->child_menu == 'place-delete'){
                    echo '<li class="active">';
                  }else{
                    echo '<li>';
                  }

                 ?>
                    <a href="javascript:void(0);" class="menu-toggle">
                        <i class="material-icons">location_on</i>
                        <span>Origin/Destination/Country</span>
                    </a>
                    <ul class="ml-menu">
                      <?php

                        if($url_active->child_menu == 'origin-list'){
                          echo '<li class="active">';
                        }else{
                          echo '<li>';
                        }

                       ?>
                          <a href="/powerlinebd/admin/superadmin/places/origin-list/1" >
                              <i class="material-icons">view_stream</i>
                              <span>Origin List</span>
                          </a>
                      </li>
                      <?php

                        if($url_active->child_menu == 'destination-list'){
                          echo '<li class="active">';
                        }else{
                          echo '<li>';
                        }

                       ?>
                            <a href="/powerlinebd/admin/superadmin/places/destination-list/1" >
                                <i class="material-icons">view_stream</i>
                                <span>Destination List</span>
                            </a>
                        </li>
                        <?php

                          if($url_active->child_menu == 'country-list'){
                            echo '<li class="active">';
                          }else{
                            echo '<li>';
                          }

                         ?>
                            <a href="/powerlinebd/admin/superadmin/places/country-list/1" >
                                <i class="material-icons">view_stream</i>
                                <span>Country List</span>
                            </a>
                        </li>
                        <?php

                          if($url_active->child_menu == 'place-add'){
                            echo '<li class="active">';
                          }else{
                            echo '<li>';
                          }

                         ?>
                            <a href="/powerlinebd/admin/superadmin/places/place-add" >
                                <i class="material-icons">add_box</i>
                                <span>Add New Record</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <?php

                  if($url_active->child_menu == 'shipper-list' or $url_active->child_menu == 'shipper-add' or $url_active->child_menu == 'shipper-detail' or $url_active->child_menu == 'shipper-edit'
                      or $url_active->child_menu == 'contract-add' or $url_active->child_menu == 'contact-detail' or $url_active->child_menu == 'contact-edit' or $url_active->child_menu == 'shipper-delete' or $url_active->child_menu == 'contact-delete'){
                    echo '<li class="active">';
                  }else{
                    echo '<li>';
                  }

                 ?>
                    <a href="javascript:void(0);" class="menu-toggle">
                        <i class="material-icons">local_shipping</i>
                        <span>Shippers</span>
                    </a>
                    <ul class="ml-menu">
                      <?php

                        if($url_active->child_menu == 'shipper-list' or $url_active->child_menu == 'shipper-detail' or $url_active->child_menu == 'shipper-edit' or $url_active->child_menu == 'contract-add'
                            or $url_active->child_menu == 'contact-detail' or $url_active->child_menu == 'contact-edit' or $url_active->child_menu == 'shipper-delete' or $url_active->child_menu == 'contact-delete'){
                          echo '<li class="active">';
                        }else{
                          echo '<li>';
                        }

                       ?>
                          <a href="/powerlinebd/admin/superadmin/shippers/shipper-list/1" >
                              <i class="material-icons">view_stream</i>
                              <span>Shippers List</span>
                          </a>
                      </li>
                      <?php

                        if($url_active->child_menu == 'shipper-add'){
                          echo '<li class="active">';
                        }else{
                          echo '<li>';
                        }

                       ?>
                            <a href="/powerlinebd/admin/superadmin/shippers/shipper-add" >
                                <i class="material-icons">create</i>
                                <span>Create Shippers</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <?php

                  if($url_active->child_menu == 'consignee-list' or $url_active->child_menu == 'consignee-add' or $url_active->child_menu == 'consignee-detail' or $url_active->child_menu == 'consignee-edit'
                      or $url_active->child_menu == 'contact-add' or $url_active->child_menu == 'consignee-contact-detail' or $url_active->child_menu == 'consignee-contact-edit'
                      or $url_active->child_menu == 'consignee-shipper-add' or $url_active->child_menu == 'consignee-delete' or $url_active->child_menu == 'consignee-contact-delete'){
                    echo '<li class="active">';
                  }else{
                    echo '<li>';
                  }

                 ?>
                    <a href="javascript:void(0);" class="menu-toggle">
                        <i class="material-icons">shopping_cart</i>
                        <span>Consignee</span>
                    </a>
                    <ul class="ml-menu">
                      <?php

                        if($url_active->child_menu == 'consignee-list' or $url_active->child_menu == 'consignee-detail' or $url_active->child_menu == 'consignee-edit' or $url_active->child_menu == 'contact-add'
                            or $url_active->child_menu == 'consignee-contact-detail' or $url_active->child_menu == 'consignee-contact-edit' or $url_active->child_menu == 'consignee-shipper-add' or $url_active->child_menu == 'consignee-delete'
                           or $url_active->child_menu == 'consignee-contact-delete'){
                          echo '<li class="active">';
                        }else{
                          echo '<li>';
                        }

                       ?>
                          <a href="/powerlinebd/admin/superadmin/consignee/consignee-list/1" >
                              <i class="material-icons">view_stream</i>
                              <span>Consignee List</span>
                          </a>
                      </li>
                      <?php

                        if($url_active->child_menu == 'consignee-add'){
                          echo '<li class="active">';
                        }else{
                          echo '<li>';
                        }

                       ?>
                            <a href="/powerlinebd/admin/superadmin/consignee/consignee-add" >
                                <i class="material-icons">create</i>
                                <span>Create Consignee</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <?php

                  if($url_active->parent_menu == 'office_management'){
                    echo '<li class="active">';
                  }else{
                    echo '<li>';
                  }

                 ?>
                    <a href="javascript:void(0);" class="menu-toggle">
                        <i class="material-icons">work</i>
                        <span>Office Management</span>
                    </a>
                    <ul class="ml-menu">
                      <?php

                        if($url_active->child_menu == 'branch-list' or $url_active->child_menu == 'branch-detail' or $url_active->child_menu == 'branch-edit' or $url_active->child_menu == 'branch-delete'){
                          echo '<li class="active">';
                        }else{
                          echo '<li>';
                        }

                       ?>
                          <a href="/powerlinebd/admin/superadmin/office_management/branch-list/1" >
                              <i class="material-icons">business</i>
                              <span>Branch List</span>
                          </a>
                      </li>
                      <?php

                        if($url_active->child_menu == 'user-list' or $url_active->child_menu == 'user-detail' or $url_active->child_menu == 'user-edit' or $url_active->child_menu == 'delete-user'
                            or $url_active->child_menu == 'user-login-edit'){
                          echo '<li class="active">';
                        }else{
                          echo '<li>';
                        }

                       ?>
                            <a href="/powerlinebd/admin/superadmin/office_management/user-list/1" >
                                <i class="material-icons">person</i>
                                <span>User List</span>
                            </a>
                        </li>
                        <?php

                          if($url_active->child_menu == 'create-branch'){
                            echo '<li class="active">';
                          }else{
                            echo '<li>';
                          }

                         ?>
                            <a href="/powerlinebd/admin/superadmin/office_management/create-branch" >
                                <i class="material-icons">create</i>
                                <span>Create Branch</span>
                            </a>
                        </li>
                        <?php

                          if($url_active->child_menu == 'create-user'){
                            echo '<li class="active">';
                          }else{
                            echo '<li>';
                          }

                         ?>
                            <a href="/powerlinebd/admin/superadmin/office_management/create-user" >
                                <i class="material-icons">person_add</i>
                                <span>Create User</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <?php

                  if($url_active->child_menu == 'log-report'){
                    echo '<li class="active">';
                  }else{
                    echo '<li>';
                  }

                 ?>
                    <a href="/powerlinebd/admin/superadmin/logs/log-report/1">
                        <i class="material-icons">access_time</i>
                        <span>System Log</span>
                    </a>
                </li>

            </ul>
        </div>
        <!-- #Menu -->
        <!-- Footer -->
        <div class="legal">
            <div class="copyright">
                &copy; 2018 <a href="javascript:void(0);">Skooby - Technology for Impact</a>.
            </div>
            <div class="version">
                <b>Version: </b> 1.0.1
            </div>
        </div>
        <!-- #Footer -->
    </aside>
    <!-- #END# Left Sidebar -->
</section>
