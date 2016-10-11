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
$registries = Entity::listMe("Registry","active AND status");
foreach((array)$registries as $r){
	if($r->variable->fieldType->isPhoto()){
		$path = __ROOT . $r->value;
		if(file_exists($path)){
			$data = file_get_contents($path);
			$base64 = base64_encode($data);
			$r->value = $base64;
		}else{
			$r->value = "";
		}
	}
}
echo json_encode($registries);
