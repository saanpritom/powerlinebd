<?php

include_once('../../../super_classes/db_connection.php');
include_once('../../../super_classes/timer.php');

  class ExcelExporter extends DbConfig{

    public $timer_id;

    //fetching time_id from timer class;
    public function fetch_time()
    {

      $timing = new Timer();
      $this->timer_id = $timing->create_timer_id();
      return $this->timer_id;

    }

    //insert data into log report table;
    public function log_report_insert($user_id, $report_type, $timing_id)
    {

      $report_data = "User downloades $report_type spreadsheet";

      $query = "INSERT INTO `log_report`(`user_id`, `log_report`, `timer_id`)
                VALUES ('$user_id','$report_data','$timing_id')";
      if($this->connection->query($query))
      {
        return true;
      }else{
        return false;
      }

    }


    public function excel_generator($query, $user_id, $report_type)
    {

      $this->timer_id = $this->fetch_time();

      if($this->log_report_insert($user_id, $report_type, $this->timer_id)){

        $result = $this->connection->query($query);

          if ($result == false) {
              return false;
          }

          $rows = array();

          while ($row = $result->fetch_assoc()) {
              $rows[] = $row;
          }

          return $rows;

      }else{
        return false;
      }



    }



  }


 ?>
