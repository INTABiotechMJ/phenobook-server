<?php
$noLogin = true;
$noHeader = false;
$noMenu = false;
require "../../../files/php/config/require.php";
$SQL = "SELECT id, LOWER(TRIM(line_name)),LOWER(TRIM(breeder)), origin,year, status FROM LineaCaracterizacion WHERE active";
$sth = $GLOBALS["conn"]->query($SQL);
$sth->setFetchMode(PDO::FETCH_ASSOC);
$data = $sth->fetchAll();
$res = array();
foreach ($data as $item) {
	foreach ($item as $key => $value) {
		$value = strtolower(trim($value));
		$key = strtolower(trim($key));
		if($key == "id"){
			$id = $value;
		}
	}
	$SQL2 = "SELECT name as gen, value as alelo FROM GenCaracterizacion WHERE GenCaracterizacion.lineaCaracterizacion = '$id' AND NOT name LIKE '%orig%'";
	$sth2 = $GLOBALS["conn"]->query($SQL2);
	$sth2->setFetchMode(PDO::FETCH_ASSOC);
	$data2 = $sth2->fetchAll();
	foreach ($data2 as $i) {
			$item[strtolower(trim($i["gen"]))] = strtolower(trim($i["alelo"]));
	}
	$res[] = $item;
}
header('Content-disposition: attachment; filename=res.json');
header('Content-type: application/json');
echo json_encode($res);
/*
function outputCSV($data) {
$outputBuffer = fopen("php://output", 'w');
foreach($data as $val) {
fputcsv($outputBuffer, $val);
}
fclose($outputBuffer);
}

$filename = "file.csv";

header("Content-type: text/csv");
header("Content-Disposition: attachment; filename={$filename}.csv");
header("Pragma: no-cache");
header("Expires: 0");
outputCSV($data);
*/
