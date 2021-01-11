<?php
//create funcation to translate array data to html table
function html_table($data = array(),$table_id)
  {
  //head is the array keys of the first item in the array
  $header = array_keys($data[0]);
  foreach ($header as $headeritem) {
    $headeritem=strtoupper($headeritem);
    $headeritems[] = "<th>{$headeritem}</th>";
  }
  $rows = array();
  foreach ($data as $row) {
  $cells = array();
      foreach ($row as $cell) {
        $cells[] = "<td>{$cell}</td>";
      }
  $rows[] = "<tr>" . implode('', $cells) . "</tr>";
  }
//pass the format for Jquery datatable
return "<table id=$table_id class='cell-border' style='width:100%'>" . "<thead>" . implode('', $headeritems) ."</thead>" . "<tbody>" . implode('', $rows) . "</tbody>" . "</table>";
}
?>
