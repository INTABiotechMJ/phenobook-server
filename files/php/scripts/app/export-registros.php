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


$ensayos_grupo = Entity::listMe("Phenobook","active AND visible AND grupo = '" . $user->userGroup->id . "' ORDER BY id DESC");
$registros_arr = array();
$items_total = array();
foreach((array)$ensayos_grupo as $ensayo){
	$parcelas = Entity::listMe("Parcela","active AND libroCampo = '$ensayo->id'");
	foreach((array)$parcelas as $parcela){
		$registros = Entity::listMe("Registro","active AND parcela = '$parcela->id' AND status = '1'");
		foreach((array)$registros as $r){
			if($r->variable->tipoCampo->isFoto()){
				$path = __ROOT . $r->valor;
				if(file_exists($path)){
					$data = file_get_contents($path);
					$base64 = base64_encode($data);
					$r->valor = $base64;
				}else{
					$r->valor = "";
				}
			}
		}
		$registros_arr = array_merge($registros_arr, $registros);
	}
}
echo json_encode($registros_arr);