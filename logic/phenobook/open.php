<?php
require "../../../files/php/config/require.php";
$id_libroCampo = _get("id_ensayo");
$libroCampo = Entity::search("Phenobook","id = '$id_libroCampo' AND active");


$items = Entity::listMe("Parcela","active AND libroCampo = '$id_libroCampo' ORDER BY numero");
$data = array();
$cont = 1;

foreach ($items as $key => $value) {
	$c = $value;
	$item = array();
	$item["Parcela"] = $value->numero;

	$valueNotEmpty = false;

	$infoEnsayo = Entity::listMe("InfoEnsayo","active AND libroCampo = '".$c->libroCampo->id."'");
	foreach ((array) $infoEnsayo as $k2 => $v2) {
		$valorInfoEnsayo = Entity::search("ValorInfoEnsayo","active AND parcela = '$value->id' AND infoEnsayo = '$v2->id'");
		if($valorInfoEnsayo){
			$item[$valorInfoEnsayo->infoEnsayo->nombre] = "<div class='info-ensayo'>".$valorInfoEnsayo->valor."</div>";
			$valueNotEmpty = true;
		}else{
			$item[$v2->nombre] = "";
		}
	}

	$variables = Entity::listMe("Variable","active AND libroCampo = '".$c->libroCampo->id."'");
	foreach ((array) $variables as $k2 => $v2) {
		$reg = Entity::search("Registro","active AND parcela = '$value->id' AND variable = '$v2->id' AND status = '1' ORDER BY localStamp DESC");
		if(!$reg){
			$reg = Entity::search("Registro","active AND parcela = '$value->id' AND variable = '$v2->id' ORDER BY localStamp DESC");
		}
		if($reg){
			$item[$reg->variable->nombreOriginal] = "<div data-id='$reg->id'>".$reg->calcValor()."</div>";
			$valueNotEmpty = true;
		}else{
			$item[$v2->nombreOriginal] = "";
		}
	}

	if(true || $valueNotEmpty){
		$data[] = $item;
	}
}

echo "<div class='botonera'>";
echo btn(__BACK, "index.php", ICON_BACK, TYPE_DEFAULT);
echo "</div>";
echo "<legend>".__FIELDBOOK_CLASS_SHOW." <span class='object-name'>$libroCampo</span></legend>";
echo genTable($data, true,null, "small tdmark");
require __ROOT."files/php/template/footer.php";
?>
<script>
	$("td").click(function(){
		var $link = $(this).find("div");
		if($link.data("id")){
			tab("openReg.php?id_libroCampo=<?= $id_libroCampo ?>&id_registro=" + $link.data("id"));
		}
	});
</script>
