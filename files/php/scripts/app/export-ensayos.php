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
$items = array();
foreach((array)$ensayos_grupo as $e){
	$ensayos = Entity::listMe("Phenobook","active AND id = '" . $e->id . "' ORDER BY id DESC");
	$items = array_merge($items, $ensayos);
}

echo json_encode($items);