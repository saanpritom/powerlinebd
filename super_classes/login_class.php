<?php

include_once('super_classes/db_connection.php');
include_once('super_classes/timer_signin.php');

class LoginOperation extends DbConfig
{
    public function __construct()
    {
        parent::__construct();
    }

    public $timer_id, $ipaddress, $hashed_password, $inputed_email, $status_message, $password_check, $fetched_password;

    //creating timing id for login;
    public function fetch_time()
    {

      $timing = new Timer();
      $this->timer_id = $timing->create_timer_id();
      return $this->timer_id;

    }

    // Function to get the client IP address
    public function get_client_ip() {

        $this->ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $this->ipaddress = getenv('HTTP_CLIENT_IP');
        else if(getenv('HTTP_X_FORWARDED_FOR'))
            $this->ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if(getenv('HTTP_X_FORWARDED'))
            $this->ipaddress = getenv('HTTP_X_FORWARDED');
        else if(getenv('HTTP_FORWARDED_FOR'))
            $this->ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if(getenv('HTTP_FORWARDED'))
           $this->ipaddress = getenv('HTTP_FORWARDED');
        else if(getenv('REMOTE_ADDR'))
            $this->ipaddress = getenv('REMOTE_ADDR');
        else
            $this->ipaddress = 'UNKNOWN';
        return $this->ipaddress;

    }

    //check if email exists in database
    public function check_email_exist($get_user_email)
    {

      $query = "SELECT sl_num FROM login_info WHERE email='$get_user_email'";
      $result = $this->connection->query($query);
      if($result->num_rows >= 1) {
        return true;
      }else{
        return false;
      }

    }


    //check if password match or not
    public function password_match($get_user_email, $get_user_password)
    {

      //fetch password from
      $query = "SELECT password FROM login_info WHERE email='$get_user_email'";
      $result = $this->connection->query($query);
      while($row = $result->fetch_assoc())
      {
        $this->fetched_password = $row['password'];
      }

      //check if password matched
      if($this->fetched_password == $get_user_password)
      {
        return true;
      }else{
        return false;
      }

    }


    //main login function;
    public function do_login($user_email, $inputed_password)
    {

      //hash the inputed password
      $options = [
        'cost' => 11
      ];
      $this->hashed_password = password_hash($inputed_password, PASSWORD_BCRYPT, $options);

      //call email check exists function
      $this->inputed_email = $this->check_email_exist($user_email);

      if($this->inputed_email){

        $this->password_check = $this->password_match($this->inputed_email, $this->hashed_password);

        if($this->password_check){

          //collect client ip address;
          $client_ip_address = $this->get_client_ip();

          //create login time;
          $time_id = $this->fetch_time();

          return array($client_ip_address, $time_id);

        }else{
          $this->status_message = 'Password does not exist';
          return $this->status_message;
        }

      }else{

        $this->status_message = 'Email does not exist';
        return $this->status_message;
      }
    }

}



?>
