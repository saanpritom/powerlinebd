<?php

  include_once('../classes/data_clearence.php');
  include_once('../classes/admin_user_crud.php');
  include_once('../classes/excel_generate.php');


                          //declare object;
                          $get_user = new UserCrudOperation();
                          $clearence = new Clearence();
                          $excel = new ExcelExporter();

                          $user_id = $_GET['b_id'];

                          //data clearance;
                          $user_id = $clearence->escape_string($user_id);
                          $user_id = strip_tags(trim($user_id));
                          $user_id = htmlentities($user_id);

                          //extracting branch_id and page number from complecated URL;
                          $exploded_url = explode('/', $user_id);

                          $action_name = end($exploded_url);

                          $user_id = prev($exploded_url);

                          //check which table to call;
                          if($action_name == 'user_login'){

                            //query to get data;
                            $query = "SELECT login_log.public_ip, creation_details.creation_date,
                                      creation_details.creation_time FROM login_log INNER JOIN
                                      creation_details ON login_log.timer_id=creation_details.timer_id
                                      WHERE login_log.user_id='$user_id'
                                      ORDER BY creation_details.creation_date DESC";

                            $result = $excel->excel_generator($query, $user_id, 'user_login');


                            //create excel file;
                            header("Content-Type: application/vnd.ms-excel");
                          	header("Content-disposition: attachment; filename=user_login_details.xls");

                          	echo 'IP Address' . "\t" . 'Date' . "\t" . 'Time' . "\n";

                            foreach($result as $key => $res){
                              echo $res['public_ip'] . "\t" . $res['creation_date'] . "\t" . $res['creation_time'] . "\n";
                            }

                          }else if($action_name == 'log_report'){

                            //query to get data;
                            $query = "SELECT log_report.log_report, creation_details.creation_date, creation_details.creation_time
                                      FROM log_report INNER JOIN creation_details ON log_report.timer_id=creation_details.timer_id
                                      WHERE log_report.user_id='$user_id' ORDER BY creation_details.creation_date DESC";

                            $result = $excel->excel_generator($query, $user_id, 'activity_report');


                            //create excel file;
                            header("Content-Type: application/vnd.ms-excel");
                          	header("Content-disposition: attachment; filename=user_activity_details.xls");

                          	echo 'Log Report' . "\t" . 'Date' . "\t" . 'Time' . "\n";

                            foreach($result as $key => $res){
                              echo $res['log_report'] . "\t" . $res['creation_date'] . "\t" . $res['creation_time'] . "\n";
                            }

                          }else{

                            echo 'Not matched';

                          }



                      ?>
