<?php

  require_once('../classes/authentication.php');
  include_once('../classes/data_clearence.php');
  include_once('../classes/mawb_crud.php');


                          //declare object;
                          $get_mawb = new MAWBCrudOperation();
                          $clearence = new Clearence();
                          //$excel = new ExcelExporter();

                          $mawb_id = $_GET['b_id'];

                          //data clearance;
                          $mawb_id = $clearence->escape_string($mawb_id);
                          $mawb_id = strip_tags(trim($mawb_id));
                          $mawb_id = htmlentities($mawb_id);


                          $user_id = $_SESSION["plbd_id"];

                          //fetch user branch origin short form
                          $query = "SELECT office_branch.name FROM office_branch INNER JOIN admin_details
                                    ON office_branch.branch_id=admin_details.branch_id WHERE admin_details.admin_id='$user_id'";

                          $result = $get_mawb->getData($query);

                          foreach ($result as $key => $value) {

                            $branch_name = $value['name'];

                          }

                          //create manifest_number
                          //fetch the last number of serial from manifest table
                          $query = "SELECT MAX(sl_num) AS t_sl FROM manifest_report WHERE 1";

                          $result = $get_mawb->getData($query);

                          foreach ($result as $key => $value) {

                            $maximum_numb = $value['t_sl'];

                          }

                          $maximum_numb = $maximum_numb + 1;

                          $manifest_number = 'MANIFEST-' . $branch_name . ' ' . $maximum_numb;


                          $create_manifest_data = $get_mawb->create_manifest_report($mawb_id, $manifest_number);


                          if($create_manifest_data){

                            //fetch MAWB from result
                            $query = "SELECT office_branch.name, mawb_details.mawb_number FROM office_branch INNER JOIN mawb_details
                                      ON office_branch.branch_id=mawb_details.mawb_from
                                      WHERE mawb_details.mawb_id='$mawb_id'";

                            $result = $get_mawb->getData($query);

                            foreach ($result as $key => $value) {

                              $mawb_from = $value['name'];

                              $mawb_number = $value['mawb_number'];

                            }

                            //fetch MAWB to result
                            $flight_number = 0;

                            $bag_number = 0;

                            $mawb_to = 0;


                            $query = "SELECT DISTINCT awb_mawb_flight_relation.flight_id, awb_mawb_flight_relation.bag_number,
                                      office_branch.name FROM awb_mawb_flight_relation
                                      INNER JOIN office_branch ON awb_mawb_flight_relation.next_branch=office_branch.branch_id
                                      WHERE awb_mawb_flight_relation.mawb_id='$mawb_id'";

                            $result = $get_mawb->getData($query);

                            foreach ($result as $key => $value) {

                              $flight_number = $value['flight_id'];

                              $bag_number = $value['bag_number'];

                              $mawb_to = $value['name'];
                            }

                          }else{

                            echo 'cannot create';

                          }

                          //fetch manifest report time and date
                          $query = "SELECT creation_details.creation_date, creation_details.creation_time FROM creation_details
                                    INNER JOIN manifest_report ON creation_details.timer_id=manifest_report.timer_id
                                    WHERE manifest_report.manifest_number='$manifest_number'";

                          $result = $get_mawb->getData($query);

                          foreach ($result as $key => $value) {

                            $creation_date = $value['creation_date'];

                            $creation_time = $value['creation_time'];

                          }

                          $creation_date = 'DATE ' . $creation_date;

                          $creation_time = 'TIME ' . $creation_time;

                          $temp_mawb_number = 'MAWB ' . $mawb_number;

                          $flight_number = 'FLIGHT ' . $flight_number;

                          $bag_number = 'BAG ' . $bag_number;

                          //create excel file header
                          header("Content-Type: application/vnd.ms-excel");
                          header("Content-disposition: attachment; filename=$manifest_number.xls");

                          echo 'FROM' . "\t" . "\t" . "\t" . "\t" . "\t" . "\t" . "\t" . "\t" . "\t" . "\t" . "\t" . "\t" . "\t" . "\t" . $manifest_number . "\n";

                          echo $mawb_from . "\t" . "\t" . "\t" . "\t" . "\t" . "\t" . "\t" . "\t" . "\t" . "\t" . "\t" . "\t" . "\t" . "\t" . $flight_number . "\n";

                          echo 'TO' . "\t" . "\t" . "\t" . "\t" . "\t" . "\t" . "\t" . "\t" . "\t" . "\t" . "\t" . "\t" . "\t" . "\t" . $creation_date . "\n";

                          echo $mawb_to . "\t" . "\t" . "\t" . "\t" . "\t" . "\t" . "\t" . "\t" . "\t" . "\t" . "\t" . "\t" . "\t" . "\t" . $creation_time . "\n";

                          echo $temp_mawb_number . "\t" . "\t" . "\t" . "\t" . "\t" . "\t" . "\t" . "\t" . "\t" . "\t" . "\t" . "\t" . "\t" . "\t" . $bag_number . "\n";

                          echo 'Sl No.' . "\t" . 'AWB' . "\t" . 'Shipper name' . "\t" . 'Shipper Address' . "\t" . 'C/nee. Name' . "\t" . 'C/nee Address' . "\t" . 'Origin' . "\t" . 'Dest.' . "\t" . 'Type' . "\t" . 'Pcs' . "\t" . 'A.Weight' . "\t" . 'B.Weight' . "\t" . 'Value' . "\t" . 'Bag No' . "\t" . 'Country Code' . "\t" . 'FREIGHT' . "\n";


                          $counter = 1;

                          $query = "SELECT awb_details.awb_id, shipper_details.name AS shipper_name, awb_details.consignee_id,
                                    awb_details.destination_id, awb_details.type, awb_details.pcs, awb_details.`a.weight`, awb_details.`b.weight`,
                                    awb_details.value, shipper_details.address AS shipper_address, origin_destination_details.short_form AS origin
                                    FROM awb_details
                                    INNER JOIN shipper_details ON awb_details.shipper_id=shipper_details.shipper_id
                                    INNER JOIN origin_destination_details ON shipper_details.country_id=origin_destination_details.o_id_id
                                    INNER JOIN awb_mawb_flight_relation ON awb_details.awb_id=awb_mawb_flight_relation.awb_id
                                    WHERE awb_mawb_flight_relation.mawb_id='$mawb_id'";

                          $result = $get_mawb->getData($query);

                          foreach ($result as $key => $value) {

                            //check consignee id is existed or new
                            $consignee_id = $value['consignee_id'];

                            if(is_numeric($consignee_id)){

                              $query2 = "SELECT consignee_details.name AS consignee_name, consignee_details.address AS consignee_address,
                                        origin_destination_details.short_form AS c_short FROM consignee_details
                                        INNER JOIN origin_destination_details ON consignee_details.country_id=origin_destination_details.o_id_id
                                        WHERE consignee_details.consignee_id='$consignee_id'";

                              $result2 = $get_mawb->getData($query2);

                              foreach ($result2 as $key => $value2) {

                                $consignee_name = $value2['consignee_name'];

                                $consignee_address = $value2['consignee_address'];

                                $consignee_short = $value2['c_short'];

                              }


                            }else{

                              $consignee_name = $value['consignee_id'];

                              $consignee_address = $value['destination_id'];

                              $consignee_short = '';


                            }

                            echo $counter . "\t" . $value['awb_id'] . "\t" . $value['shipper_name'] . "\t" . $value['shipper_address'] . "\t" . $consignee_name . "\t" . $consignee_address . "\t" . $value['origin'] . "\t" . $consignee_short . "\t" . $value['type'] . "\t" . $value['pcs'] . "\t" . $value['a.weight'] . "\t" . $value['b.weight'] . "\t" . $value['value'] . "\t" . ' ' . "\t" . ' ' . "\t" . 'PREPAID' . "\n";



                            $counter++;

                          }



                      ?>
