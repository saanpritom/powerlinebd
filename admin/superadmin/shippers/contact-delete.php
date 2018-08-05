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

                              $user_id = $_SESSION["plbd_id"];

                              //data clearance;
                              $contact_id = $clearence->escape_string($contact_id);
                              $contact_id = strip_tags(trim($contact_id));
                              $contact_id = htmlentities($contact_id);



                          ?>

                            <h2 style="text-align: left;">
                                Contact Delete
                            </h2>



                        </div>
                        <div class="body">


                          <?php

                            $contact_delete = $get_contact->delete_contact($contact_id, $user_id);

                            if($contact_delete == 'Contact deleted Successfully'){

                              ?>

                              <div class="alert bg-green">
                                  Contact deleted Successfully
                              </div>

                              <?php

                            }else{

                              ?>

                              <div class="alert bg-red">
                                  <?php echo $contact_delete; ?>
                              </div>

                              <?php

                            }


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
