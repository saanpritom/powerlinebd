<?php
include_once('../../../../super_classes/db_connection.php');

class FetchConsignee extends DbConfig
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


$get_data = new FetchConsignee();


if(!empty($_POST["keyword"])) {

  $keyword = $_POST["keyword"];

  //$query = "SELECT `name` FROM `consignee_details` WHERE name LIKE '$keyword %' ORDER BY `name` LIMIT 0,6";

  $query = "SELECT mawb_number FROM mawb_details WHERE mawb_number LIKE '$keyword%' ORDER BY `mawb_number` LIMIT 0,6";

            //echo $query;
  $result = $get_data->getData($query);


  if(!empty($result)) {

    ?>

    <?php
    foreach($result as $country) {
    ?>
    <span onClick="selectCountry('<?php echo $country["mawb_number"]; ?>')"><?php echo $country["mawb_number"]; ?></span><br>
    <?php }

  }

}

?>
