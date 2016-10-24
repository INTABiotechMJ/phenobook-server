<?php
header('Access-Control-Allow-Origin: *');
$noLogin = true;
$noMenu = true;
$noHeader = true;
require "../files/php/config/require.php";
$email = _request("email");
$pass = _request("pass");
$user = Entity::search("User", "email = '$email' AND pass = '$pass' AND active");
if(!$user){
	die("error");
}
$userGroup = $user->userGroup;
$ug = _post("from_app")?" AND userGroup = '".$user->userGroup->id."' ":"";
$users = Entity::listMe("User","active $ug");
foreach((array)$users as $user){
	//$user->pass = md5($user->pass);
}
echo json_encode($users);
