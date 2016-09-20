<?php
//exporta todas las entradas ID, nombre linea / variedad
$noHeader = true;
$noMenu = true;
require "../../files/php/config/require.php";
$items = Entity::listMe("Linea");
$data = array();
$col = array();
$col["id"] ="Id";
$col["name"] = "Linea";
$data[] = $col;
foreach ($items as $key => $value) {
  $col = array();
  $col["id"] = $value->id;
  $col["name"] = $value->__toString();
  $data[] = $col;
}
function outputCSV($data) {
    $outputBuffer = fopen("php://output", 'w');
    foreach($data as $val) {
        fputcsv($outputBuffer, $val);
    }
    fclose($outputBuffer);
}
$filename = "lineas.csv";

header("Content-type: text/csv");
header("Content-Disposition: attachment; filename={$filename}.csv");
header("Pragma: no-cache");
header("Expires: 0");

outputCSV($data);
