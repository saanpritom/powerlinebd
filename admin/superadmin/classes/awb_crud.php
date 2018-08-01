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
      $query = "SELECT sl_num FROM awb_details WHERE awb_id='$awb_number'";
      $result = $this->connection->query($query);
      if($result->num_rows >= 1) {
        return true;
      }else{
        return false;
      }
    }


    public function get_consignee_id($consignee_name){

      //check if the string contains -
      if (strpos($consignee_name, '-') !== false) {

        $country_short_name = explode('-', $consignee_name);

        $country_short_name = end($country_short_name);

        $c_name =  current(explode('-', $consignee_name));

        //getting the consignee id;
        $query = "SELECT consignee_details.consignee_id FROM consignee_details
                  INNER JOIN origin_destination_details ON consignee_details.country_id=origin_destination_details.o_id_id
                  WHERE consignee_details.name='$c_name' AND origin_destination_details.short_form='$country_short_name'";

        $result = $this->getData($query);

        foreach ($result as $key => $res)
        {
          $consignee_id = $res['consignee_id'];
        }

        return $consignee_id;

      }else{

        return 'new consignee';

      }



    }

    //creating a new user on the database;
    public function create_awb($awb_number, $shipper_id, $consignee_name, $destination, $bag_number, $type, $pcs, $a_weight, $b_weight, $price_value, $user_id)
    {

      //calling the timer fetch function;
      $this->timer_id = $this->fetch_time();

      //check mawb number uniqueness
      $this->awb_number_check = $this->check_awb_number($awb_number);

      //check destination is null or not;
      if($destination==''){
        $destination = 0;
      }else{
        $destination = $destination;
      }

      if($this->awb_number_check){
        $this->usering_id = 'AWB number already exists. Please try a different one';
        return $this->usering_id;
      }else{

        //check if timer_id created properly
        if(is_numeric($this->timer_id)){

          //determine new or existing consignee;
          $check_consignee = $this->get_consignee_id($consignee_name);

          if($check_consignee == 'new consignee'){

            $consignee_name = $consignee_name;

          }else{

            $consignee_name = $check_consignee;

            //update consignee shipper relation table;
            $query = "INSERT INTO `consignee_shipper_relation`(`shipper_id`, `consignee_id`, `timer_id`)
                      VALUES ('$shipper_id', '$consignee_name', '$this->timer_id')";

            $this->connection->query($query);

          }

          //first query inserting data into login table;
          $query ="INSERT INTO `awb_details`(`awb_id`, `shipper_id`, `consignee_id`, `destination_id`,
                   `bag_number`, `type`, `pcs`, `a.weight`, `b.weight`, `value`, `timer_id`, `user_id`)
                   VALUES ('$awb_number', '$shipper_id', '$consignee_name', '$destination', '$bag_number',
                           '$type', '$pcs', '$a_weight', '$b_weight', '$price_value', '$this->timer_id', '$user_id')";

          if($this->connection->query($query)){

            //insert into awb status table;
            $query = "INSERT INTO `awb_lock`(`awb_id`, `lock_status`, `operational_awb`)
                      VALUES ('$awb_number', 'unlocked', '1')";

            if($this->connection->query($query)){

              //insert a basic delivery status into awb_status
              $query = "INSERT INTO `awb_status`(`awb_id`, `user_id`, `timer_id`, `delivery_status`, `status_active`)
                        VALUES ('$awb_number', '$user_id', '$this->timer_id', 'Created', '1')";

              if($this->connection->query($query)){

                $report_data = 'AWB ' . $awb_number . ' created by User';

                $report_status = $this->log_report_insert($user_id, $report_data, $this->timer_id);

                if($report_status){

                  $this->status_message = 'Successfully created a new AWB';
                  return $this->status_message;

                }else{

                  $this->status_message = 'Successfully created a new AWB but log can not be generated';
                  return $this->status_message;

                }


              }else{

                $this->status_message = 'Successfully created a new AWB but initial delivery status can not be generated';
                return $this->status_message;


              }


            }else{

              $this->status_message = 'Successfully created a new AWB but status can not be created';
              return $this->status_message;


            }




          }else{
            $this->status_message = 'Problem creating new AWB. Please try again';
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
    public function update_awb($awb_number, $shipper_id, $consignee_name, $destination, $bag_number, $type, $pcs, $a_weight, $b_weight, $price_value, $user_id)
    {

      //fetch sl_num by awb_id
      $query = "SELECT sl_num FROM awb_details WHERE awb_id='$awb_number'";

      $get_sl = $this->getData($query);

      foreach ($get_sl as $key => $res){

        $sl_num = $res['sl_num'];

      }

      //calling the timer fetch function;
      $this->timer_id = $this->fetch_time();



        //determine new or existing consignee;
        $check_consignee = $this->get_consignee_id($consignee_name);

        //if check consignee numeric then need to check shipper consignee relation table;
        if(is_numeric($check_consignee)){

          //check if this consignee already belongs to the shipper
          $query = "SELECT `sl_num` FROM `consignee_shipper_relation` WHERE shipper_id='$shipper_id' AND consignee_id='$check_consignee'";

          $result = $this->connection->query($query);

          if($result->num_rows < 1) {

            //create a new relation between this shipper and consignee;

            $query = "INSERT INTO `consignee_shipper_relation`(`shipper_id`, `consignee_id`, `timer_id`)
                      VALUES ('$shipper_id', '$check_consignee', '$this->timer_id')";

            $this->connection->query($query);

          }

          $destination = '0';

        }else{

          $check_consignee = $consignee_name;

        }

        //update awb details table

        $query = "UPDATE `awb_details` SET `awb_id`='$awb_number',`shipper_id`='$shipper_id',
                 `consignee_id`='$consignee_name',`destination_id`='$destination',`bag_number`='$bag_number',
                 `type`='$type',`pcs`='$pcs',`a.weight`='$a_weight',`b.weight`='$b_weight',`value`='$price_value',
                 `timer_id`='$this->timer_id' WHERE sl_num='$sl_num'";

        if($this->connection->query($query)){

          $report_data = 'AWB ' . $awb_number . ' updated by User';

          $report_status = $this->log_report_insert($user_id, $report_data, $this->timer_id);

          if($report_status){

            $this->status_message = 'Successfully updated a new AWB';
            return $this->status_message;

          }else{

            $this->status_message = 'Successfully updated a new AWB but log can not be generated';
            return $this->status_message;

          }

        }else{

          $this->status_message = 'Problem updating AWB. Please try again';

          return $this->status_message;

        }

    }



    public function update_mawb_flight($awb_id, $mawb_id, $flight_number, $next_delivery, $user_id){


      //calling the timer fetch function;
      $this->timer_id = $this->fetch_time();

      if(is_numeric($this->timer_id)){

        $query = "INSERT INTO `awb_mawb_flight_relation`(`awb_id`, `mawb_id`, `flight_id`, `next_branch`)
                  VALUES ('$awb_id', '$mawb_id', '$flight_number', '$next_delivery')";

        if($this->connection->query($query)){

          //update AWB status;
          //First make previous status inactive
          $query = "UPDATE `awb_status` SET `status_active`='0' WHERE awb_id='$awb_id'";

          if($this->connection->query($query)){

            //insert new status
            $query = "INSERT INTO `awb_status`(`awb_id`, `user_id`, `timer_id`, `delivery_status`, `status_active`)
                      VALUES ('$awb_id', '$user_id', '$this->timer_id', 'On the way', '1')";

            if($this->connection->query($query)){

              $query = "UPDATE `awb_lock` SET `lock_status`='locked' WHERE awb_id='$awb_id'";

              if($this->connection->query($query)){

                $report_data = 'MAWB and Flight of AWB Number ' . $awb_id . ' is updated and locked by User';

                $report_status = $this->log_report_insert($user_id, $report_data, $this->timer_id);

                if($report_status){

                  $this->status_message = 'Successfully updated MAWB, Flight and Next Destination';

                  return $this->status_message;

                }else{

                  $this->status_message = 'AWB is updated but log report cannot be generated';

                  return $this->status_message;


                }

              }else{

                $this->status_message = 'AWB is updated but lock status cannot be changed';

                return $this->status_message;


              }

            }else{

              $this->status_message = 'AWB is updated but new status cannot be changed';

              return $this->status_message;


            }

          }else{

            $this->status_message = 'AWB is updated but previous status cannot be changed';

            return $this->status_message;


          }

        }else{

          $this->status_message = 'Problem updating MAWB and Flight. Please try again';

          return $this->status_message;


        }


      }else{

        $this->status_message = 'Problem updating. Please try again';

        return $this->status_message;


      }


    }



    public function unlock_awb($awb_id, $user_id){

      //calling the timer fetch function;
      $this->timer_id = $this->fetch_time();

      if(is_numeric($this->timer_id)){

        $query = "UPDATE `awb_lock` SET `lock_status`='unlocked' WHERE awb_id='$awb_id'";

        if($this->connection->query($query)){

          $query = "SELECT sl_num FROM awb_status WHERE awb_id='$awb_id' AND status_active='1'";

          $result = $this->getData($query);

          foreach ($result as $key => $res) {

            $sl_num = $res['sl_num'];

            echo $sl_num;

          }

          //query running double times for unkown reason. This only works in 'Received by branch' is seletcted
          $query = "SELECT delivery_status FROM awb_status WHERE awb_id='$awb_id' AND sl_num='$sl_num'";

          $result = $this->getData($query);

          foreach ($result as $key => $res) {

            $dev_status = $res['delivery_status'];

          }

          if($dev_status != 'Received by branch'){

            //inactive previous status;
            $query = "UPDATE `awb_status` SET `status_active`='0' WHERE awb_id='$awb_id' AND sl_num='$sl_num'";

            if($this->connection->query($query)){

              $query = "INSERT INTO `awb_status`(`awb_id`, `user_id`, `timer_id`, `delivery_status`, `status_active`)
                        VALUES ('$awb_id', '$user_id', '$this->timer_id', 'Received by branch', '1')";

              if($this->connection->query($query)){

                $report_data = 'AWB unlocked ' . $awb_id . ' by User';

                $report_status = $this->log_report_insert($user_id, $report_data, $this->timer_id);

                if($report_status){

                  $this->status_message = 'Successfully unlocked AWB';

                  return $this->status_message;

                }else{

                  $this->status_message = 'AWB is unlocked but log report cannot be generated';

                  return $this->status_message;


                }

              }else{

                $this->status_message = 'Problem creating new status. Please try again';

                return $this->status_message;

              }

            }else{

              $this->status_message = 'Problem changing previous status. Please try again';

              return $this->status_message;

            }

          }else{

            $this->status_message = 'That unknown error found';

            return $this->status_message;

          }

        }else{

          $this->status_message = 'Problem unlocking AWB. Please try again';

          return $this->status_message;

        }

      }else{

        $this->status_message = 'Problem unlocking. Please try again';

        return $this->status_message;

      }

    }


    public function awb_delete($awb_id, $user_id){

      //calling the timer fetch function;
      $this->timer_id = $this->fetch_time();

      if(is_numeric($this->timer_id)){

        //delete awb_third_party
        $query = "DELETE FROM `awb_third_party` WHERE awb_id='$awb_id'";

        if($this->connection->query($query)){

          //delete awb status_active
          $query = "DELETE FROM `awb_status` WHERE awb_id='$awb_id'";

          if($this->connection->query($query)){

            //delete awb mawb flight relation
            $query = "DELETE FROM `awb_mawb_flight_relation` WHERE awb_id='$awb_id'";

            if($this->connection->query($query)){

              $query = "DELETE FROM `awb_lock` WHERE awb_id='$awb_id'";

              if($this->connection->query($query)){

                //delete awb details

                $query = "DELETE FROM `awb_details` WHERE awb_id='$awb_id'";

                if($this->connection->query($query)){

                  $report_data = 'AWB ' . $awb_id . ' deleted by User';

                  $report_status = $this->log_report_insert($user_id, $report_data, $this->timer_id);

                  if($report_status){

                    $this->status_message = 'Successfully delete AWB';

                    return $this->status_message;

                  }else{

                    $this->status_message = 'AWB is deleted but log report cannot be generated';

                    return $this->status_message;


                  }

                }else{

                  $this->status_message = 'Problem deletion AWB Lock Status. Please try again';

                  return $this->status_message;

                }

              }else{

                $this->status_message = 'Problem deletion AWB Lock Status. Please try again';

                return $this->status_message;

              }

            }else{

              $this->status_message = 'Problem deletion AWB MAWB Flight Relation. Please try again';

              return $this->status_message;

            }

          }else{

            $this->status_message = 'Problem deletion AWB Status. Please try again';

            return $this->status_message;

          }

        }else{

          $this->status_message = 'Problem deletion AWB Third Party. Please try again';

          return $this->status_message;

        }


      }else{

        $this->status_message = 'Problem deletion. Please try again';

        return $this->status_message;

      }


    }


    public function update_thirdparty_consignee($awb_id, $next_delivery, $user_id){

      //calling the timer fetch function;
      $this->timer_id = $this->fetch_time();

      //check if next delivery is only a third party on consignee;

      if($next_delivery == 'third_party' or $next_delivery == 'consignee'){

        $query = "UPDATE `awb_mawb_flight_relation` SET `next_branch`='$next_delivery' WHERE `awb_id`='$awb_id'";

        if($this->connection->query($query)){

          $report_data = 'AWB ' . $awb_id . ' destination updated by User';

          $report_status = $this->log_report_insert($user_id, $report_data, $this->timer_id);

          if($report_status){

            $this->status_message = 'Successfully updated next destination';

            return $this->status_message;

          }else{

            $this->status_message = 'AWB is updated but log report cannot be generated';

            return $this->status_message;


          }

        }else{

          $this->status_message = 'Problem updating next branch';

          return $this->status_message;

        }

      }else{

        $this->status_message = 'Must be a third party or consignee';

        return $this->status_message;

      }


    }

    public function create_third_party($awb_id, $third_party_name, $third_party_address, $third_party_contact_number, $third_party_awb_number, $third_party_destination, $user_id){


      //check if awb_id exists or not;
      $query = "SELECT sl_num FROM awb_details WHERE awb_id='$awb_id'";

      $result = $this->connection->query($query);

      if($result->num_rows >= 1) {

        //calling the timer fetch function;
        $this->timer_id = $this->fetch_time();

        $query = "INSERT INTO `awb_third_party`(`awb_id`, `third_party_name`, `third_party_address`, `third_party_contact_number`,
                  `third_party_number`, `third_party_destination`, `timer_id`) VALUES ('$awb_id', '$third_party_name', '$third_party_address',
                  '$third_party_contact_number', '$third_party_awb_number', '$third_party_destination', '$this->timer_id')";

        if($this->connection->query($query)){

          $report_data = 'Third party created for ' . $awb_id . ' by User';

          $report_status = $this->log_report_insert($user_id, $report_data, $this->timer_id);

          if($report_status){

            $this->status_message = 'Successfully created Third Party for AWB';

            return $this->status_message;

          }else{

            $this->status_message = 'Third Party created but log report cannot be generated';

            return $this->status_message;


          }

        }else{

          $this->status_message = 'Cannot create Third Party. Please try again';

          return $this->status_message;

        }

      }else{

        $this->status_message = 'Do not try this again. AWB not exists';

        return $this->status_message;

      }

    }


    public function update_third_party($awb_id, $third_party_name, $third_party_address, $third_party_contact_number, $third_party_awb_number, $third_party_destination, $user_id){


      //check if awb_id exists or not;
      $query = "SELECT sl_num FROM awb_details WHERE awb_id='$awb_id'";

      $result = $this->connection->query($query);

      if($result->num_rows >= 1) {

        //calling the timer fetch function;
        $this->timer_id = $this->fetch_time();

        $query = "UPDATE `awb_third_party` SET `third_party_name`='$third_party_name',
                 `third_party_address`='$third_party_address',`third_party_contact_number`='$third_party_contact_number',
                 `third_party_number`='$third_party_awb_number',`third_party_destination`='$third_party_destination'
                  WHERE `awb_id`='$awb_id'";

        if($this->connection->query($query)){

          $report_data = 'Third party updated for ' . $awb_id . ' by User';

          $report_status = $this->log_report_insert($user_id, $report_data, $this->timer_id);

          if($report_status){

            $this->status_message = 'Successfully updated Third Party for AWB';

            return $this->status_message;

          }else{

            $this->status_message = 'Third Party updated but log report cannot be generated';

            return $this->status_message;


          }

        }else{

          $this->status_message = 'Cannot update Third Party. Please try again';

          return $this->status_message;

        }

      }else{

        $this->status_message = 'Do not try this again. AWB not exists';

        return $this->status_message;

      }

    }


}


?>
