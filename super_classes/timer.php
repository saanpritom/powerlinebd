<?php

include_once('../../../super_classes/db_connection.php');

class Timer extends DbConfig
{
    public function __construct()
    {
        parent::__construct();
      }

    public $time_id, $id_check, $current_date, $current_time, $check_insert;


    //check if the newly generated time_id is already exists on the table;
    public function check_timer_id($time_id)
    {
      $query = "SELECT sl_num FROM creation_details WHERE timer_id='$this->time_id'";
      $result = $this->connection->query($query);
      if($result->num_rows == 0) {
        return true;
      }else{
        return false;
      }
    }

    //capture current date of the system;
    public function create_date(){
      $this->current_date = date("Y/m/d");
      return $this->current_date;
    }

    //capture current time of the system;
    public function create_time(){
      date_default_timezone_set("Asia/Dhaka");
      $this->current_time = date("h:i:sa");
      return $this->current_time;
    }

    //insert date and time into database;
    public function insert_data(){
      $query = "INSERT INTO creation_details (timer_id, creation_date, creation_time)
                VALUES ('$this->time_id', '$this->current_date', '$this->current_time')";
      if($this->connection->query($query)){
        return true;
      }else{
        return false;
      }
    }


    //creating timer id;
    public function create_timer_id()
    {

        //generating random number;
        $this->time_id = mt_rand();

        //call the id checking function;
        $id_check = $this->check_timer_id($this->time_id);

        //If true then error will be shown;
        if($this->id_check){
          $this->time_id = 'Timer ID Overflow. Please try again';
          return $this->time_id;
        }else{

          //call date creating function;
          $current_date = $this->create_date();

          //call time capture function;
          $current_time = $this->create_time();

          //call the time insert function;
          $this->check_insert = $this->insert_data();

          //return created time_id;
          if($this->check_insert){
            return $this->time_id;
          }else{
            $this->time_id = 'Problem creating new time. Please try again';
            return $this->time_id;
          }

          //return $this->time_id;
          //return array('time_id' => $this->time_id, 'current_date' => $this->current_date, '$current_time' => $this->current_time);
        }
    }

}

?>
