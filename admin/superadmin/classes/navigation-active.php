<?php

class NavigationActive
{

  public $child_menu, $parent_menu, $exploded_data;

  public function url_detection($str)
  {
      $this->exploded_data = explode("/",$str);
      $this->child_menu = end($this->exploded_data);
      $this->parent_menu = prev($this->exploded_data);

      return array('child_menu' => $this->child_menu, 'parent_menu' => $this->parent_menu);

  }
}


?>
