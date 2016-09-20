<?php
$admin = true;
require "../../files/php/config/require.php";
$id_ensayo = _get("id_ensayo");
$ensayo = Entity::search("Phenobook","id = '$id_ensayo' AND active");

$id_registro = _get("id_registro");
$registro = Entity::search("Registro","id = '$id_registro' AND active");

$regActual = Entity::search("Registro","active AND parcela = '".$registro->parcela->id."' AND variable = '" . $registro->variable->id . "' ORDER BY localStamp DESC");

echo "<div class='botonera'>";
echo "<a href='index.php?id_ensayo=$id_ensayo' class='btn hide btn-primary btn-sm btn-shadow'>".__BACK."</a>";
echo "</div>";
echo "<legend>" . i($regActual->parcela) . " | " . i($regActual->variable) . " | " . i($regActual->variable->libroCampo) . "</legend>";
echo reg2table($regActual);
echo "<hr>";

$histRegistros = Entity::listMe("Registro","active AND parcela = '".$regActual->parcela->id."' AND id != '$regActual->id' AND variable = '" . $regActual->variable->id . "' ORDER BY localStamp DESC");
if($histRegistros){
	echo "<a class='btn btn-default' id='toggleHistoricos'>".__RECORDS."</a>";
	echo "<hr>";
	echo "<div class='historicos-container' style='display:none;''>";
	echo "<legend>".__RECORDS."</legend>";
	foreach((array)$histRegistros as $r){
		echo reg2table($r);
	}
	echo "</div>";
}else{
	echo __NO_MODIFICATIONS_RECORDS;
}


require __ROOT."files/php/template/footer.php";


function reg2table($registro){
	$data = array();

	$item = array();
	$item["Data"] = __USER;
	$item["Value"] = $registro->user;
	$data[] = $item;

	$item = array();
	$item["Data"] = "Variable";
	$item["Value"] = $registro->variable;
	$data[] = $item;

	$item = array();
	$item["Data"] = __EXP_UNIT;
	$item["Value"] = $registro->parcela;
	$data[] = $item;

	$item = array();
	$item["Data"] = "On mobile";
	$item["Value"] = timeStampHumano($registro->localStamp);
	$data[] = $item;

	$item = array();
	$item["Data"] = __UPLOADED_DATE_SERVER;
	$item["Value"] = timeStampHumano($registro->stamp);
	$data[] = $item;


	$item = array();
	$item["Data"] = "Value";
	$item["Value"] = $registro->calcValor();
	$data[] = $item;

	if($registro->variable->tipoCampo->isFoto()){
		$item = array();
		$item["Data"] = __OPEN_PHOTO;
		if($registro->existeFoto()){
			$item["Value"] = "<a href='".$registro->calcLinkFoto()."' class='link-foto'><i class='glyphicon glyphicon-eye-open'></i>";
		}else{
			$item["Value"] = "[X]";
		}
		$data[] = $item;

		$item = array();
		$item["Data"] = __DOWNLOAD_PHOTO;
		if($registro->existeFoto()){
			$item["Value"] = "<a download href='".$registro->calcLinkFoto()."'><i class='glyphicon glyphicon-download'></i>";
		}else{
			$item["Value"] = "[X]";
		}
		$data[] = $item;
	}

	$item = array();
	$item["Data"] = "Latitude";
	$item["Value"] = $registro->latitude;
	$data[] = $item;

	$item = array();
	$item["Data"] = "Longitude";
	$item["Value"] = $registro->longitude;
	$data[] = $item;

	return genTable($data, false, false, "table-thin");
}
?>
<script type="text/javascript" src="<?= __URL; ?>assets/libs/abigimage.jquery.min.js"></script>
<script>
	$("#toggleHistoricos").click(function(){
		$(".historicos-container").toggle();
	});
	$(".link-foto").abigimage();
</script>
