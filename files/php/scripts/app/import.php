<?php
set_time_limit(5);
header('Access-Control-Allow-Origin: *');
$noLogin = true;
$noMenu = true;
$noHeader = true;
require "../../../../files/php/config/require.php";
$email = _post("email");
$pass = _post("pass");
$user = Entity::search("User", "email = '$email' AND pass = '$pass' AND active");
if(!$user){
	die("error");
}

$data = _post("data");
$json = json_decode($data);
foreach((array) $json as $j){
	$oldRegs = Entity::listMe("Registry", "active AND experimental_unit_number = '$j->eu' variable = '$j->variable'");
	foreach((array)$oldRegs as $oldReg){
		$oldReg->status = '0';
		Entity::update($oldReg);
	}
	$reg = new Registry();
	$reg->user = Entity::load("User", $j->user);
	$reg->stamp = stamp();
	$reg->status = $j->status;
	$reg->localStamp = $j->localStamp;
	$reg->latitude = $j->latitude;
	$reg->longitude = $j->longitude;
	$reg->experimental_unit_number = $j->eu;
	$reg->variable = Entity::load("Variable", $j->variable);
	$reg->phenobook = Entity::load("Phenobook", $j->phenobook);

	if($reg->variable->fieldType->isPhoto()){
		$dir_rel = "files/uploads/" . date("Y") . "/". date("m") ."/";
		$dir = __ROOT . $dir_rel;
		if (!file_exists($dir)) {
			if(!@mkdir($dir, 0777, true)){
				die("Cannot create directory");
			}
		}
		$filename = time() . rand(0, 10000) .".jpg";
		$decoded = base64_decode($j->value);
		file_put_contents($dir.$filename,$decoded);
		$reg->value = $dir_rel.$filename;
	}else{
		$reg->value = $j->value;
	}

	Entity::save($reg);
}
die(stamp());
