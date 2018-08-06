<?php

include_once('../../../super_classes/db_connection.php');

class UserInfo extends DbConfig
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

  public function fetch_user_details($usn_id){

    $query = "SELECT name FROM admin_details WHERE admin_id='$usn_id'";

    $result = $this->getData($query);

    foreach ($result as $key => $res) {

      $usn_name = $res['name'];


    }

    return $usn_name;

  }

}


?>
