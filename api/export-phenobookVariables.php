<?php
header('Access-Control-Allow-Origin: *');
$noLogin = true;
$noMenu = true;
$noHeader = true;
require_once "../files/php/config/require.php";

$email = _post("email");
$pass = _post("pass");
$user = Entity::search("User", "email = '$email' AND pass = '$pass' AND active");
if(!$user){
	die("error");
}
$ug = _post("from_app")?" AND userGroup = '".$user->userGroup->id."' ":"";

$pvs = Entity::listMe("PhenobookVariable","active");
foreach ($pvs as $k => $v) {
	if($v->phenobook->userGroup->id != $user->userGroup->id ){
		unset($pvs[$k]);
	}
}
echo json_encode(array_values($pvs));
