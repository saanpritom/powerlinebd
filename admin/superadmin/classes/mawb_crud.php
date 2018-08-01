<?php

include_once('../../../super_classes/db_connection.php');
include_once('../../../super_classes/timer.php');

class MAWBCrudOperation extends DbConfig
{
    public function __construct()
    {
        parent::__construct();
    }

    public $timer_id, $status_message;

    //fetching time_id from timer class;
    public function fetch_time()
    {

      $timing = new Timer();
      $this->timer_id = $timing->create_timer_id();
      return $this->timer_id;

    }

    //insert data into log report table;
    public function log_report_insert($user_id, $report_data, $timing_id)
    {


      $query = "INSERT INTO log_report(user_id, log_report, timer_id)
                 VALUES ('$user_id','$report_data','$timing_id')";
      if($this->connection->query($query))
      {
        return true;
      }else{
        return false;
      }

    }

    //check if the user id is exist on the database;
    public function check_mawb_id($mawb_id)
    {
      $query = "SELECT sl_num FROM mawb_details WHERE mawb_id='$mawb_id'";
      $result = $this->connection->query($query);
      if($result->num_rows >= 1) {
        return true;
      }else{
        return false;
      }
    }

    //check if the user id is exist on the database;
    public function check_mawb_number($mawb_number)
    {
      $query = "SELECT sl_num FROM mawb_details WHERE mawb_number='$mawb_number'";
      $result = $this->connection->query($query);
      if($result->num_rows >= 1) {
        return true;
      }else{
        return false;
      }
    }

    //creating a new user on the database;
    public function create_mawb($mawb_number, $user_id)
    {

      //calling the timer fetch function;
      $this->timer_id = $this->fetch_time();

      //create and check user_id;
      $mawb_id = mt_rand();

      //call user id check function
      $this->mawb_id_check = $this->check_mawb_id($mawb_id);

      //check mawb number uniqueness
      $this->mawb_number_check = $this->check_mawb_number($mawb_number);

      if($this->mawb_id_check){
        $this->usering_id = 'MAWB ID overflow. Please try again';
        return $this->usering_id;
      }elseif($this->mawb_number_check){
        $this->usering_id = 'MAWB number already exists. Please try a different one';
        return $this->usering_id;
      }else{

        //check if timer_id created properly
        if(is_numeric($this->timer_id)){

          //first query inserting data into login table;
          $query = "INSERT INTO `mawb_details`(`mawb_id`, `mawb_number`, `timer_id`)
                    VALUES ('$mawb_id', '$mawb_number', '$this->timer_id')";

          if($this->connection->query($query)){

            $report_data = 'MAWB ' . $mawb_number . ' created by User';

            $report_status = $this->log_report_insert($user_id, $report_data, $this->timer_id);

            if($report_status){

              $this->status_message = 'Successfully created a new MAWB';
              return $this->status_message;

            }else{

              $this->status_message = 'Successfully created a new MAWB but log can not be generated';
              return $this->status_message;

            }


          }else{
            $this->status_message = 'Problem creating new MAWB. Please try again';
            return $this->status_message;
          }

        }else{
          return $this->timer_id;
        }

      }

    }

    //fetch data from branch table;
    public function getData($query)
    {
      $result = $this->connection->query($query);

        if ($result == false) {
            return false;
        }

        $rows = array();

        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }

