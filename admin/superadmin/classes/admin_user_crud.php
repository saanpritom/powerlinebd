<?php

include_once('../../../super_classes/db_connection.php');
include_once('../../../super_classes/timer.php');

class UserCrudOperation extends DbConfig
{
    public function __construct()
    {
        parent::__construct();
    }

    public $timer_id, $usering_id, $user_id_check, $user_email_check, $email_sender, $status_message;

    //fetching time_id from timer class;
    public function fetch_time()
    {

      $timing = new Timer();
      $this->timer_id = $timing->create_timer_id();
      return $this->timer_id;

    }

    //check if the user id is exist on the database;
    public function check_user_id($usering_id)
    {
      $query = "SELECT sl_num FROM login_info WHERE user_id='$this->usering_id'";
      $result = $this->connection->query($query);
      if($result->num_rows >= 1) {
        return true;
      }else{
        return false;
      }
    }

    //check if the user email is exist on the database;
    public function check_user_email($user_email)
    {
      $query = "SELECT sl_num FROM login_info WHERE email='$user_email'";
      $result = $this->connection->query($query);
      if($result->num_rows >= 1) {
        return true;
      }else{
        return false;
      }
    }

    //send user the first email after account creation;
    public function send_account_create_email($u_name, $e_name, $d_pass, $u_role)
    {
      $to = $e_name;
      $subject = "Your New Account Created at Power Line BD Tracker Panel";

      $message = "
      <html>
      <head>
      <title>New Account Created</title>
      </head>
      <body>
      <p>Hello $u_name</p>
      <br/>
      <p>Your new $u_role type account created on the Power Line BD Tracker Panel.
         Now below is your account access credentials.
         <b>Link: </b> http://192.168.64.2/powerlinebd/login <br/>
         <b>Email: </b> $e_name <br/>
         <b>Password: </b> $d_pass <br/>
      </p>
      <b>Please change the password after your first login. Thank you</b>
      <br/>
      <br/>
      <p>Sincerely</p>
      <b>Power Line Bangladesh Admin</b>
      </body>
      </html>
      ";

      // Always set content-type when sending HTML email
      $headers = "MIME-Version: 1.0" . "\r\n";
      $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

      // More headers
      $headers .= 'From: <admin@powerlinebd.com>' . "\r\n";


      if(mail($to,$subject,$message,$headers))
      {
        return true;
      }else{
        return false;
      }

    }

    //creating a new user on the database;
    public function create_user($u_name, $e_name, $c_name, $dnation, $dpment, $gndr, $b_id, $u_role)
    {

      //calling the timer fetch function;
      $this->timer_id = $this->fetch_time();

      //create and check user_id;
      $this->usering_id = mt_rand();

      //create new password for user;
      //$default_password = mt_rand();
      $default_password = '1234';
      //hash the default password;
      $options = [
        'cost' => 11
      ];
      $hash_password = password_hash($default_password, PASSWORD_BCRYPT, $options);

      //call user id check function
      $this->user_id_check = $this->check_user_id($this->usering_id);

      //call user email check function
      $this->user_email_check = $this->check_user_email($e_name);
      //return $this->user_email_check;

      if($this->user_id_check){
        $this->usering_id = 'User ID overflow. Please try again';
        return $this->usering_id;
      }elseif ($this->user_email_check) {
        $this->usering_id = 'User email exist. Please try a different email';
        return $this->usering_id;
      }else{

        //check if timer_id created properly
        if(is_numeric($this->timer_id)){

          //first query inserting data into login table;
          $query = "INSERT INTO `login_info`(`user_id`, `email`, `password`, `rememberme_token`, `user_type`, `login_status`)
                    VALUES ('$this->usering_id','$e_name','$hash_password','0','admin','0');";

          //second query inserting data into admin details table;
          $query .= "INSERT INTO `admin_details`(`admin_id`, `branch_id`, `admin_type`, `name`, `gender`, `designation`,
                    `department`, `contact_number`, `timer_id`)
                    VALUES ('$this->usering_id','$b_id','$u_role','$u_name','$gndr','$dnation',
                    '$dpment','$c_name','$this->timer_id')";

          if($this->connection->multi_query($query)){

            //calling the first email send function
            $this->email_sender = $this->send_account_create_email($u_name, $e_name, $default_password, $u_role);

            if($this->email_sender){
              $this->status_message = 'Successfully created a new user';
              return $this->status_message;
            }else{
              $this->status_message = 'User created but cannot send the email';
              return $this->status_message;
            }


          }else{
            $this->status_message = 'Problem creating new user. Please try again';
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


}


?>
