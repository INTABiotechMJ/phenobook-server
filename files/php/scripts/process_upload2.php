<?php 
$noLogin = true;
$noMenu = true;
$noHeader = true;
require "../../../files/php/config/require.php";
$folder = __ROOT . 'files/uploads/ftp/';
$files_names = glob($folder.'*.dat');
Entity::begin();
foreach((array) $files_names as $filename){
	$handle = fopen($filename, "r");
	$content = fread($handle, filesize($filename));
	$lines = explode("\n", $content);
	foreach((array)$lines as $line){
		if(trim($line) == ""){
			continue;
		}
		parse_str($line, $get_array);
		$stamp = $get_array["stamp"];

		$id_ensayo = $get_array["id_ensayo"];
		$ensayo = Entity::load("Ensayo",$id_ensayo);

		$control = new Control();
		$control->stamp = stamp();
		$control->localStamp = $stamp;
		$control->ensayo = $ensayo;
		Entity::save($control);

		foreach((array) $get_array as $k => $v){
			if($k == "stamp" || $k == "id_ensayo"){
				continue;
			}
			$data_arr = explode("_", $k);

			$id_parcela = $data_arr[1];
			$id_variable = $data_arr[2];

			$parcela = Entity::load("Parcela", $id_parcela);
			$variable = Entity::load("Variable", $id_variable);

			$dc = new DetalleControl();
			$dc->localStamp = $stamp;
			$dc->control = $control;
			$dc->parcela = $parcela;
			$dc->variable = $variable;
			$dc->valor = $v;

			//UPLOAD FOTO
			if($variable->tipoCampo->isFoto()){
				$photo_path = $folder . $v;


				if(file_exists($photo_path)){

					$dir_rel = "files/uploads/" . date("Y") . "/". date("m") ."/";
					$dir = __ROOT.$dir_rel;
					if (!file_exists($dir)) {
						@mkdir($dir, 0777, true);
					}
					$new_name = time() . rand(0, 1000) .".jpg";
					$full_new_path = $dir_rel . $new_name;
					rename($folder . $v, $full_new_path);
					$dc->valor = $full_new_path;
				}

			}
			//END UPLOAD FOTO

			Entity::save($dc);

		}
	}
}
Entity::commit();
echo "OK";