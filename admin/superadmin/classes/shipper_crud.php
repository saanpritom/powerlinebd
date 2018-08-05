<?php

include_once('../../../super_classes/db_connection.php');
include_once('../../../super_classes/timer.php');

class ShipperCrudOperation extends DbConfig
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
    public function create_shipper($name, $email, $contact_number, $country_id, $address, $user_id)
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
      $this->user_email_check = $this->check_user_email($email);
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
                    VALUES ('$this->usering_id','$email','$hash_password','0','shipper','0')";

          if($this->connection->query($query)){

            $query = "INSERT INTO `shipper_details`(`shipper_id`, `name`, `country_id`, `address`, `contact_number`, `timer_id`)
                      VALUES ('$this->usering_id','$name', '$country_id','$address','$contact_number','$this->timer_id')";


            if($this->connection->query($query)){

              //calling the first email send function but for now it is off;
              /*$this->email_sender = $this->send_account_create_email($u_name, $e_name, $default_password, $u_role);

              if($this->email_sender){
                $this->status_message = 'Successfully created a new user';
                return $this->status_message;
              }else{
                $this->status_message = 'User created but cannot send the email';
                return $this->status_message;
              }*/

              $report_data = 'Shipper ' . $name . ' created by User';

              $report_status = $this->log_report_insert($user_id, $report_data, $this->timer_id);

              if($report_status){

                $this->status_message = 'Successfully created a new shipper';
                return $this->status_message;

              }else{

                $this->status_message = 'Successfully created a new shipper but log can not be generated';
                return $this->status_message;

              }


            }else{
              $this->status_message = 'Problem creating new user details. But user email created';
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
    public function update_shipper($shipper_id, $shipper_name, $email_address, $contact_number, $country_id, $address, $user_id)
    {

      //calling the timer fetch function;
      $this->timer_id = $this->fetch_time();

      //create and check user_id;
      $this->usering_id = $shipper_id;


      //call user email check function
      $this->user_email_check = $this->check_user_email($email_address);
      //return $this->user_email_check;

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


    public function delete_shipper($shipper_id, $user_id){

      //check shipper has associated data with it
      $query = "SELECT sl_num FROM contact_person_details WHERE parent_organization_id='$shipper_id'";

      $result = $this->connection->query($query);

      if($result->num_rows > 0) {

        $this->status_message = 'You cannot delete this shipper. This shipper has contact details with it. At first change
                                  the contact details with another one then delete this shipper';

        return $this->status_message;

      }else{

        //check if this shipper has any consignee relation
        $query = "SELECT sl_num FROM consignee_shipper_relation WHERE shipper_id='$shipper_id'";

        $result = $this->connection->query($query);

        if($result->num_rows > 0) {

          $this->status_message = 'You cannot delete this shipper. This shipper has consignee relations with it. At first change
                                    the consignee relations with another one then delete this shipper';

          return $this->status_message;

        }else{

          //check if this shipper has any associated awb details with it
          $query = "SELECT sl_num FROM awb_details WHERE shipper_id='$shipper_id'";

          $result = $this->connection->query($query);

          if($result->num_rows > 0) {

            $this->status_message = 'You cannot delete this shipper. This shipper has AWB details with it. At first change
                                      the AWB details with another one then delete this shipper';

            return $this->status_message;

          }else{

            //delete Shipper from shipper details
            $query = "DELETE FROM `shipper_details` WHERE shipper_id='$shipper_id'";

            if($this->connection->query($query)){

              $report_data = 'Shipper is deleted by User';

              //calling the timer fetch function;
              $this->timer_id = $this->fetch_time();

              //update log record data;
              $report_status = $this->log_report_insert($user_id, $report_data, $this->timer_id);

              if($report_status){

                $this->status_message = 'Shipper deleted Successfully';
                return $this->status_message;

              }else{

                //insert log report not working unknown reason;
                $this->status_message = 'Shipper deleted but log report can not be inserted';
                return $this->status_message;

              }

            }else{

              $this->status_message = 'Problem deleting shipper details. Please try again';

              return $this->status_message;

            }

          }

        }

      }


    }


}


?>
