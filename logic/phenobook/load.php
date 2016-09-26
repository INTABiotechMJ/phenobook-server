<?php
require "../../files/php/config/require.php";
$id = _get("id");
$phenobook = Entity::search("Phenobook","id = '$id' AND active");
$variableGroup = Entity::search("VariableGroup","active AND id = '" . $phenobook->variableGroup->id . "'");

$informative = Entity::search("FieldType","active AND type = '" . FieldType::$TYPE_INFORMATIVE . "'");
$photo = Entity::search("FieldType","active AND type = '" . FieldType::$TYPE_PHOTO . "'");

$variables = Entity::listMe("Variable","active AND fieldType != '$photo->id' AND variableGroup = '$variableGroup->id' ORDER BY field(fieldType, ".$informative->id.") DESC");
$data = array();
for ($i=1; $i <= $phenobook->experimental_units_number; $i++) {
	$row = array();
	foreach((array)$variables as $v){
		$reg = Entity::search("Registry","active AND experimental_unit_number = '$i' AND variable = '$v->id' ORDER BY experimental_unit_number");
		if($reg){
			if($v->fieldType->isCheck()){
				$row["$v"] = $reg->value?"true":"false";
			}else{
				$row["$v"] = $reg->value;
			}
		}else{
			if($v->fieldType->isCheck()){
				$row["$v"] = "false";
			}else{
				$row["$v"] = "";
			}
		}
	}
	$data[] = $row;
}
?>
<style media="screen">
.status{
	margin-top: 10px;
}
</style>
<link rel="stylesheet" type="text/css" href="<?= __URL ?>assets/libs/handsontable/handsontable.full.min.css">
<link rel="stylesheet" type="text/css" href="<?= __URL ?>assets/libs/handsontable/handsontable.full.min.css">
<script src="<?= __URL ?>assets/libs/handsontable/handsontable.full.min.js"></script>
<?php
echo "<div class='row'>";

echo "<div class='col-md-8'>";
echo "<legend>Load data to Phenobook <span class='object-name'>$phenobook</span></legend>";
echo "</div>";

echo "<div class='col-md-2'>";

echo "</div>";

echo "<div class='col-md-1'>";
echo "<a href='index.php' class='btn btn-default'>Back to phenobooks</a>";
echo "</div>";

echo "</div>";
echo "<div id='hot'></div>";
?>
<div class="status">
	ready
</div>
<div class="form-group">
	<hr>
	<input type="submit" name="save" value="Save" class="btn btn-primary">
	<hr>
	<span style="background-color:rgba(160, 204, 12, 0.14);color: black">Informative field</span> <br>
	Photo variables are hidden in this section <br>
</div>
<?php
require __ROOT."files/php/template/footer.php";
?>
<script>
var dataObject = <?= json_encode($data) ?>;

yellowRenderer = function(instance, td, row, col, prop, value, cellProperties) {
	Handsontable.renderers.TextRenderer.apply(this, arguments);
	td.style.backgroundColor = 'rgba(160, 204, 12, 0.14)';
};

var hotElement = document.querySelector('#hot');
var hotElementContainer = hotElement.parentNode;
var hotSettings = {
	data: dataObject,
	columns: [
		<?php
		foreach ((array) $variables as $k2 => $v) {
			$editor = "";
			#https://docs.handsontable.com/pro/1.7.0/tutorial-cell-types.html
			#'text'
			#'numeric'  format: '0.00%'  format: '0.00%'
			#'date' format 'MM/DD/YYYY'
			if($v->fieldType->isCheck()){
				echo "{data:'$v',type:'checkbox',allowInvalid: false,width:'auto'}, ";
			}
			#'date' format 'MM/DD/YYYY'
			if($v->fieldType->isDate()){
				echo "{data:'$v',type: 'date', allowInvalid: false,dateFormat: 'YYYY-MM-DD',width:'auto'}, ";
			}
			if($v->fieldType->isInformative()){
				echo "{data:'$v',renderer: yellowRenderer,allowInvalid: false,type:'text',width:'auto'}, ";
			}
			if($v->fieldType->isNumeric()){
				echo "{data:'$v',type:'numeric',allowInvalid: false,width:'auto'}, ";
			}
			if($v->fieldType->isText()){
				echo "{data:'$v',type:'text',allowInvalid: false,width:'auto'}, ";
			}
			if($v->fieldType->isOption()){
				$options = $v->getOptions();
				echo "{data:'$v',
					type:'autocomplete',
					allowInvalid: false,
					strict:'true',
					source: ['".implode($options,"','")."'],
					width:'auto',
					filter:false }, ";
				}
			}

			?>

		],
		stretchH: 'all',
		startRows: <?= $phenobook->experimental_units_number; ?>,
		rowHeaders: true,
		colHeaders: [
			<?php
			foreach ((array) $variables as $k => $v) {
				echo "'$v',";
			}
			?>
		],
		afterChange: function (change, source) {
			if (source === 'loadData') {
				return; //don't save this change
			}
			$(".status").html("saving...");
			console.log(change)
			$.ajax({
				method: "POST",
				url: "ajax/save_data.php",
				data: {
					data:JSON.stringify({change}),
					phenobook: "<?= $phenobook->id ?>",
				}
			})
			.done(function( msg ) {
				$(".status").html("saved");
			});
		}
	};
	var hot = new Handsontable(hotElement, hotSettings);
	</script>
