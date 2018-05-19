<?php

include_once('../../../super_classes/db_connection.php');

class Clearence extends DbConfig
{
    public function __construct()
    {
        parent::__construct();
    }

    public function escape_string($value)
    {
        return $this->connection->real_escape_string($value);
    }

}

?>
