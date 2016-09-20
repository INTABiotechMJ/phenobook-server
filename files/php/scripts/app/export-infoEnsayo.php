<?php 
header('Access-Control-Allow-Origin: *');
$noLogin = true;
$noMenu = true;
$noHeader = true;
require_once "../../../../files/php/config/require.php";

$email = _post("email");
$pass = _post("pass");
$user = Entity::search("User", "email = '$email' AND pass = '$pass' AND active");
if(!$user){
	die("error");
}

$ensayos_grupo = Entity::listMe("Phenobook","active AND visible AND grupo = '" . $user->userGroup->id . "' ORDER BY id DESC");
$items_total = array();
foreach((array)$ensayos_grupo as $e){
	$infoEnsayo = Entity::listMe("InfoEnsayo","active AND libroCampo = '$e->id'");
	$items_total = array_merge($items_total, $infoEnsayo);
}

echo json_encode($items_total);