<?php

class NavigationActive
{

  public $child_menu, $parent_menu, $exploded_data;

  public function url_detection($str)
  {
      $this->exploded_data = explode("/",$str);
      $this->child_menu = end($this->exploded_data);
      $this->parent_menu = prev($this->exploded_data);

      //check for page number contained URL;
      if(is_numeric($this->child_menu)){

        $total_url_length = sizeof($this->exploded_data);

        $this->child_menu = $this->exploded_data[$total_url_length - 2];

        $this->parent_menu = $this->exploded_data[$total_url_length - 3];

      }

      return array('child_menu' => $this->child_menu, 'parent_menu' => $this->parent_menu);

  }
}


?>
