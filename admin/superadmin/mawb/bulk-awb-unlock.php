<?php

  require_once('../classes/authentication.php');
  include('../static_references/header.php');
  include('../static_references/search.php');
  include('../static_references/navbar.php');
  include_once('../classes/data_validation.php');
  include_once('../classes/data_clearence.php');
  include_once('../classes/mawb_crud.php');

?>

<section class="content">
        <div class="container-fluid">

          <?php

            if(isset($_POST['submit'])){

          ?>
          <div class="row clearfix">
              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="body">

                      <?php

                          //declare classes for checking;
                          $validation = new Validation();
                          $clearence = new Clearence();
                          $mawb_operation = new MAWBCrudOperation();

                          //mysql escape string clearence;
                          $mawb_number = $clearence->escape_string($_POST['mawb_number']);


                          //input data triming;
                          $mawb_number = strip_tags(trim($mawb_number));

                          // Escape any html characters;
                          $mawb_number = htmlentities($mawb_number);

                          //fetch MAWB_ID. This is important because of Go Back button;
                          $query = "SELECT mawb_id FROM mawb_details WHERE mawb_number='$mawb_number'";

                          $fetch_mawb_id = $mawb_operation->getData($query);

                          foreach ($fetch_mawb_id as $key => $value) {

                            $mawb_id = $value['mawb_id'];

                          }


                          //check refined and input values are empty and valid or not;
                          $msg = $validation->check_empty(array($mawb_number));

                          if($msg != null){
                            ?>
                            <div class="alert bg-red">
                                <?php echo $msg; ?>
                            </div>
                            <?php

                          }else{

                            $user_id = $_SESSION["plbd_id"];

                            //sending all variables to branch_crud for creating new branch;
                            $new_mawb = $mawb_operation->unlock_multiple_awb($mawb_number, $user_id);

                            //check if branch created properly;
                            if($new_mawb == 'Successfully unlocked AWBs'){
                              ?>
                              <div class="alert bg-green">
                                  <?php echo 'Successfully unlocked AWBs'; ?>
                              </div>
                              <?php
                            }else{
                              ?>
                              <div class="alert bg-red">
                                  <?php echo $new_mawb; ?>
                              </div>
                              <?php
                            }

                          }

                      ?>

                      <a href="/powerlinebd/admin/superadmin/mawb/mawb-detail/<?php echo $mawb_id; ?>" type="button" class="btn bg-teal waves-effect">Go back</a>

                    </div>
                  </div>
              </div>
            </div>

            <?php
              }

            ?>


<!-- Vertical Layout -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                                Unlock AWBs
                            </h2>

                        </div>
                        <div class="body">
                            <form action="bulk-awb-unlock" method="POST">
                              <label for="consignee">MAWB Number</label>
                              <div class="form-group">
                                <div class="form-line frmSearch">
                                  <input type="text" id="search-box" placeholder="MAWB Number" class="form-control" required aria-required="true" name="mawb_number"  autocomplete="off"/>
                                  <div id="suggesstion-box"></div>
                                </div>
                              </div>

                              <script src="https://code.jquery.com/jquery-2.1.1.min.js" type="text/javascript"></script>



                              <script type="text/javascript">


                                  // AJAX call for autocomplete of Consignee
                                  $(document).ready(function(){
                                    $("#search-box").keyup(function(){
                                      $.ajax({
                                      type: "POST",
                                      url: "ajax-call/call-mawb.php",
                                      data:'keyword='+$(this).val(),
                                      beforeSend: function(){
                                        $("#search-box").css("background","#FFF url(LoaderIcon.gif) no-repeat 165px");
                                      },
                                      success: function(data){
                                        $("#suggesstion-box").show();
                                        $("#suggesstion-box").html(data);
                                        $("#search-box").css("background","#FFF");
                                      }
                                      });
                                    });
                                  });
                                  //To select country name
                                  function selectCountry(val) {
                                  $("#search-box").val(val);
                                  $("#suggesstion-box").hide();
                                  }

                              </script>


                                <br>
                                <input type="submit" name="submit" class="btn bg-deep-orange waves-effect m-t-15" value="Create">
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
