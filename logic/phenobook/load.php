<?php
require "../../files/php/config/require.php";
$id = _get("id");
$phenobook = Entity::search("Phenobook","id = '$id' AND active");
$variableGroup = Entity::search("VariableGroup","active AND id = '" . $phenobook->variableGroup->id . "'");
$variables = Entity::listMe("Variable","active AND variableGroup = '$variableGroup->id'");
$informativeVariables = array();
foreach((array)$variables as $var){
	if($var->fieldType->isInformative()){		
		$informativeVariables[] = $var;
	}
}
?>
<link rel="stylesheet" type="text/css" href="<?= __URL ?>assets/libs/handsontable/handsontable.full.min.css">
<link rel="stylesheet" type="text/css" href="<?= __URL ?>assets/libs/handsontable/handsontable.full.min.css">
<script src="<?= __URL ?>assets/libs/handsontable/handsontable.full.min.js"></script>
<?php
echo "<div class='botonera'>";
echo btn("Back to Phenobooks", "index.php", null);
echo "</div>";
echo "<legend>Load data for Phenobook <span class='object-name'>$phenobook</span></legend>";
echo "<div id='hot'></div>";
require __ROOT."files/php/template/footer.php";

?>
<script>
var dataObject = <?= json_encode(obj2arr($variables)) ?>;

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
