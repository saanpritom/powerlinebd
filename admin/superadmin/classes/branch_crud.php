<?php

include_once('../../../super_classes/db_connection.php');
include_once('../../../super_classes/timer.php');

class BranchCrudOperation extends DbConfig
{
    public function __construct()
    {
        parent::__construct();
    }

    public $timer_id, $brancher_id, $branch_id_check, $status_message;

    //fetching time_id from timer class;
    public function fetch_time()
    {

      $timing = new Timer();
      $this->timer_id = $timing->create_timer_id();
      return $this->timer_id;

    }

    //check if the branch id is exist on the database;
    public function check_branch_id($brancher_id)
    {
      $query = "SELECT sl_num FROM office_branch WHERE branch_id='$this->brancher_id'";
      $result = $this->connection->query($query);
      if($result->num_rows > 0) {
        return true;
      }else{
        return false;
      }
    }

    //insert data into log report table;
    public function log_report_insert($user_id, $branch_name, $timing_id)
    {

      $report_data = "$branch_name updated by User";

      $query = "INSERT INTO `log_report`(`user_id`, `log_report`, `timer_id`)
                VALUES ('$user_id','$report_data','$timing_id')";
      if($this->connection->query($query))
      {
        return true;
      }else{
        return false;
      }

    }

    //creating a new branch on the database;
    public function create_branch($b_name, $e_name, $c_name, $adrs)
    {

      //calling the timer fetch function;
      $this->timer_id = $this->fetch_time();

      //create and check branch_id;
      $this->brancher_id = mt_rand();

      $this->branch_id_check = $this->check_branch_id($this->brancher_id);

      if($this->branch_id_check){
        $this->brancher_id = 'Branch ID overflow. Please try again';
        return $this->brancher_id;
      }else{

        //check if timer_id created properly
        if(is_numeric($this->timer_id)){

          $query = "INSERT INTO `office_branch`(`branch_id`, `name`, `address`, `email`, `contact_number`, `timer_id`)
                    VALUES ('$this->brancher_id','$b_name','$adrs','$e_name','$c_name','$this->timer_id')";
          if($this->connection->query($query)){
            $this->status_message = 'Successfully created a new branch';
            return $this->status_message;
          }else{
            $this->status_message = 'Problem creating new branch. Please try again';
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

    //update branch data
    public function update_branch($b_id, $b_name, $e_name, $c_name, $adrs)
    {

      //calling the timer fetch function;
      $this->timer_id = $this->fetch_time();

      //check if timer_id created properly
      if(is_numeric($this->timer_id)){

        $query = "UPDATE `office_branch` SET `name`='$b_name',
                         `address`='$adrs',`email`='$e_name',
                         `contact_number`='$c_name' WHERE `branch_id`='$b_id'";
        if($this->connection->query($query)){

          $user_login_id = $_SESSION["plbd_id"];

          if($this->log_report_insert($user_login_id, $b_name, $this->timer_id)){

            $this->status_message = 'Branch updated Successfully';
            return $this->status_message;

          }else{

            $this->status_message = 'Problem creating log report but branch updated';
            return $this->status_message;

          }


        }else{
          $this->status_message = 'Problem updating new branch. Please try again';
          return $this->status_message;
        }


      }else{
        return $this->timer_id;
      }



    }


    public function delete_branch($branch_id, $user_id){

      //check if this branch has associated users
      $query = "SELECT sl_num FROM admin_details WHERE branch_id='$branch_id'";

      $result = $this->connection->query($query);

      if($result->num_rows > 0) {

        $this->status_message = 'You cannot delete this branch. This branch has users with it. At first delete
                                or update user branch then delete this branch';

        return $this->status_message;

      }else{

        //check if this branch has AWB MAWB data

        $query = "SELECT sl_num FROM awb_mawb_flight_relation WHERE next_branch='$branch_id'";

        $result = $this->connection->query($query);

        if($result->num_rows > 0){

          $this->status_message = 'You cannot delete this branch. This branch has assigned as a Next branch for some
                                    AWB. Please change those next branch and then delete it';

          return $this->status_message;

        }else{

          //delete branch details
          $query = "DELETE FROM `office_branch` WHERE branch_id='$branch_id'";

          if($this->connection->query($query)){

            $report_data = 'Branch is deleted by User';

            //calling the timer fetch function;
            $this->timer_id = $this->fetch_time();

            //update log record data;
            $report_status = $this->log_report_insert($user_id, $report_data, $this->timer_id);

            if($report_status){

              $this->status_message = 'Branch deleted Successfully';
              return $this->status_message;

            }else{

              //insert log report not working unknown reason;
              $this->status_message = 'Branch deleted but log report can not be inserted';
              return $this->status_message;

            }


          }else{

            $this->status_message = 'Problem deleting this branch. Please try again';

            return $this->status_message;

          }


        }


      }


    }


}


?>
