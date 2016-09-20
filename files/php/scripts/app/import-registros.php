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
//get json data
//search for ensayo, variable, parcela
	// if exists set state to old
//create new one
$data = _post("data");
$json = json_decode($data);
foreach((array) $json as $j){
	$oldRegs = Entity::listMe("Registro", "parcela = '$j->parcela' AND variable = '$j->variable'");
	foreach((array)$oldRegs as $oldReg){
		$oldReg->status = '0';
		Entity::update($oldReg);
	}
	$reg = new Registro();
	$reg->user = Entity::load("User", $j->user);
	$reg->stamp = stamp();
	$reg->parcela = Entity::load("Parcela", $j->parcela);
	$reg->status = $j->status;
	$reg->localStamp = $j->localStamp;
	$reg->latitude = $j->latitude;
	$reg->longitude = $j->longitude;

	$reg->variable = Entity::load("Variable", $j->variable);

	if($reg->variable->tipoCampo->isFoto()){
		$dir_rel = "files/uploads/" . date("Y") . "/". date("m") ."/";
		$dir = __ROOT . $dir_rel;
		if (!file_exists($dir)) {
			if(!@mkdir($dir, 0777, true)){
				die("No se puede crear el directorio");
			}
		}
		$filename = time() . rand(0, 10000) .".jpg";
		$decoded = base64_decode($j->valor);
		file_put_contents($dir.$filename,$decoded);
		$reg->valor = $dir_rel.$filename;
	}else{
		$reg->valor = $j->valor;
	}

	if($reg->variable->tipoCampo->isAudio()){
		$dir_rel = "files/uploads/" . date("Y") . "/". date("m") ."/";
		$dir = __ROOT . $dir_rel;
		if (!file_exists($dir)) {
			if(!@mkdir($dir, 0777, true)){
				die("No se puede crear el directorio");
			}
		}
		$filename = time() . rand(0, 10000) .".jpg";
		$decoded = base64_decode($j->valor);
		file_put_contents($dir.$filename,$decoded);
		$reg->valor = $dir_rel.$filename;
	}

	Entity::save($reg);
}
echo $user->userGroup;
