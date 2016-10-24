<?php
header('Access-Control-Allow-Origin: *');
$noLogin = true;
$noMenu = true;
$noHeader = true;
require_once "../files/php/config/require.php";

$email = _request("email");
$pass = _request("pass");
$user = Entity::search("User", "email = '$email' AND pass = '$pass' AND active");
if(!$user){
	die("error");
}
$ug = _request("from_app")?" AND userGroup = '".$user->userGroup->id."' ":"";
echo json_encode(Entity::listMe("Variable","active $ug"));
