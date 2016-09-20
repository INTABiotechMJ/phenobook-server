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
	$variables = Entity::listMe("Variable","active AND libroCampo = '$e->id'");
	foreach((array)$variables as $variable){
		$items = Entity::listMe("Opcion","active AND variable = '$variable->id'");
		$items_total = array_merge($items_total, $items);
	}
}
echo json_encode($items_total);