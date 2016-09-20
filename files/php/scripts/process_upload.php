<?php 
$noLogin = true;
$noMenu = true;
$noHeader = true;
require "../../../files/php/config/require.php";
$folder = __ROOT . 'files/uploads/ftp/';
$filename = _get("file");
$filename = $folder.$filename;

$id_user = _get("id_user");
$user = Entity::load("User", $id_user);
Entity::begin();
if(!file_exists($filename)){
	die("OK[1]");
}

$handle = fopen($filename, "r");
$content = fread($handle, filesize($filename));
$lines = explode("\n", $content);
foreach((array)$lines as $line){
	if(trim($line) == ""){
		continue;
	}
	parse_str($line, $get_array);
	$stamp = $get_array["stamp"];

	$id_user = $get_array["id_user"];
	$curr_user = Entity::load("User", $id_user);

	$keys = array_keys($get_array);
	$v = $get_array[$keys[2]];
	$data = $keys[2];

	$data_arr = explode("_", $data);
	if(!isset($data_arr[1]) || !isset($data_arr[2]) || !isset($data_arr[3])){
		die("ERROR[error de formato]");
	}
	$id_ensayo = $data_arr[1];
	$id_parcela = $data_arr[2];

	$id_variable = $data_arr[3];

	
	$variable = Entity::load("Variable", $id_variable);
	$parcela = Entity::load("Parcela", $id_parcela);
	
	if(!$variable || !$parcela){
		continue;
	}


	$control = new Registro();
	$control->stamp = stamp();
	$control->user = $curr_user;
	$control->localStamp = $stamp;
	$control->parcela = $parcela;
	$control->variable = $variable;
	$control->valor = $v;
	Entity::save($control);
	

/**	if($variable->tipoCampo->isFoto()){
		if(!file_exists($photo_path) || !is_file($photo_path)){
			$control->valor = "";
		}
	}**/

	$photo_path = $folder . $v;
	if(file_exists($photo_path) && is_file($photo_path)){

		$dir_rel = "files/uploads/" . date("Y") . "/". date("m") ."/";
		$dir = __ROOT.$dir_rel;
		if (!file_exists($dir)) {
			@mkdir($dir, 0777, true);
		}
		$new_name = time() . rand(0, 1000) .".jpg";
		$full_new_path = $dir . $new_name;
		rename($folder . $v, $full_new_path);
		$control->valor = $dir_rel . $new_name;;
		$control->active = 1;
		Entity::update($control);
	}

}
//END UPLOAD FOTO

Entity::commit();
echo "OK[2]";