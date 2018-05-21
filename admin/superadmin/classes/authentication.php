<?php

include_once('../../../super_classes/db_connection.php');

class AuthCheckOperation extends DbConfig
{
    public function __construct()
    {
        parent::__construct();
    }

    public $login_status, $user_login_id;

    public function check_if_logged_in()
    {
      //fetch the session id;
      session_start();
      $this->user_login_id = $_SESSION["plbd_id"];

      $query = "SELECT `login_status` FROM `login_info` WHERE `user_id`='$this->user_login_id'";

      $result = $this->connection->query($query);

      while($row = $result->fetch_assoc()){

        $this->login_status = $row['login_status'];

      }

      if($this->user_login_id == 0 and $this->login_status == 0){

        header('Location: ../../../signin.php', true, 302);
        exit;

      }else{

        return true;

      }

    }
}

//call login check class;

$chk_login = new AuthCheckOperation();

$chk_login->check_if_logged_in();


?>
