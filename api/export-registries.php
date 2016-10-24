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
$ug = _post("from_app")?" AND userGroup = '".$user->userGroup->id."' ":"";
$pheno_sql = "";
$exp_unit_sql = "";
$variable_sql = "";
if(_request("phenobook_id")){
	$id_phenobook = _request("phenobook_id");
	$pheno_sql = " AND phenobook = '$id_phenobook' ";
}
if(_request("experimental_unit")){
	$experimental_unit = _request("experimental_unit");
	$exp_unit_sql = " AND experimental_unit_number = '$experimental_unit' ";
}
if(_request("variable_id")){
	$id_variable = _request("variable_id");
	$variable_sql = " AND experimental_unit_number = '$id_variable' ";
}
$registries = Entity::listMe("Registry","active $ug AND status $pheno_sql $exp_unit_sql $variable_sql ");
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
