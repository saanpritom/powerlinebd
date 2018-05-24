
<?php

include('../classes/navigation-active.php');

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
                <!-- Notifications -->
                <li class="dropdown">
                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button">
                        <i class="material-icons">notifications</i>
                        <span class="label-count">1</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header">NOTIFICATIONS</li>
                        <li class="body">
                            <ul class="menu">
                                <li>
                                    <a href="javascript:void(0);">
                                        <div class="icon-circle bg-light-green">
                                            <i class="material-icons">person_add</i>
                                        </div>
                                        <div class="menu-info">
                                            <h4>12 new members joined</h4>
                                            <p>
                                                <i class="material-icons">access_time</i> 14 mins ago
                                            </p>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="footer">
                            <a href="javascript:void(0);">View All Notifications</a>
                        </li>
                    </ul>
                </li>
                <!-- #END# Notifications -->
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
                <div class="name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">John Doe</div>
                <div class="email">Superuser Panel</div>
                <div class="btn-group user-helper-dropdown">
                    <i class="material-icons" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">keyboard_arrow_down</i>
                    <ul class="dropdown-menu pull-right">
                        <li><a href="javascript:void(0);"><i class="material-icons">person</i>Profile</a></li>
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


                <li>
                    <a href="javascript:void(0);" class="menu-toggle">
                        <i class="material-icons">flight_takeoff</i>
                        <span>Shipments</span>
                    </a>
                    <ul class="ml-menu">
                      <li>
                          <a href="javascript:void(0);" >
                              <i class="material-icons">view_stream</i>
                              <span>All Shipments</span>
                          </a>
                      </li>
                        <li>
                            <a href="javascript:void(0);" >
                                <i class="material-icons">cancel</i>
                                <span>Undelivered Shipments</span>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" >
                                <i class="material-icons">add_box</i>
                                <span>Create New Shipment</span>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" >
                                <i class="material-icons">add_box</i>
                                <span>Create New Shipment</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="../superadmin/dashboard">
                        <i class="material-icons">description</i>
                        <span>Manifest Report</span>
                    </a>
                </li>


                <li>
                    <a href="javascript:void(0);" class="menu-toggle">
                        <i class="material-icons">group_work</i>
                        <span>Master AWB</span>
                    </a>
                    <ul class="ml-menu">
                      <li>
                          <a href="javascript:void(0);" >
                              <i class="material-icons">view_stream</i>
                              <span>All MAWB</span>
                          </a>
                      </li>
                        <li>
                            <a href="javascript:void(0);" >
                                <i class="material-icons">cancel</i>
                                <span>Incomplete MAWB</span>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" >
                                <i class="material-icons">add_box</i>
                                <span>Create New MAWB</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="javascript:void(0);" class="menu-toggle">
                        <i class="material-icons">flight</i>
                        <span>Flights</span>
                    </a>
                    <ul class="ml-menu">
                      <li>
                          <a href="javascript:void(0);" >
                              <i class="material-icons">view_stream</i>
                              <span>All Flight List</span>
                          </a>
                      </li>
                        <li>
                            <a href="javascript:void(0);" >
                                <i class="material-icons">add_box</i>
                                <span>Add New Flight</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="javascript:void(0);" class="menu-toggle">
                        <i class="material-icons">location_on</i>
                        <span>Origin/Destination/Country</span>
                    </a>
                    <ul class="ml-menu">
                      <li>
                          <a href="javascript:void(0);" >
                              <i class="material-icons">view_stream</i>
                              <span>Origin List</span>
                          </a>
                      </li>
                        <li>
                            <a href="javascript:void(0);" >
                                <i class="material-icons">view_stream</i>
                                <span>Destination List</span>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" >
                                <i class="material-icons">view_stream</i>
                                <span>Country List</span>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" >
                                <i class="material-icons">add_box</i>
                                <span>Add New Record</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="javascript:void(0);" class="menu-toggle">
                        <i class="material-icons">local_shipping</i>
                        <span>Shippers</span>
                    </a>
                    <ul class="ml-menu">
                      <li>
                          <a href="javascript:void(0);" >
                              <i class="material-icons">view_stream</i>
                              <span>Shippers List</span>
                          </a>
                      </li>
                        <li>
                            <a href="javascript:void(0);" >
                                <i class="material-icons">create</i>
                                <span>Create Shippers</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="javascript:void(0);" class="menu-toggle">
                        <i class="material-icons">shopping_cart</i>
                        <span>Consignee</span>
                    </a>
                    <ul class="ml-menu">
                      <li>
                          <a href="javascript:void(0);" >
                              <i class="material-icons">view_stream</i>
                              <span>Consignee List</span>
                          </a>
                      </li>
                        <li>
                            <a href="javascript:void(0);" >
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

                        if($url_active->child_menu == 'branch-list' or $url_active->child_menu == 'branch-detail' or $url_active->child_menu == 'branch-edit'){
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

                        if($url_active->child_menu == 'user-list' or $url_active->child_menu == 'user-detail' or $url_active->child_menu == 'user-edit'){
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

                <li>
                    <a href="../superadmin/dashboard">
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
