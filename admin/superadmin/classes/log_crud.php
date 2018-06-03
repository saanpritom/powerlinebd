<?php

include_once('../../../super_classes/db_connection.php');
include_once('../../../super_classes/timer.php');

class LogCrudOperation extends DbConfig
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

}
