<?php

  include_once('../classes/data_clearence.php');
  include_once('../classes/mawb_crud.php');
  include_once('../classes/excel_generate.php');


                          //declare object;
                          $get_mawb = new MAWBCrudOperation();
                          $clearence = new Clearence();
                          $excel = new ExcelExporter();

                          $mawb_id = $_GET['b_id'];

                          //data clearance;
                          $mawb_id = $clearence->escape_string($mawb_id);
                          $mawb_id = strip_tags(trim($mawb_id));
                          $mawb_id = htmlentities($mawb_id);


                          $report_array = array();

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


                          }

                          /*$result = $excel->excel_generator($query, $user_id, 'user_login');


                          //create excel file;
                          header("Content-Type: application/vnd.ms-excel");
                          header("Content-disposition: attachment; filename=user_login_details.xls");

                          echo 'IP Address' . "\t" . 'Date' . "\t" . 'Time' . "\n";

                          foreach($result as $key => $res){
                            echo $res['public_ip'] . "\t" . $res['creation_date'] . "\t" . $res['creation_time'] . "\n";
                          }*/



                      ?>