        return $rows;
    }


    //updating a new user on the database;
    public function update_mawb($mawb_id, $mawb_number, $user_id)
    {

      //calling the timer fetch function;
      $this->timer_id = $this->fetch_time();

      $this->mawb_number_check = $this->check_mawb_number($mawb_number);

      if($this->mawb_number_check){

        $this->usering_id = 'MAWB number already exists. Please try a different one';

        return $this->usering_id;

      }else{

        if(is_numeric($this->timer_id)){

          $query = "UPDATE `mawb_details` SET `mawb_number`='$mawb_number' WHERE `mawb_id`='$mawb_id'";

          if($this->connection->query($query)){

            $report_data = 'MAWB ' . $mawb_number . ' is updated by User';

            //update log record data;
            $report_status = $this->log_report_insert($user_id, $report_data, $this->timer_id);

            if($report_status){

              $this->status_message = 'MAWB updated Successfully';
              return $this->status_message;

            }else{

              //insert log report not working unknown reason;
              $this->status_message = 'MAWB updated but log report can not be inserted';
              return $this->status_message;

            }

          }else{

            return 'Some problem happened. Please try again later.';

          }


        }else{
          return $this->timer_id;
        }

      }

    }


    public function bulk_awb_lock($mawb_number, $flight_number, $bag_number, $next_delivery, array $awb_numbers, $user_id)
    {

      //check mawb_number exists or not;

      $query = "SELECT mawb_id FROM mawb_details WHERE mawb_number='$mawb_number'";

      $result = $this->connection->query($query);

      if($result->num_rows >= 1) {



              //calling the timer fetch function;
              $this->timer_id = $this->fetch_time();

              if($flight_number == ''){
                $flight_number = 0;
              }

              if($bag_number == ''){
                $bag_number = 0;
              }

              //fetch mawb_id by mawb_number
              $query = "SELECT mawb_id FROM mawb_details WHERE mawb_number='$mawb_number'";

              $result = $this->getData($query);

              foreach ($result as $key => $res)
              {

                $mawb_id = $res['mawb_id'];

              }

              $counter = 0;

              foreach ($awb_numbers as $key => $awb_number) {

                $query = "UPDATE `awb_lock` SET `lock_status`='locked' WHERE `awb_id`='$awb_number'";

                if($this->connection->query($query)){



                  $query = "INSERT INTO `awb_mawb_flight_relation`(`awb_id`, `mawb_id`, `flight_id`, `next_branch`)
                            VALUES ('$awb_number', '$mawb_id', '$flight_number', '$next_delivery')";

                  if($this->connection->query($query)){

                    //update AWB status;
                    //First make previous status inactive
                    $query = "UPDATE `awb_status` SET `status_active`='0' WHERE awb_id='$awb_number'";

                    if($this->connection->query($query)){

                      //insert new status
                      $query = "INSERT INTO `awb_status`(`awb_id`, `user_id`, `timer_id`, `delivery_status`, `status_active`)
                                VALUES ('$awb_number', '$user_id', '$this->timer_id', 'On the way', '1')";

                      if($this->connection->query($query)){

                        //update bag number
                        $query = "UPDATE `awb_details` SET `bag_number`='$bag_number' WHERE awb_id='$awb_number'";

                        if($this->connection->query($query)){

                          $counter = $counter + 1;

                        }else{

                          $this->status_message = 'Cannot update bag number. Please try again';
                          return $this->status_message;

                        }

                      }else{

                        $this->status_message = 'Cannot update AWB status. Please try again';
                        return $this->status_message;

                      }

                    }else{

                      $this->status_message = 'Cannot update AWB status_active. Please try again';
                      return $this->status_message;

                    }

                  }else{

                    $this->status_message = 'Cannot insert new MAWB Flight. Please try again';
                    return $this->status_message;

                  }


                }else{

                  $this->status_message = 'Cannot lock AWB. Please try again';
                  return $this->status_message;

                }



              }

              //check if all AWB lock Successfully
              if($counter == sizeof($awb_numbers)){

                $report_data = 'Multiple AWBs locked by User';

                $report_status = $this->log_report_insert($user_id, $report_data, $this->timer_id);

                if($report_status){

                  $this->status_message = 'Successfully locked AWBs';

                  return $this->status_message;

                }else{

                  $this->status_message = 'AWB is locked but log report cannot be generated';

                  return $this->status_message;


                }

              }else{

                $this->status_message = 'Something wrong happened. Please unlock the locked AWBs and try again';
                return $this->status_message;

              }



      }else{

        $this->status_message = 'This MAWB number does not exist';

        return $this->status_message;

      }




    }


    public function unlock_multiple_awb($mawb_number, $user_id){

      //fetch mawb_id by mawb_number
      $query = "SELECT mawb_id FROM mawb_details WHERE mawb_number='$mawb_number'";

      $result = $this->connection->query($query);

      foreach ($result as $key => $res) {

        $mawb_id = $res['mawb_id'];

      }


      //check if the mawb has awb or not;
      $check_mawb_exist = 0;

      $query = "SELECT sl_num FROM awb_mawb_flight_relation WHERE mawb_id='$mawb_id'";

      $result = $this->connection->query($query);

      if($result->num_rows >= 1) {

        $check_mawb_exist = 1;

      }else{

        $check_mawb_exist = 0;

      }


      if($check_mawb_exist == 0){

        $this->status_message = 'This MAWB has no AWB related.';

        return $this->status_message;


      }else{

        //calling the timer fetch function;
        //$this->timer_id = $this->fetch_time();

        //fetch all AWB ids associated with this MAWB
        $awb_ids = array();

        $i = 0;

        $query = "SELECT awb_id FROM awb_mawb_flight_relation WHERE mawb_id='$mawb_id'";

        $result = $this->connection->query($query);

        foreach ($result as $key => $res) {

          $awb_ids[$i] = $res['awb_id'];

          $i++;

        }

        $counter = 0;

        foreach ($awb_ids as $key => $awb_id) {

          //calling the timer fetch function;
          $this->timer_id = $this->fetch_time();

          if(is_numeric($this->timer_id)){

            $query = "UPDATE `awb_lock` SET `lock_status`='unlocked' WHERE awb_id='$awb_id'";

            if($this->connection->query($query)){

              $query = "SELECT sl_num FROM awb_status WHERE awb_id='$awb_id' AND status_active='1'";

              $result = $this->getData($query);

              foreach ($result as $key => $res) {

                $sl_num = $res['sl_num'];

              }

              //query running double times for unkown reason. This only works in 'Received by branch' is seletcted
              $query = "SELECT delivery_status FROM awb_status WHERE awb_id='$awb_id' AND sl_num='$sl_num'";

              $result = $this->getData($query);

              foreach ($result as $key => $res) {

                $dev_status = $res['delivery_status'];

              }

              if($dev_status != 'Received by branch'){

                //inactive previous status;
                $query = "UPDATE `awb_status` SET `status_active`='0' WHERE awb_id='$awb_id' AND sl_num='$sl_num'";

                if($this->connection->query($query)){

                  $query = "INSERT INTO `awb_status`(`awb_id`, `user_id`, `timer_id`, `delivery_status`, `status_active`)
                            VALUES ('$awb_id', '$user_id', '$this->timer_id', 'Received by branch', '1')";

                  if($this->connection->query($query)){

                    $report_data = 'AWB unlocked ' . $awb_id . ' by User';

                    $report_status = $this->log_report_insert($user_id, $report_data, $this->timer_id);

                    if($report_status){

                      $counter++;

                    }else{

                      $this->status_message = 'AWB is unlocked but log report cannot be generated';

                      return $this->status_message;


                    }

                  }else{

                    $this->status_message = 'Problem creating new status. Please try again';

                    return $this->status_message;

                  }

                }else{

                  $this->status_message = 'Problem changing previous status. Please try again';

                  return $this->status_message;

                }

              }else{

                $this->status_message = 'That unknown error found';

                return $this->status_message;

              }

            }else{

              $this->status_message = 'Problem unlocking AWB. Please try again';

              return $this->status_message;

            }

          }else{

            $this->status_message = 'Problem unlocking. Please try again';

            return $this->status_message;

          }




        }


        if($counter == sizeof($awb_ids)){

          $this->status_message = 'Successfully unlocked AWBs';

          return $this->status_message;

        }else{

          $this->status_message = 'Something wrong happened. Please delete and try again';

          return $this->status_message;

        }

      }





    }


}


?>
