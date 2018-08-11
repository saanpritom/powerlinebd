<?php

  include_once('../classes/data_clearence.php');
  include_once('../classes/mawb_crud.php');
  include_once('../classes/excel_generate.php');
  include_once('../../../super_classes/Spreadsheet/Excel/Writer.php');


  /*$mawb_id = $_GET['b_id'];

  $user_id = $_SESSION['plbd_id'];

  $document_name = $mawb_id . $user_id . '.xls';

  $document_path = '/powerlinebd/media/excel_reports/' . $document_name;

  // Creating a workbook
  $workbook = new Spreadsheet_Excel_Writer($document_path);


  $worksheet =& $workbook->addWorksheet('My first worksheet');

  $worksheet->write(0, 0, 'Name');
  $worksheet->write(0, 1, 'Age');
  $worksheet->write(1, 0, 'John Smith');
  $worksheet->write(1, 1, 30);
  $worksheet->write(2, 0, 'Johann Schmidt');
  $worksheet->write(2, 1, 31);
  $worksheet->write(3, 0, 'Juan Herrera');
  $worksheet->write(3, 1, 32);

  // We still need to explicitly close the workbook
  $workbook->close();*/

?>
