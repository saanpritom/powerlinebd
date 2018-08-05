<?php
include_once('../../../super_classes/db_connection.php');
include_once('../../../super_classes/timer.php');
class ConsigneeCrudOperation extends DbConfig
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
    public function create_consignee($name, $email, $contact_number, $country_id, $address, $shipper_id, $user_id)
    {
      //calling the timer fetch function;
      $this->timer_id = $this->fetch_time();
      //create and check user_id;
      $this->usering_id = mt_rand();
      //call user id check function
      $this->user_id_check = $this->check_user_id($this->usering_id);
      //return $this->user_email_check;
      if($this->user_id_check){
        $this->usering_id = 'User ID overflow. Please try again';
        return $this->usering_id;
      }else{
        //check if timer_id created properly
        if(is_numeric($this->timer_id)){
          //first query inserting data into login table;
          $query = "INSERT INTO `consignee_details`(`consignee_id`, `name`, `email`, `address`, `contact_number`,
                    `country_id`, `timer_id`)
                    VALUES ('$this->usering_id','$name','$email','$address','$contact_number','$country_id',
                            '$this->timer_id')";
          if($this->connection->query($query)){
            $query = "INSERT INTO `consignee_shipper_relation`(`shipper_id`, `consignee_id`, `timer_id`)
                      VALUES ('$shipper_id','$this->usering_id','$this->timer_id')";
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
              $report_data = 'Consignee ' . $name . ' created by User';
              $report_status = $this->log_report_insert($user_id, $report_data, $this->timer_id);
              if($report_status){
                $this->status_message = 'Successfully created a new consignee';
                return $this->status_message;
              }else{
                $this->status_message = 'Successfully created a new consignee but log can not be generated';
                return $this->status_message;
              }
            }else{
              $this->status_message = 'Problem creating new consignee shipper relation. But consignee details created';
              return $this->status_message;
            }
          }else{
            $this->status_message = 'Problem creating new consignee. Please try again';
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
    public function update_query($consignee_id, $consignee_name, $email_address, $contact_number, $country_id, $address, $user_id)
    {
      //check if timer_id created properly
        if(is_numeric($this->timer_id)){
            //second query inserting data into admin details table;
            $query = "UPDATE `consignee_details` SET `name`='$consignee_name',
                      `email`='$email_address',`address`='$address',`contact_number`='$contact_number',
                      `country_id`='$country_id' WHERE `consignee_id`='$consignee_id'";
            if($this->connection->query($query)){
              $report_data = 'Consignee ' . $consignee_name . ' is updated by User';
              //update log record data;
              $report_status = $this->log_report_insert($user_id, $report_data, $this->timer_id);
              if($report_status){
                $this->status_message = 'Successfully updated consignee';
                return $this->status_message;
              }else{
                //insert log report not working unknown reason;
                $this->status_message = 'Consignee updated but log report can not be inserted';
                return $this->status_message;
              }
            }else{
              $this->status_message = 'Problem updating consignee. Please try again later';
              return $this->status_message;
            }
        }else{
          return $this->timer_id;
      }
    }
    //updating a new user on the database;
    public function update_consignee($consignee_id, $consignee_name, $email_address, $contact_number, $country_id, $address, $user_id)
    {
      //calling the timer fetch function;
      $this->timer_id = $this->fetch_time();
      //create and check user_id;
      $this->usering_id = $consignee_id;
      return $this->update_query($this->usering_id, $consignee_name, $email_address, $contact_number, $country_id, $address, $user_id);
    }
    //check if consignee belongs to a shipper;
    public function getShipper($query)
    {
      $result = $this->connection->query($query);
      if($result->num_rows >= 1) {
        return true;
      }else{
        return false;
      }
    }
    //add new shipper;
    public function newShipper(array $shippers, $user_id, $consignee_id)
    {
      //calling the timer fetch function;
      $this->timer_id = $this->fetch_time();
      //fetch Consignee name for log_report;
      $query = "SELECT name FROM consignee_details WHERE consignee_id='$consignee_id'";
      $result = $this->connection->query($query);
      foreach ($result as $key => $res) {
        $consignee_name = $res['name'];
      }
      $counter = 0;
      $existed_shipper_count = 0;


      //check if previously selected shipper is not selected this time;
      $j=0;
      $query = "SELECT shipper_id FROM consignee_shipper_relation WHERE consignee_id='$consignee_id'";
      $result = $this->connection->query($query);
      foreach($result as $key => $res){
        $id_shippers[$j] = $res['shipper_id'];
        $j++;
      }

      //create updated shipper list;
      $j=0;
      $k=0;
      $flag = 1;
      $new_list = [];
      for($i=0; $i<sizeof($id_shippers); $i++){

        for($j=0; $j<sizeof($shippers); $j++){

          if($id_shippers[$i] == $shippers[$j]){

            $flag++;

          }

        }

        if($flag == 1){

          $new_list[$k] = $id_shippers[$i];

          $k++;

        }

        $flag = 1;

      }

      $j = 1;

      //remove the previously selected but currently not selected shippers;
      foreach ($new_list as $key => $value) {

        $query = "DELETE FROM `consignee_shipper_relation` WHERE `shipper_id`='$value' AND `consignee_id`='$consignee_id'";

        $result = $this->connection->query($query);


      }


      for($i=0; $i<sizeof($shippers); $i++){
        $temp_shipper_id = $shippers[$i];
        //check if the shipper is already added to the consignee;
        $mother_query = "SELECT sl_num FROM consignee_shipper_relation WHERE
                      shipper_id='$temp_shipper_id' AND consignee_id='$consignee_id'";
        $mother_result = $this->getShipper($mother_query);
        //if shipper not existed then only inserted;
        if(!$mother_result){
          $query = "INSERT INTO `consignee_shipper_relation`(`shipper_id`, `consignee_id`, `timer_id`)
                    VALUES ('$temp_shipper_id','$consignee_id','$this->timer_id')";
          $result = $this->connection->query($query);
          if($result){
            $counter++;
          }
        }else{
          $existed_shipper_count++;
        }
      }
      //combine new and existed shipper to match all data inserted correctly;
      $counter = $counter + $existed_shipper_count;
      //check if all inserted correctly;
      if($counter == sizeof($shippers)){
          $report_data = 'New Shippers for ' . $consignee_name . ' is added by User';
          //update log record data;
          $report_status = $this->log_report_insert($user_id, $report_data, $this->timer_id);
          if($report_status){
            $this->status_message = 'Successfully added new Shippers';
            return $this->status_message;
          }else{
            $this->status_message = 'New Shippers added but log report cannot inserted';
            return $this->status_message;
          }
      }else{
        $this->status_message = 'Sorry some of the Shippers can not be added. Please try again';
        return $this->status_message;
      }
    }


    public function delete_consignee($consignee_id, $user_id){

      //check consignee has associated data with it
      $query = "SELECT sl_num FROM contact_person_details WHERE parent_organization_id='$consignee_id'";

      $result = $this->connection->query($query);

      if($result->num_rows > 0) {

        $this->status_message = 'You cannot delete this consignee. This consignee has contact details with it. At first change
                                  the contact details with another one then delete this consignee';

        return $this->status_message;

      }else{

        //check if this shipper has any consignee relation
        $query = "SELECT sl_num FROM consignee_shipper_relation WHERE consignee_id='$consignee_id'";

        $result = $this->connection->query($query);

        if($result->num_rows > 0) {

          $this->status_message = 'You cannot delete this consignee. This consignee has shipper relations with it. At first change
                                    the shipper relations with another one then delete this consignee';

          return $this->status_message;

        }else{

          //check if this shipper has any associated awb details with it
          $query = "SELECT sl_num FROM awb_details WHERE consignee_id='$consignee_id'";

          $result = $this->connection->query($query);

          if($result->num_rows > 0) {

            $this->status_message = 'You cannot delete this consignee. This consignee has AWB details with it. At first change
                                      the AWB details with another one then delete this consignee';

            return $this->status_message;

          }else{

            $query = "DELETE FROM `consignee_details` WHERE consignee_id='$consignee_id'";

            if($this->connection->query($query)){


              $report_data = 'Consignee is deleted by User';

              //calling the timer fetch function;
              $this->timer_id = $this->fetch_time();

              //update log record data;
              $report_status = $this->log_report_insert($user_id, $report_data, $this->timer_id);

              if($report_status){

                $this->status_message = 'Consignee deleted Successfully';

                return $this->status_message;

              }else{

                $this->status_message = 'Consignee deleted but log report cannot inserted';

                return $this->status_message;

              }


            }else{

              $this->status_message = 'Problem deleting consignee. Please try again';

              return $this->status_message;

            }

          }

        }

      }

    }
}
?>
