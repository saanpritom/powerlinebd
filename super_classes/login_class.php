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
    public $user_login_id, $user_type;

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
      $query = "SELECT user_id, password, user_type FROM login_info WHERE email='$get_user_email'";
      $result = $this->connection->query($query);
      while($row = $result->fetch_assoc())
      {
        $this->fetched_password = $row['password'];
        $this->user_login_id = $row['user_id'];
        $this->user_type = $row['user_type'];
      }

      //check if password matched
      if($this->fetched_password == $get_user_password)
      {
        return true;
      }else{
        return false;
      }

    }

    //insert data into login_log table;
    public function login_log_insert($insert_client_ip, $insert_time_id)
    {

      $query = "INSERT INTO `login_log`(`user_id`, `public_ip`, `timer_id`)
                VALUES ('$this->user_login_id','$insert_client_ip','$insert_time_id')";
      if($this->connection->query($query))
      {
        return true;
      }else{
        return false;
      }

    }

    //insert data into log report table;
    public function log_report_insert($time_id)
    {

      $report_data = 'User logged In';

      $query = "INSERT INTO `log_report`(`user_id`, `log_report`, `timer_id`)
                VALUES ('$this->user_login_id','$report_data','$time_id')";
      if($this->connection->query($query))
      {
        return true;
      }else{
        return false;
      }

    }

    //fetch admin type from admin table;
    public function fetch_admin_type()
    {

      $admin_type_data = '0';

      $query = "SELECT `admin_type` FROM `admin_details` WHERE `admin_id`='$this->user_login_id'";
      $result = $this->connection->query($query);
      while($row = $result->fetch_assoc())
      {
        $admin_type_data = $row['admin_type'];
      }

      return $admin_type_data;

    }

    //main login function;
    public function do_login($user_email, $inputed_password, $is_remember)
    {

      //hash the inputed password
      $this->hashed_password = hash('sha256', $inputed_password);

      //call email check exists function
      $this->inputed_email = $this->check_email_exist($user_email);

      if($this->inputed_email){

        $this->password_check = $this->password_match($user_email, $this->hashed_password);

        //return $this->password_check;

        if($this->password_check){

          //collect client ip address;
          $client_ip_address = $this->get_client_ip();

          //create login time;
          $time_id = $this->fetch_time();


          //call login log function to keep login data
          $login_log_insertion = $this->login_log_insert($client_ip_address, $time_id);

          //call login info function to keep log data
          $log_info_insertion = $this->log_report_insert($time_id);

          if($log_info_insertion and $login_log_insertion){


            //if admin then call the admin type function;
            if($this->user_type == 'admin'){

              $admin_type = $this->fetch_admin_type();

              //set log in field on login table to 1 as user logged in;
              $query = "UPDATE `login_info` SET `login_status`='1' WHERE `user_id`='$this->user_login_id'";
              if($result = $this->connection->query($query)){

                //set user session;
                session_start();
                $_SESSION["plbd_id"] = $this->user_login_id;

                //remember me email and password;
                if(!empty($is_remember)){

                  //setting email and password as cookie for next time;
                  setcookie ("member_email",$user_email,time()+ (10 * 365 * 24 * 60 * 60));
    				      setcookie ("member_password",$inputed_password,time()+ (10 * 365 * 24 * 60 * 60));

                }

                //redirect the particular app of particular user;
                if($admin_type == 'super_admin'){

                  header('Location: admin/superadmin/home/dashboard', true, 302);
                  exit;

                }else if($admin_type == 'finance_user'){

                  header('Location: admin/finance/home/dashboard', true, 302);
                  exit;

                }else if($admin_type == 'business_user'){

                  header('Location: admin/business/home/dashboard', true, 302);
                  exit;

                }else{

                  $this->status_message = 'Probably you are in hack mode. Do not try it';
                  return $this->status_message;

                }


              }else{

                $this->status_message = 'Somebody went wrong. Can not login. Please try again';
                return $this->status_message;

              }



            }else{

              $this->status_message = 'This type of users are not currently allowed';
              return $this->status_message;

            }


          }else{

            $this->status_message = 'Something went wrong. Can not login. Please try again';
            return $this->status_message;

          }



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
