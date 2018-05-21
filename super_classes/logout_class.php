<?php
include_once('super_classes/db_connection.php');
include_once('super_classes/timer_signin.php');

class LogoutOperation extends DbConfig
{
    public function __construct()
    {
        parent::__construct();
    }

    public $user_login_id, $login_status, $timer_id;

    //create a time and date reference for logout;
    public function fetch_time()
    {

      $timing = new Timer();
      $this->timer_id = $timing->create_timer_id();
      return $this->timer_id;

    }

    //reset login status value to zero;
    public function login_status_value()
    {

      $query = "UPDATE `login_info` SET `login_status`='0' WHERE `user_id`='$this->user_login_id'";
      if($result = $this->connection->query($query)){

        return true;

      }else{

        return false;

      }

    }

    //insert data into log table and session destroy;
    public function do_logout()
    {
      session_start();

      $this->user_login_id = $_SESSION["plbd_id"];

      $this->login_status = $this->login_status_value();

      $this->timer_id = $this->fetch_time();

      if($this->login_status)
      {

        $report_data = 'User logged Out';

        $query = "INSERT INTO `log_report`(`user_id`, `log_report`, `timer_id`)
                  VALUES ('$this->user_login_id','$report_data','$this->timer_id')";
        if($this->connection->query($query)){

          $_SESSION["plbd_id"] = 0;

          // remove all session variables
          session_unset();

          // destroy the session
          session_destroy();

          return true;

        }

      }

    }

}



 ?>
