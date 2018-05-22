<?php

include_once('../../../super_classes/db_connection.php');

class PaginationOperation extends DbConfig
{
    public function __construct()
    {
        parent::__construct();
    }

    public $total_row_number, $pagination_limit, $total_page_number, $status_message;
    public $highest_point, $lowest_point, $current_page_number;


    //calculating how many entries for pagination to show a message;
    public function counting_pagination($query, $local_pagination_limit, $local_current_page_number){

      $this->pagination_limit = $local_pagination_limit;

      $this->current_page_number = $local_current_page_number;

      //calculating highest and lowest point;
      $this->highest_point = $this->current_page_number * $this->pagination_limit;

      //calculating the lowest point;
      $this->lowest_point = $this->highest_point - $this->pagination_limit;

      $result = $this->connection->query($query);

      while ($row = $result->fetch_assoc()) {

          $this->total_row_number = $row['total_id'];

      }

      //calculate how many rows are currently showing on the page;
      $current_page_number_of_showing_id = $this->total_row_number/$this->pagination_limit;

      //calculating total number of pages;
      $this->total_page_number = ceil($current_page_number_of_showing_id);

      //populate status message;
      $display_starting_value = $this->lowest_point + 1;

      $this->status_message = "Showing $display_starting_value to $this->highest_point of $this->total_row_number entries";

      return $this->status_message;

    }


    public function do_pagination($next_page_link){

      ?>

      <nav>
            <ul class="pagination">

              <?php

              if($this->current_page_number == 1){

                ?>
                  <li class="disabled"><a href="javascript:void(0);"><i class="material-icons">chevron_left</i></a></li>
                <?php

              }else{

                //calculating previous page number
                $temp = $this->current_page_number - 1;

                ?>
                  <li><a href="<?php echo $temp ?>"><i class="material-icons">chevron_left</i></a></li>
                <?php

              }

              //showing all of the pagination number;
              for($i=1;$i<=$this->total_page_number;$i++){

                //detect current page;
                if($this->current_page_number == $i){

                  ?>

                  <li class="active"><a href="<?php echo $i; ?>"><?php echo $i; ?></a></li>

                  <?php

                }else{

                  ?>

                  <li><a href="<?php echo $i; ?>"><?php echo $i; ?></a></li>

                  <?php


                }


              }


              //calculating next page;
              if($this->current_page_number == $this->total_page_number){

                ?>

                <li class="disabled"><a href="javascript:void(0);"><i class="material-icons">chevron_right</i></a></li>

                <?php

              }else{

                //calculating next page number;
                $temp = $this->current_page_number + 1;

                ?>

                <li><a href="<?php $temp ?>"><i class="material-icons">chevron_right</i></a></li>

                <?php

              }

              ?>

            </ul>

      </nav>

      <?php


    }

}
