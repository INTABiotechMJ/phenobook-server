<?php
header('Access-Control-Allow-Origin: *');
$noLogin = true;
$noMenu = true;
$noHeader = true;
require_once "../files/php/config/require.php";

$email = _post("user");
$pass = _post("pass");
$user = Entity::search("User", "email = '$email' AND pass = '$pass' AND active");
if(!$user){
	die("error");
}
$userGroup = $user->userGroup;
$ug = _post("from_app")?" AND userGroup = '$userGroup->id' ":"";
echo json_encode(Entity::listMe("Phenobook","active $ug AND visible "));
