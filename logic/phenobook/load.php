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
?>
<link rel="stylesheet" type="text/css" href="<?= __URL ?>assets/libs/handsontable/handsontable.full.min.css">
<link rel="stylesheet" type="text/css" href="<?= __URL ?>assets/libs/handsontable/handsontable.full.min.css">
<script src="<?= __URL ?>assets/libs/handsontable/handsontable.full.min.js"></script>
<?php
echo "<div class='botonera'>";
echo btn(__BACK, "index.php", ICON_BACK, TYPE_DEFAULT);
echo "</div>";
echo "<legend>".__FIELDBOOK_CLASS_SHOW." <span class='object-name'>$libroCampo</span></legend>";
echo "<div id='hot'></div>";
require __ROOT."files/php/template/footer.php";

$items = Entity::listMe("Parcela","active AND libroCampo = '$id_libroCampo' ORDER BY numero");
$data = array();
$cont = 1;
foreach ($items as $key => $value) {
	$infoEnsayo = Entity::listMe("InfoEnsayo","active AND libroCampo = '".$c->libroCampo->id."'");
	$i = array();
	foreach ((array) $infoEnsayo as $k2 => $v2) {
		$valorInfoEnsayo = Entity::search("ValorInfoEnsayo","active AND parcela = '$value->id' AND infoEnsayo = '$v2->id'");
		if($valorInfoEnsayo){
			$i[$valorInfoEnsayo->infoEnsayo->nombre] = $valorInfoEnsayo->valor;
			$valueNotEmpty = true;
		}else{
			$i[$v2->nombre] = "";
		}
	}


	$variables = Entity::listMe("Variable","active AND libroCampo = '".$c->libroCampo->id."'");
	foreach ((array) $variables as $k2 => $v2) {
		$i[$v2->nombreOriginal] = "";
	}

	$data[] = $i;
}
?>
<script>
var dataObject = <?= json_encode($data) ?>;

var hotElement = document.querySelector('#hot');
var hotElementContainer = hotElement.parentNode;
var hotSettings = {
	data: dataObject,
	columns: [
		<?php
		$infoEnsayo = Entity::listMe("InfoEnsayo","active AND libroCampo = '".$c->libroCampo->id."'");
		foreach ((array) $infoEnsayo as $k => $v) {
			echo "{ data:'$v', type:'numeric',editor:'false',width:'auto' }, ";
		}
		$variables = Entity::listMe("Variable","active AND libroCampo = '".$c->libroCampo->id."'");
		foreach ((array) $variables as $k2 => $v2) {
			#'text'
			#'numeric'  format: '0.00%'  format: '0.00%'
			#'date' format 'MM/DD/YYYY'
			if($v2->isTexto()){
				$editor = '';
			}
			echo "{ data:'$v2', type:'numeric',editor:'$editor',width:'auto' }, ";
		}

		?>

	],
	stretchH: 'all',
	width: 806,
	autoWrapRow: true,
	height: 441,
	maxRows: 22,
	rowHeaders: true,
	colHeaders: [
		<?php
		foreach ((array) $infoEnsayo as $k => $v) {
			echo "'$v',";
		}
		foreach ((array) $variables as $k2 => $v2) {
			echo "'$v2', ";
		}
		?>
	]
};
var hot = new Handsontable(hotElement, hotSettings);
</script>
