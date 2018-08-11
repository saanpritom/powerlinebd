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
                          header("Content-disposition: attachment; filename=user_login_details.xls");

                          echo 'FROM' . "\t" . "\t" . "\t" . "\t" . "\t" . "\t" . "\t" . "\t" . "\t" . "\t" . "\t" . "\t" . "\t" . "\t" . $manifest_number . "\n";

                          echo $mawb_from . "\t" . "\t" . "\t" . "\t" . "\t" . "\t" . "\t" . "\t" . "\t" . "\t" . "\t" . "\t" . "\t" . "\t" . $flight_number . "\n";

                          echo 'TO' . "\t" . "\t" . "\t" . "\t" . "\t" . "\t" . "\t" . "\t" . "\t" . "\t" . "\t" . "\t" . "\t" . "\t" . $creation_date . "\n";

                          echo $mawb_to . "\t" . "\t" . "\t" . "\t" . "\t" . "\t" . "\t" . "\t" . "\t" . "\t" . "\t" . "\t" . "\t" . "\t" . $creation_time . "\n";

                          echo $temp_mawb_number . "\t" . "\t" . "\t" . "\t" . "\t" . "\t" . "\t" . "\t" . "\t" . "\t" . "\t" . "\t" . "\t" . "\t" . $bag_number . "\n";

                          //echo 'IP Address' . "\t" . 'Date' . "\t" . 'Time' . "\n";


                          /*$report_array = array();

                          $i=0;


                          //query to get data;
                          $query = "SELECT awb_mawb_flight_relation.flight_id, awb_details.awb_id, awb_details.consignee_id,
                                    awb_details.destination_id, awb_details.bag_number, awb_details.type, awb_details.pcs,
                                    awb_details.`a.weight`, awb_details.`b.weight`, awb_details.value, shipper_details.name AS s_name,
                                    shipper_details.address AS s_address, origin_destination_details.short_form AS s_s_form FROM awb_mawb_flight_relation
                                    INNER JOIN awb_details ON awb_mawb_flight_relation.awb_id=awb_details.awb_id
                                    INNER JOIN shipper_details ON awb_details.shipper_id=shipper_details.shipper_id
                                    INNER JOIN origin_destination_details ON shipper_details.country_id=origin_destination_details.o_id_id
                                    WHERE awb_mawb_flight_relation.mawb_id='$mawb_id'";

                          $result = $get_mawb->getData($query);

                          foreach ($result as $key => $value) {

                            $report_array[$i] = $value['flight_id'];

                            $i++;

                            $report_array[$i] = $value['awb_id'];

                            $i++;

                            //check if consignee is new or existing
                            if(is_numeric($value['consignee_id'])){

                              $temp_consignee_id = $value['consignee_id'];

                              $query2 = "SELECT consignee_details.name AS c_name, consignee_details.address AS c_address, origin_destination_details.short_form AS c_s_address,
                                        origin_destination_details.full_name FROM consignee_details
                                        INNER JOIN origin_destination_details ON consignee_details.country_id=origin_destination_details.o_id_id
                                        WHERE consignee_details.consignee_id='$temp_consignee_id'";

                              $result2 = $get_mawb->getData($query2);

                              foreach ($result2 as $key => $value2) {

                                $report_array[$i] = $value2['c_name'];

                                $i++;

                                $report_array[$i] = $value2['c_address'];

                                $i++;

                                $report_array[$i] = $value2['c_s_address'];

                                $i++;

                                $report_array[$i] = $value2['full_name'];

                                $i++;

                              }


                            }else{

                              $report_array[$i] = $value['consignee_id'];

                              $i++;

                              $report_array[$i] = $value['destination_id'];

                              $i++;


                            }


                            $report_array[$i] = $value['bag_number'];

                            $i++;

                            $report_array[$i] = $value['type'];

                            $i++;

                            $report_array[$i] = $value['pcs'];

                            $i++;

                            $report_array[$i] = $value['a.weight'];

                            $i++;

                            $report_array[$i] = $value['b.weight'];

                            $i++;

                            $report_array[$i] = $value['value'];

                            $i++;

                            $report_array[$i] = $value['s_name'];

                            $i++;

                            $report_array[$i] = $value['s_address'];

                            $i++;

                            $report_array[$i] = $value['s_s_form'];

                            $i++;


                          }*/

                          /*$result = $excel->excel_generator($query, $user_id, 'user_login');


                          //create excel file;
                          header("Content-Type: application/vnd.ms-excel");
                          header("Content-disposition: attachment; filename=user_login_details.xls");

                          echo 'IP Address' . "\t" . 'Date' . "\t" . 'Time' . "\n";

                          foreach($result as $key => $res){
                            echo $res['public_ip'] . "\t" . $res['creation_date'] . "\t" . $res['creation_time'] . "\n";
                          }*/



                      ?>
