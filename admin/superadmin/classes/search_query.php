<?php

include_once('../../../super_classes/db_connection.php');
include_once('../../../super_classes/timer.php');

class SearchOperation extends DbConfig
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



    public function text_search_result($keyword){

      $keyword = $this->connection->escape_string($keyword);
      $keyword = strip_tags(trim($keyword));
      $keyword = htmlentities($keyword);

      $query_result = array();

      $i=0;


      //Search AWB Numbers
      $query = "SELECT awb_id AS id FROM awb_details WHERE awb_id LIKE '%$keyword%'";

      $result = $this->getData($query);

      foreach ($result as $key => $value) {

        //fetch the data which will be used to link the page
        $query_result[$i] = $value['id'];

        $i++;

        //fetch the data which will be shown
        $query_result[$i] = $value['id'];

        $i++;

        //fetch the data to determine which type it is
        $query_result[$i] = 'AWB';

        $i++;

      }

      $search_result = $query_result;

      $i=0;

      unset($query_result);

      $query_result = array();


      //Search MAWB Numbers
      $query = "SELECT mawb_id, mawb_number FROM mawb_details WHERE mawb_number LIKE '%$keyword%'";

      $result = $this->getData($query);

      foreach ($result as $key => $value) {

        //fetch the data which will be used to link the page
        $query_result[$i] = $value['mawb_id'];

        $i++;

        //fetch the data which will be shown
        $query_result[$i] = $value['mawb_number'];

        $i++;

        //fetch the data to determine which type it is
        $query_result[$i] = 'MAWB';

        $i++;

      }

      $search_result = array_merge($search_result, $query_result);

      $i=0;

      unset($query_result);

      $query_result = array();


      //search branch id and name
      $query = "SELECT branch_id, name FROM office_branch WHERE branch_id LIKE '%$keyword%' OR name LIKE '%$keyword%'";

      $result = $this->getData($query);

      foreach ($result as $key => $value) {

        $query_result[$i] = $value['branch_id'];

        $i++;

        $query_result[$i] = $value['name'];

        $i++;

        $query_result[$i] = 'office_branch';

        $i++;

      }

      $search_result = array_merge($search_result, $query_result);

      $i = 0;

      unset($query_result);

      $query_result = array();



      //search username only users
      $query = "SELECT admin_details.admin_id, admin_details.name FROM admin_details INNER JOIN login_info
                ON admin_details.admin_id=login_info.user_id WHERE login_info.user_type='admin' AND (admin_details.admin_id LIKE '%$keyword%' OR
                admin_details.name LIKE '%$keyword%' OR admin_details.contact_number LIKE '%$keyword%' OR
                login_info.email LIKE '%$keyword%')";

      $result = $this->getData($query);

      foreach ($result as $key => $value) {

        $query_result[$i] = $value['admin_id'];

        $i++;

        $query_result[$i] = $value['name'];

        $i++;

        $query_result[$i] = 'username';

        $i++;

      }

      $search_result = array_merge($search_result, $query_result);

      $i = 0;

      unset($query_result);

      $query_result = array();


      //search for shippers
      $query = "SELECT shipper_id, name FROM shipper_details WHERE name LIKE '%$keyword%' OR contact_number LIKE '%$keyword%'";

      $result = $this->getData($query);

      foreach ($result as $key => $value) {

        $query_result[$i] = $value['shipper_id'];

        $i++;

        $query_result[$i] = $value['name'];

        $i++;

        $query_result[$i] = 'shipper';

        $i++;

      }

      $search_result = array_merge($search_result, $query_result);

      $i = 0;

      unset($query_result);

      $query_result = array();



      //search for consignee
      $query = "SELECT consignee_id, name FROM consignee_details WHERE consignee_id LIKE '%$keyword%' OR
                name LIKE '%$keyword%' OR email LIKE '%$keyword%' OR contact_number LIKE '%$keyword'";

      $result = $this->getData($query);

      foreach ($result as $key => $value) {

        $query_result[$i] = $value['consignee_id'];

        $i++;

        $query_result[$i] = $value['name'];

        $i++;

        $query_result[$i] = 'consignee';

        $i++;

      }

      $search_result = array_merge($search_result, $query_result);

      $i = 0;

      unset($query_result);

      $query_result = array();


      //search shipper contact person
      $query = "SELECT contact_id, name FROM contact_person_details WHERE person_from='shipper' AND
                (contact_id LIKE '%$keyword%' OR name LIKE '%$keyword%' OR contact_number LIKE '%$keyword%')";

      $result = $this->connection->query($query);

      foreach ($result as $key => $value) {

        $query_result[$i] = $value['contact_id'];

        $i++;

        $query_result[$i] = $value['name'];

        $i++;

        $query_result[$i] = 'shipper_contact';

        $i++;

      }

      $search_result = array_merge($search_result, $query_result);

      $i = 0;

      unset($query_result);

      $query_result = array();


      //search consignee contact person
      $query = "SELECT contact_id, name FROM contact_person_details WHERE person_from='consignee' AND
                (contact_id LIKE '%$keyword%' OR name LIKE '%$keyword%' OR contact_number LIKE '%$keyword%')";

      $result = $this->connection->query($query);

      foreach ($result as $key => $value) {

        $query_result[$i] = $value['contact_id'];

        $i++;

        $query_result[$i] = $value['name'];

        $i++;

        $query_result[$i] = 'consignee_contact';

        $i++;

      }

      $search_result = array_merge($search_result, $query_result);

      $i = 0;

      unset($query_result);

      $query_result = array();

      return $search_result;


    }


}


?>
