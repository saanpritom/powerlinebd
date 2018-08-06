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

    //insert data into log report table;
    public function log_report_insert($user_login_id, $report_data)
    {


      $query = "INSERT INTO `log_report`(`user_id`, `log_report`, `timer_id`)
                VALUES ('$user_login_id', '$report_data', '$this->timer_id')";
      if($this->connection->query($query))
      {
        return true;
      }else{
        return false;
      }

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
      $default_password = mt_rand();

      //hash the default password;
      $hash_password = hash('sha256', $default_password);

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



    public function update_query($u_name, $e_name, $c_name, $dnation, $dpment, $gndr, $b_id, $u_role, $u_id)
    {

      //check if timer_id created properly
        if(is_numeric($this->timer_id)){

          //first query inserting data into login table;
          $query = "UPDATE `login_info` SET `email`='$e_name' WHERE `user_id`='$this->usering_id';";

          //second query inserting data into admin details table;
          $query .= "UPDATE `admin_details` SET `branch_id`='$b_id', `admin_type`='$u_role', `name`='$u_name', `gender`='$gndr', `designation`='$dnation',
                    `department`='$dpment', `contact_number`='$c_name' WHERE `admin_id`='$this->usering_id';";

          if($this->connection->multi_query($query)){

            //fetch the login user id;
            $user_login_id = $_SESSION["plbd_id"];

            $report_data = $u_name . ' is updated by User';

            //update log record data;
            $report_status = $this->log_report_insert($user_login_id, $report_data);

            if($report_status){

              $this->status_message = 'Successfully updated a new user';
              return $this->status_message;

            }else{

              //insert log report not working unknown reason;
              $this->status_message = 'Successfully updated a new user';
              return $this->status_message;

            }



          }else{
            $this->status_message = 'Problem updating new user. Please try again';
            return $this->status_message;
          }


        }else{
          return $this->timer_id;
      }


    }



    //updating a new user on the database;
    public function update_user($u_name, $e_name, $c_name, $dnation, $dpment, $gndr, $b_id, $u_role, $u_id)
    {

      //calling the timer fetch function;
      $this->timer_id = $this->fetch_time();

      //create and check user_id;
      $this->usering_id = $u_id;


      //call user email check function
      $this->user_email_check = $this->check_user_email($e_name);
      //return $this->user_email_check;

      if ($this->user_email_check) {

        //check if email address is not changed;
        $query = "SELECT email FROM login_info WHERE user_id='$this->usering_id'";
        $result = $this->getData($query);
        foreach($result as $key => $res){
          $prev_email = $res['email'];
        }

        if($prev_email != $e_name){

          $this->usering_id = 'User email exist. Please try a different email';
          return $this->usering_id;

        }else{

          return $this->update_query($u_name, $e_name, $c_name, $dnation, $dpment, $gndr, $b_id, $u_role, $u_id);


        }


      }else{

        return $this->update_query($u_name, $e_name, $c_name, $dnation, $dpment, $gndr, $b_id, $u_role, $u_id);

      }

    }


    public function delete_user($u_id, $user_id){

      //check if this one is the current user
      if($u_id != $user_id){


              //check if this user has awb details with it
              $query = "SELECT sl_num FROM awb_details WHERE user_id='$u_id'";

              $result = $this->connection->query($query);

              if($result->num_rows > 0) {

                $this->status_message = 'You cannot delete this user. This user has AWB associated with it. Please delete or
                                          change the AWB then delete this user';

                return $this->status_message;

              }else{

                //check awb status for this user
                $query = "SELECT sl_num FROM awb_status WHERE user_id='$u_id'";

                $result = $this->connection->query($query);

                if($result->num_rows > 0) {

                  $this->status_message = 'You cannot delete this user. This user has multiple AWB status. You have to change them
                                            first then try again.';

                  return $this->status_message;

                }else{

                  //delete login information of this user
                  $query = "DELETE FROM `login_info` WHERE user_id='$u_id'";

                  if($this->connection->query($query)){

                    //delete user detail information

                    $query = "DELETE FROM `admin_details` WHERE admin_id='$u_id'";

                    if($this->connection->query($query)){

                      $report_data = 'An user is deleted by User';

                      //calling the timer fetch function;
                      $this->timer_id = $this->fetch_time();

                      //update log record data;
                      $report_status = $this->log_report_insert($user_id, $report_data, $this->timer_id);

                      if($report_status){

                        $this->status_message = 'User deleted Successfully';
                        return $this->status_message;

                      }else{

                        //insert log report not working unknown reason;
                        $this->status_message = 'User deleted but log report can not be inserted';
                        return $this->status_message;

                      }

                    }else{

                      $this->status_message = 'Problem deleting users detail information. Please try again';

                      return $this->status_message;

                    }

                  }else{

                    $this->status_message = 'Problem deleting users login information. Please try again';

                    return $this->status_message;

                  }

                }

              }

      }else{

        $this->status_message = 'You cannot delete yourself. Donot try this';

        return $this->status_message;

      }


    }


    public function update_password($password, $user_id, $usn_id){

      //check the charcter limit of the password
      if(strlen($password) >= 8){

        //hash the default password;
        $hash_password = hash('sha256', $password);

        //update password to table
        $query = "UPDATE `login_info` SET `password`='$hash_password', `login_status`='0' WHERE user_id='$user_id'";

        if($this->connection->query($query)){

          $report_data = 'Password changed of ' . $user_id . ' by Super Admin' . $usn_id;

          //calling the timer fetch function;
          $this->timer_id = $this->fetch_time();

          //update log record data;
          $report_status = $this->log_report_insert($usn_id, $report_data, $this->timer_id);

          if($report_status){

            $this->status_message = 'Successfully changed password';
            return $this->status_message;

          }else{

            //insert log report not working unknown reason;
            $this->status_message = 'Password changed but log report can not be inserted';
            return $this->status_message;

          }

        }else{

          $this->status_message = 'Problem updating password. Please try again';

          return $this->status_message;

        }


      }else{

        $this->status_message = 'Password must contain 8 characters';

        return $this->status_message;

      }

    }


}


?>
