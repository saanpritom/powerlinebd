<?php

include_once('../../../super_classes/db_connection.php');
include_once('../../../super_classes/timer.php');

class ContactCrudOperation extends DbConfig
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
    public function create_contact($name, $email, $contact_number, $designation, $organization_id, $organization_type, $user_id)
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
        $this->usering_id = 'Contact ID overflow. Please try again';
        return $this->usering_id;
      }elseif ($this->user_email_check) {
        $this->usering_id = 'Contact email exist. Please try a different email';
        return $this->usering_id;
      }else{

        //check if timer_id created properly
        if(is_numeric($this->timer_id)){

          //first query inserting data into login table;
          $query = "INSERT INTO `login_info`(`user_id`, `email`, `password`, `rememberme_token`, `user_type`, `login_status`)
                    VALUES ('$this->usering_id','$email','$hash_password','0','contact_person','0')";

          if($this->connection->query($query)){


            $query = "INSERT INTO `contact_person_details`(`contact_id`, `person_from`, `parent_organization_id`, `name`,
                      `designation`, `contact_number`, `timer_id`)
                      VALUES ('$this->usering_id','$organization_type','$organization_id','$name',
                        '$designation','$contact_number','$this->timer_id')";

            if($this->connection->query($query)){

                $report_data = 'Contact ' . $name . ' created by User';

                $report_status = $this->log_report_insert($user_id, $report_data, $this->timer_id);

                if($report_status){

                  $this->status_message = 'Successfully created a new contact';
                  return $this->status_message;

                }else{

                  $this->status_message = 'Successfully created a new contact but log can not be generated';
                  return $this->status_message;

                }

            }else{

              $this->status_message = 'Contact email created but details can not be created. Please try again';
              return $this->status_message;

            }


          }else{

            $this->status_message = 'Can not create contact. Please try again';
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


    public function update_query($name, $email, $contact_number, $designation, $organization_id, $contact_id, $user_id)
    {

      //check if timer_id created properly
        if(is_numeric($this->timer_id)){

          //first query inserting data into login table;
          $query = "UPDATE `login_info` SET `email`='$email' WHERE `user_id`='$this->usering_id'";

          if($this->connection->query($query)){

            $query = "UPDATE `contact_person_details` SET `parent_organization_id`='$organization_id',
                      `name`='$name',`designation`='$designation',`contact_number`='$contact_number' WHERE
                      `contact_id`='$this->usering_id'";

            if($this->connection->query($query)){

              $report_data = 'Contact ' . $name . ' is updated by User';

              //update log record data;
              $report_status = $this->log_report_insert($user_id, $report_data, $this->timer_id);

              if($report_status){

                $this->status_message = 'Successfully updated contact';
                return $this->status_message;

              }else{

                //insert log report not working unknown reason;
                $this->status_message = 'Contact updated but log report can not be inserted';
                return $this->status_message;

              }


            }else{

              $this->status_message = 'Contact email is updated but details are not updated. Please try again';
              return $this->status_message;

            }


          }else{

            $this->status_message = 'Contact is not updated. Please try again';
            return $this->status_message;

          }

        }else{
          return $this->timer_id;
      }


    }


    //updating a new user on the database;
    public function update_contact($name, $email, $contact_number, $designation, $organization_id, $contact_id, $user_id)
    {

      //calling the timer fetch function;
      $this->timer_id = $this->fetch_time();

      //create and check user_id;
      $this->usering_id = $contact_id;


      //call user email check function
      $this->user_email_check = $this->check_user_email($email);
      //return $this->user_email_check;


      if ($this->user_email_check) {

        //check if email address is not changed;
        $query = "SELECT email FROM login_info WHERE user_id='$this->usering_id'";
        $result = $this->getData($query);
        foreach($result as $key => $res){
          $prev_email = $res['email'];
        }

        if($prev_email != $email){

          $this->usering_id = 'User email exist. Please try a different email';
          return $this->usering_id;

        }else{

          return $this->update_query($name, $email, $contact_number, $designation, $organization_id, $contact_id, $user_id);


        }


      }else{

        return $this->update_query($name, $email, $contact_number, $designation, $organization_id, $contact_id, $user_id);

      }

    }







}


?>
