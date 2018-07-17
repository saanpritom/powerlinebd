<?php

include_once('../../../super_classes/db_connection.php');
include_once('../../../super_classes/timer.php');

class AWBCrudOperation extends DbConfig
{
    public function __construct()
    {
        parent::__construct();
    }

    public $timer_id, $status_message;

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
    public function check_awb_number($awb_number)
    {
      $query = "SELECT sl_num FROM awb_details WHERE awb_number='$awb_number'";
      $result = $this->connection->query($query);
      if($result->num_rows >= 1) {
        return true;
      }else{
        return false;
      }
    }

    //fetch consignee id FROM consignee name;
    public function collect_consignee_id($consignee_name){

      //spliting name and country short name;
      $country_short_name = end(explode('-', $consignee_name));

      $consignee_name =  current(explode('-', $consignee_name));

      //getting the consignee id;
      $query = "SELECT consignee_details.consignee_id FROM consignee_details
                INNER JOIN origin_destination_details ON consignee_details.country_id=origin_destination_details.o_id_id
                WHERE consignee_details.consignee_name='$consignee_name' AND origin_destination_details.short_form='$country_short_name'";

      $result = $this->connection->query($query);

      //collect the consignee_id
      while ($row = $result->fetch_assoc()) {

          $consignee_id = $row['consignee_id'];

      }

      //send true or false signal for consignee_id
      if($result->num_rows >= 1) {

        return $consignee_id;

      }else{

        return false;

      }
    }


    //check destination
    public function check_destination($designation){

      //getting the consignee id;
      $query = "SELECT `o_id_id` FROM `origin_destination_details` WHERE
                full_name='$destination' AND type='destination'";

      $result = $this->connection->query($query);

      //collect the consignee_id
      while ($row = $result->fetch_assoc()) {

          $destination_id = $row['o_id_id'];

      }

      //send true or false signal for consignee_id
      if($result->num_rows >= 1) {

        return $destination_id;

      }else{

        return false;

      }
    }



    //check if newly creating consignee id already exists or not;
    public function check_consignee_id($new_consignee_id){

      $query = "SELECT sl_num FROM consignee_details WHERE consignee_id='$new_consignee_id'";
      $result = $this->connection->query($query);
      if($result->num_rows >= 1) {
        return true;
      }else{
        return false;


    }


    //check if newly creating consignee id already exists or not;
    public function check_destination_id($new_destination_id){

      $query = "SELECT sl_num FROM origin_destination_details WHERE o_id_id='$new_destination_id'";
      $result = $this->connection->query($query);
      if($result->num_rows >= 1) {
        return true;
      }else{
        return false;


    }


    //creating a new user on the database;
    public function create_mawb($awb_number, $shipper_id, $consignee_name, $destination, $bag_number, $type, $pcs, $a_weight, $b_weight, $price_value, $user_id)
    {

      //calling the timer fetch function;
      $this->timer_id = $this->fetch_time();

      //check mawb number uniqueness
      $this->awb_number_check = $this->check_awb_number($awb_number);

      //collect consignee_id FROM consignee_name
      $consignee_id = $this->collect_consignee_id($consignee_name);

      if($this->awb_number_check){
        $this->usering_id = 'AWB number already exists. Please try a different one';
        return $this->usering_id;
      }else{

        //check if timer_id created properly
        if(is_numeric($this->timer_id)){


          //check if consignee is new or old. If new then a new consignee will be created;
          if(!$consignee_id){

            //generate new consignee id
            $new_consignee_id = mt_rand();

            //check consignee id already exists
            $consignee_id_check = $this->check_consignee_id($new_consignee_id);

            if($consignee_id_check){

              $this->usering_id = 'Newly creating consignee ID overflow. Please try again';
              return $this->usering_id;

            }else{

              //check if destination is already exists or not;
              $destination_check = $this->check_destination($destination);

              //create destination if not found;
              if(!$destination_check){

                //generate new destination ID;
                $new_destination_id = mt_rand();

                //check destination id already exists
                $destination_id_check = $this->check_destination_id($new_destination_id);

                if($destination_id_check){

                  //generate new destination ID;
                  $new_destination_id = mt_rand() * mt_rand();

                }

                $query = "INSERT INTO `origin_destination_details`(`o_id_id`, `full_name`, `short_form`, `type`) VALUES ('$new_destination_id',
                          '$destination', 'N/A', 'destination')";

                $this->connection->query($query);

              }


              //insert a new consignee into consignee_details
              $query = "INSERT INTO `consignee_details`(`consignee_id`, `name`, `email`, `address`, `contact_number`,
                        `country_id`) VALUES ('$new_consignee_id', $consignee_name, 'no@email.com',
                        'No Address', 'No Contact Number', '0')";

              if($this->connection->query($query)){

                //insert data into AWB details table
                $query = "INSERT INTO `awb_details`(`awb_id`, `shipper_id`, `consignee_id`, `destination_id`, `bag_number`,
                          `type`, `pcs`, `a.weight`, `b.weight`, `value`, `timer_id`)
                          VALUES ('$awb_number','$shipper_id','$new_consignee_id','$new_destination_id','$bag_number',
                          '$type','$pcs','$a_weight',
                          '$b_weight','$value','$this->timer_id')";


              }else{

                $this->usering_id = 'Problem creating new consignee. Please try again';
                return $this->usering_id;

              }



            }


          }

          //first query inserting data into login table;
          $query = "INSERT INTO `mawb_details`(`mawb_id`, `mawb_number`, `timer_id`)
                    VALUES ('$mawb_id', '$mawb_number', '$this->timer_id')";

          if($this->connection->query($query)){

            $report_data = 'MAWB ' . $mawb_number . ' created by User';

            $report_status = $this->log_report_insert($user_id, $report_data, $this->timer_id);

            if($report_status){

              $this->status_message = 'Successfully created a new MAWB';
              return $this->status_message;

            }else{

              $this->status_message = 'Successfully created a new MAWB but log can not be generated';
              return $this->status_message;

            }


          }else{
            $this->status_message = 'Problem creating new MAWB. Please try again';
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


    //updating a new user on the database;
    public function update_mawb($mawb_id, $mawb_number, $user_id)
    {

      //calling the timer fetch function;
      $this->timer_id = $this->fetch_time();

      $this->mawb_number_check = $this->check_mawb_number($mawb_number);

      if($this->mawb_number_check){

        $this->usering_id = 'MAWB number already exists. Please try a different one';

        return $this->usering_id;

      }else{

        if(is_numeric($this->timer_id)){

          $query = "UPDATE `mawb_details` SET `mawb_number`='$mawb_number' WHERE `mawb_id`='$mawb_id'";

          if($this->connection->query($query)){

            $report_data = 'MAWB ' . $mawb_number . ' is updated by User';

            //update log record data;
            $report_status = $this->log_report_insert($user_id, $report_data, $this->timer_id);

            if($report_status){

              $this->status_message = 'MAWB updated Successfully';
              return $this->status_message;

            }else{

              //insert log report not working unknown reason;
              $this->status_message = 'MAWB updated but log report can not be inserted';
              return $this->status_message;

            }

          }else{

            return 'Some problem happened. Please try again later.';

          }


        }else{
          return $this->timer_id;
        }

      }

    }


}


?>
