<?php

  include_once('super_classes/logout_class.php');

  $logout = new LogoutOperation();

  $logout_result = $logout->do_logout();

  if($logout_result){

    header('Location: signin', true, 302);
    exit;

  }

?>
