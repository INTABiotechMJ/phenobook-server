<?php 
header('Access-Control-Allow-Origin: *');
$noLogin = true;
$noMenu = true;
$noHeader = true;
require_once "../../../../files/php/config/require.php";
$items = Entity::listMe("Phenobook","active AND visible");
$ret = array();
foreach((array)$items as $item){
	$UserPhenobook = Entity::listMe("UserPhenobook","active AND libroCampo = '$item->id' AND user IS NOT NULL AND libroCampo IS NOT NULL");
	$ret = array_merge($ret, $UserPhenobook);
}
echo json_encode($ret);