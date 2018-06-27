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

    public function update_query($shipper_id, $shipper_name, $email_address, $contact_number, $country_id, $address, $user_id)
    {

      //check if timer_id created properly
        if(is_numeric($this->timer_id)){

          //first query inserting data into login table;
          $query = "UPDATE `login_info` SET `email`='$email_address' WHERE `user_id`='$this->usering_id'";

          if($this->connection->query($query)){

            //second query inserting data into admin details table;
            $query = "UPDATE `shipper_details` SET `name`='$shipper_name',`country_id`='$country_id',
                      `address`='$address',`contact_number`='$contact_number' WHERE `shipper_id`='$this->usering_id'";


            if($this->connection->query($query)){


              $report_data = 'Shipper ' . $shipper_name . ' is updated by User';

              //update log record data;
              $report_status = $this->log_report_insert($user_id, $report_data, $this->timer_id);

              if($report_status){

                $this->status_message = 'Successfully updated shipper';
                return $this->status_message;

              }else{

                //insert log report not working unknown reason;
                $this->status_message = 'Shipper updated but log report can not be inserted';
                return $this->status_message;

              }

            }else{

              $this->status_message = 'Shipper email updated but rests are not';
              return $this->status_message;
            }


          }else{

            $this->status_message = 'Problem updating shipper. Please try again';
            return $this->status_message;

          }


        }else{
          return $this->timer_id;
      }


    }



    //updating a new user on the database;
    public function update_mawb($mawb_id, $mawb_number, $user_id)
    {

      //calling the timer fetch function;
      $this->timer_id = $this->fetch_time();

      if ($this->user_email_check) {

        //check if email address is not changed;
        $query = "SELECT email FROM login_info WHERE user_id='$this->usering_id'";
        $result = $this->getData($query);
        foreach($result as $key => $res){
          $prev_email = $res['email'];
        }

        if($prev_email != $email_address){

          $this->usering_id = 'User email exist. Please try a different email';
          return $this->usering_id;

        }else{

          return $this->update_query($shipper_id, $shipper_name, $email_address, $contact_number, $country_id, $address, $user_id);


        }


      }else{

        return $this->update_query($shipper_id, $shipper_name, $email_address, $contact_number, $country_id, $address, $user_id);

      }

    }


}


?>
