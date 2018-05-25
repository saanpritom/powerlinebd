<?php

include_once('../../../super_classes/db_connection.php');
include_once('../../../super_classes/timer.php');

class FlightCrudOperation extends DbConfig
{
    public function __construct()
    {
        parent::__construct();
    }

    public $timer_id, $flight_id, $flight_id_check, $status_message;

    //fetching time_id from timer class;
    public function fetch_time()
    {

      $timing = new Timer();
      $this->timer_id = $timing->create_timer_id();
      return $this->timer_id;

    }


    //insert data into log report table;
    public function log_report_insert($user_id, $flight_number, $timing_id)
    {

      $report_data = "$flight_number created by User";

      $query = "INSERT INTO `log_report`(`user_id`, `log_report`, `timer_id`)
                VALUES ('$user_id','$report_data','$timing_id')";
      if($this->connection->query($query))
      {
        return true;
      }else{
        return false;
      }

    }

    //creating a new branch on the database;
    public function create_flight($flight_number, $flight_date, $flight_time, $user_id)
    {

      //calling the timer fetch function;
      $this->timer_id = $this->fetch_time();

      $query = "INSERT INTO `flight_details`(`flight_number`, `flight_date`, `flight_time`, `timer_id`)
                VALUES ('$flight_number','$flight_date','$flight_time','$this->timer_id')";
      if($this->connection->query($query)){

        if($this->log_report_insert($user_id, $flight_number, $this->timer_id))
        {
          $this->status_message = 'Successfully created a new flight';
          return $this->status_message;
        }else{
          $this->status_message = 'Successfully created a new flight but can not create log report';
          return $this->status_message;
        }


      }else{
        $this->status_message = 'Problem creating new flight. Please try again';
        return $this->status_message;
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

    //update branch data
    public function update_branch($b_id, $b_name, $e_name, $c_name, $adrs)
    {

      //calling the timer fetch function;
      $this->timer_id = $this->fetch_time();

      //check if timer_id created properly
      if(is_numeric($this->timer_id)){

        $query = "UPDATE `office_branch` SET `name`='$b_name',
                         `address`='$adrs',`email`='$e_name',
                         `contact_number`='$c_name' WHERE `branch_id`='$b_id'";
        if($this->connection->query($query)){

          $user_login_id = $_SESSION["plbd_id"];

          if($this->log_report_insert($user_login_id, $b_name, $this->timer_id)){

            $this->status_message = 'Branch updated Successfully';
            return $this->status_message;

          }else{

            $this->status_message = 'Problem creating log report but branch updated';
            return $this->status_message;

          }


        }else{
          $this->status_message = 'Problem updating new branch. Please try again';
          return $this->status_message;
        }


      }else{
        return $this->timer_id;
      }



    }


}


?>
