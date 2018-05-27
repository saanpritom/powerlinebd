<?php

include_once('../../../super_classes/db_connection.php');
include_once('../../../super_classes/timer.php');

class PlaceCrudOperation extends DbConfig
{
    public function __construct()
    {
        parent::__construct();
    }

    public $timer_id, $place_id, $place_id_check, $status_message;

    //fetching time_id from timer class;
    public function fetch_time()
    {

      $timing = new Timer();
      $this->timer_id = $timing->create_timer_id();
      return $this->timer_id;

    }

    //check if the branch id is exist on the database;
    public function check_place_id($place_id)
    {
      $query = "SELECT sl_num FROM origin_destination_details WHERE o_d_id='$this->place_id'";
      $result = $this->connection->query($query);

      if($result){
        if($result->num_rows > 0) {
          return true;
        }else{
          return false;
        }
      }

    }

    //check if short form is existed or not;
    public function check_short_form($short_form, $place_type)
    {
      $query = "SELECT sl_num FROM origin_destination_details WHERE short_form='$short_form' AND `type`='$place_type'";
      $result = $this->connection->query($query);
      if($result->num_rows > 0) {
        return true;
      }else{
        return false;
      }
    }

    //insert data into log report table;
    public function log_report_insert($user_id, $report_data, $timing_id)
    {

      //$report_data = "$branch_name updated by User";

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
    public function create_place($full_name, $short_form, $place_type, $user_id)
    {

      //calling the timer fetch function;
      $this->timer_id = $this->fetch_time();

      //create and check branch_id;
      $this->place_id = mt_rand();

      $this->place_id_check = $this->check_place_id($this->place_id);

      $short_form_check = $this->check_short_form($short_form, $place_type);

      if($this->place_id_check){
        $this->status_message = 'Place ID overflow. Please try again';
        return $this->status_message;
      }elseif($short_form_check){
        $this->status_message = 'Short form exists. Please use another';
        return $this->status_message;
      }else{

        //check if timer_id created properly
        if(is_numeric($this->timer_id)){

          $query = "INSERT INTO `origin_destination_details`(`o_id_id`, `full_name`, `short_form`, `type`, `timer_id`)
                    VALUES ('$this->place_id','$full_name','$short_form','$place_type','$this->timer_id')";
          if($this->connection->query($query)){

            $report_data = 'Place ' . $full_name . ' created by User';

            $report_status = $this->log_report_insert($user_id, $report_data, $this->timer_id);

            if($report_status){

              $this->status_message = 'Successfully created a new place';
              return $this->status_message;

            }else{

              $this->status_message = 'Successfully created a new place but log report can not be created';
              return $this->status_message;

            }


          }else{
            $this->status_message = 'Problem creating new place. Please try again';
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

    //update branch data
    public function update_place($place_id, $full_name, $short_form, $place_type, $user_id)
    {

      //calling the timer fetch function;
      $this->timer_id = $this->fetch_time();

      //check if short form existed;
      $short_form_check = $this->check_short_form($short_form, $place_type);

      if(!$short_form_check){

        //check if timer_id created properly
        if(is_numeric($this->timer_id)){

          $query = "UPDATE `origin_destination_details` SET
                    `full_name`='$full_name',
                    `short_form`='$short_form',
                    `type`='$place_type'
                     WHERE `o_id_id`='$place_id'";

          if($this->connection->query($query)){

            $report_data = 'Place ' . $full_name . ' updated by User';

            if($this->log_report_insert($user_id, $report_data, $this->timer_id)){

              $this->status_message = 'Place updated Successfully';
              return $this->status_message;

            }else{

              $this->status_message = 'Problem creating log report but place updated';
              return $this->status_message;

            }


          }else{
            $this->status_message = 'Problem updating new place. Please try again';
            return $this->status_message;
          }


        }else{
          return $this->timer_id;
        }

      }else{

        $this->status_message = 'Short form exist. Please try again';
        return $this->status_message;

      }





    }


}


?>
