<?php 
header('Access-Control-Allow-Origin: *');
$noLogin = true;
$noMenu = true;
$noHeader = true;
require "../../../../files/php/config/require.php";
$users = Entity::listMe("User","active");
foreach((array)$users as $user){
	//$user->pass = md5($user->pass);
}
echo json_encode($users);