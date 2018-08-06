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

      $query = "SELECT count awb_id AS t_id FROM awb_details WHERE 1";

      $result = $this->getData($query);

      foreach ($result as $key => $value) {

        $t_id = $value['t_id'];

      }

      return $t_id;

    }


    public function total_undelivered(){

      $query = ""

    }

}
