<?php

  include('../static_references/header.php');
  include('../static_references/search.php');
  include('../static_references/navbar.php');

?>

<section class="content">
        <div class="container-fluid">

<!-- Vertical Layout -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                                Create New User
                            </h2>

                        </div>
                        <div class="body">
                            <form>
                                <label for="name">Name</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" id="name" class="form-control" required aria-required="true" placeholder="Enter user's name here">
                                    </div>
                                </div>
                                <label for="email_address">Email Address</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="email" id="email_address" class="form-control" placeholder="Enter user's email address">
                                    </div>
                                </div>
                                <label for="contact_number">Contact Number</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" id="contact_number" class="form-control" placeholder="Enter user's contact number">
                                    </div>
                                </div>
                                <label for="gender">Gender</label>
                                <div class="form-group">
                                    <input type="radio" name="gender" id="male" class="with-gap radio-col-deep-orange">
                                    <label for="male">Male</label>

                                    <input type="radio" name="gender" id="female" class="with-gap radio-col-deep-orange">
                                    <label for="female" class="m-l-20">Female</label>
                                </div>

                                <label for="branch_name">Branch Name</label>
                                <div class="form-group">
                                  <select class="form-control show-tick">
                                      <option value="0">-- Select Branch --</option>
                                      <option value="10">10</option>
                                      <option value="20">20</option>
                                      <option value="30">30</option>
                                      <option value="40">40</option>
                                      <option value="50">50</option>
                                  </select>
                                </div>

                                <label for="user_role">User Role</label>
                                <div class="form-group">
                                  <select class="form-control show-tick">
                                      <option value="0">-- Select User Role --</option>
                                      <option value="10">Super Admin</option>
                                      <option value="20">Finance User</option>
                                      <option value="30">Business User</option>
                                  </select>
                                </div>

                                <br>
                                <button type="button" class="btn bg-deep-orange waves-effect m-t-15">Create</button>
                            </form>
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
