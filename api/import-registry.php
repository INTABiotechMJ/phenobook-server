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
$phenobook = Entity::load("Phenobook",_request("phenobook_id"));
if(!$phenobook){
	die("please specify a valid phenobook id");
}
$variable = Entity::load("Variable",_request("variable_id"));
if(!$variable){
	die("please specify a valid variable");
}
$exp_unit = _request("experimental_unit");
if(!$exp_unit){
	die("please specify a valid experimental_unit");
}
$value = _request("value");
if(!$value){
	die("please specify a valid value");
}
Entity::begin();
$oldRegs = Entity::listMe("Registry", "active AND phenobook = '$phenobook->id' AND experimental_unit_number = '$exp_unit' AND variable = '$variable->id'");
foreach((array)$oldRegs as $oldReg){
	$oldReg->status = '0';
	Entity::update($oldReg);
}
$r = new Registry();
$r->stamp = stamp();
$r->user = $user;
$r->variable = $variable;
$r->phenobook = $phenobook;
$r->status = "1";
$r->experimental_unit_number = $exp_unit;
$r->value = $value;
Entity::save($r);
Entity::commit();
die(json_encode($r));
