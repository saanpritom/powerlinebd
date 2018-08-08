<?php

include_once('../../../super_classes/db_connection.php');
include_once('../../../super_classes/timer.php');

class DashboardData extends DbConfig
{
    public function __construct()
    {
        parent::__construct();
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



    public function total_awb(){

      $query = "SELECT COUNT(awb_id) AS t_id FROM awb_details WHERE 1";

      $result = $this->getData($query);

      foreach ($result as $key => $value) {

        $t_id = $value['t_id'];

      }

      return $t_id;

    }


    public function total_undelivered(){

      $query = "SELECT COUNT(awb_id) AS t_id FROM awb_status WHERE NOT delivery_status='Consignee' AND status_active='1'";

      $result = $this->getData($query);

      foreach ($result as $key => $value) {

        $t_id = $value['t_id'];

      }

      return $t_id;

    }


    public function total_shippers(){

      $query = "SELECT COUNT(shipper_id) AS t_id FROM shipper_details WHERE 1";

      $result = $this->getData($query);

      foreach ($result as $key => $value) {

        $t_id = $value['t_id'];

      }

      return $t_id;

    }

    public function total_users(){

      $query = "SELECT COUNT(admin_id) AS t_id FROM admin_details WHERE 1";

      $result = $this->getData($query);

      foreach ($result as $key => $value) {

        $t_id = $value['t_id'];

      }

      return $t_id;

    }


    public function top_shippers(){

      $t_id = array();

      $i = 0;

      $query = "SELECT DISTINCT shipper_details.name FROM shipper_details
                INNER JOIN awb_details ON awb_details.shipper_id=shipper_details.shipper_id
                WHERE 1 GROUP BY awb_id ORDER BY COUNT(awb_details.shipper_id) DESC LIMIT 5";

      $result = $this->getData($query);

      foreach ($result as $key => $value) {

        $t_id[$i] = $value['name'];

        $i++;

      }

      return $t_id;

    }


    public function top_branches(){

      $t_id = array();

      $i = 0;

      $query = "SELECT DISTINCT office_branch.name FROM office_branch INNER JOIN admin_details
                ON office_branch.branch_id=admin_details.branch_id INNER JOIN awb_details
                ON admin_details.admin_id=awb_details.user_id WHERE 1 GROUP BY awb_id
                ORDER BY COUNT(office_branch.branch_id) DESC LIMIT 5";

      $result = $this->getData($query);

      foreach ($result as $key => $value) {

        $t_id[$i] = $value['name'];

        $i++;

      }

      return $t_id;


    }


    public function top_users(){


      $t_id = array();

      $i = 0;

      $query = "SELECT DISTINCT admin_details.name FROM admin_details INNER JOIN awb_details
                ON admin_details.admin_id=awb_details.user_id WHERE 1 GROUP BY awb_id
                ORDER BY COUNT(admin_details.admin_id) DESC LIMIT 5";

      $result = $this->getData($query);

      foreach ($result as $key => $value) {

        $t_id[$i] = $value['name'];

        $i++;

      }

      return $t_id;


    }


    public function latest_awbs(){

      $t_id = array();

      $i = 0;

      $query = "SELECT awb_details.awb_id, awb_status.delivery_status, office_branch.name,
                awb_details.destination_id, awb_details.consignee_id
                FROM awb_details
                INNER JOIN creation_details ON awb_details.timer_id=creation_details.timer_id
                INNER JOIN awb_status ON awb_details.awb_id=awb_status.awb_id
                INNER JOIN admin_details ON awb_details.user_id=admin_details.admin_id
                INNER JOIN office_branch ON admin_details.branch_id=office_branch.branch_id
                WHERE awb_status.status_active='1'
                ORDER BY creation_details.creation_date DESC, creation_details.creation_time DESC LIMIT 5";

      $result = $this->getData($query);

      foreach ($result as $key => $value) {

        $t_id[$i] = $value['awb_id'];

        $i++;

        $t_id[$i] = $value['delivery_status'];

        $i++;

        $t_id[$i] = $value['name'];

        $i++;

        if(is_numeric($value['destination_id'])){

          $temp_consignee = $value['consignee_id'];

          $query2 = "SELECT consignee_details.address AS c_name FROM consignee_details INNER JOIN awb_details
                      ON consignee_details.consignee_id=awb_details.consignee_id WHERE awb_details.consignee_id='$temp_consignee'";

                      

          $result2 = $this->getData($query2);

          foreach ($result2 as $key2 => $value2) {

            $address = $value2['c_name'];

          }

          $t_id[$i] = $address;

          $i++;


        }else{

          $t_id[$i] = $value['destination_id'];

          $i++;

        }

      }

      return $t_id;


    }


}
