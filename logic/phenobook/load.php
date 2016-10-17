<?php
require "../../files/php/config/require.php";
$id = _get("id");
$phenobook = Entity::search("Phenobook","id = '$id' AND active");
$photo = Entity::search("FieldType","active AND type = '" . FieldType::$TYPE_PHOTO . "'");
$variables =  Entity::listMe("Variable","active AND fieldType != '$photo->id' AND id IN (SELECT variable FROM PhenobookVariable WHERE phenobook = '$phenobook->id')");

$data = array();
for ($i=1; $i <= $phenobook->experimental_units_number; $i++) {
	$row = array();
	foreach((array)$variables as $v){
		$reg = Entity::search("Registry","active AND phenobook = '$phenobook->id' AND status AND experimental_unit_number = '$i' AND variable = '$v->id' ORDER BY experimental_unit_number, id DESC");
		if($reg){
			switch ($v->fieldType->type) {
				case FieldType::$TYPE_CATEGORICAL:
				$option = Entity::search("Category","variable = '$v->id' AND id = '$reg->value'");
				if($option){
					$row["$v"] = $option->name;
				}
				break;
				case FieldType::$TYPE_BOOLEAN:
				$row["$v"] = $reg->value?"true":"false";
				break;
				default:
				$row["$v"] = $reg->value;
				break;
			}
		}else{
			if($v->fieldType->isBoolean()){
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
<div class="alert alert-info" style="margin-top:1em">
	<ul>
		<li>
			Photo variables are hidden in this section
		</li>
		<li>
			You can copy and paste from Excel like programs in above table
		</li>
	</ul>
</div>
<?php
require __ROOT."files/php/template/footer.php";
?>
<script>
var dataObject = <?= json_encode($data) ?>;

yellowRenderer = function(instance, td, row, col, prop, value, cellProperties) {
	Handsontable.renderers.TextRenderer.apply(this, arguments);
	td.style.backgroundColor = '#d9edf7';
};

var hotElement = document.querySelector('#hot');
var hotElementContainer = hotElement.parentNode;
var hotSettings = {
	data: dataObject,
	columns: [
		<?php
		$countInformative = 0;
		foreach ((array) $variables as $k2 => $v) {
			$renderer = "";
			if($v->isInformative){
				$renderer = "renderer: yellowRenderer,";
				$countInformative++;
			}
			$editor = "";
			#https://docs.handsontable.com/pro/1.7.0/tutorial-cell-types.html
			#'text'
			#'numeric'  format: '0.00%'  format: '0.00%'
			#'date' format 'MM/DD/YYYY'
			if($v->fieldType->isBoolean()){
				echo "{data:'$v',$renderer type:'checkbox'}, ";
			}
			#'date' format 'MM/DD/YYYY'
			if($v->fieldType->isDate()){
				echo "{data:'$v',$renderer width:110,type: 'date',dateFormat: 'YYYY-MM-DD',width:'auto'}, ";
			}

			if($v->fieldType->isNumeric()){
				echo "{data:'$v',$renderer type:'numeric',width:'auto'}, ";
			}
			if($v->fieldType->isText()){
				echo "{data:'$v',$renderer type:'text',width:'auto'}, ";
			}
			if($v->fieldType->isCategorical()){
				$options = $v->getCategories();
				echo "{data:'$v',$renderer
					type:'autocomplete',
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
		fixedColumnsLeft: <?= $countInformative ?>,
		manualColumnFreeze: true,
		manualRowResize: true,
		manualColumnResize: true,
		colHeaders: [
			<?php
			foreach ((array) $variables as $k => $v) {
				echo "'$v [".$v->fieldType->name."]',";
			}
			?>
		],
		afterChange: function (change, source) {

			if (source === 'loadData') {
				return; //don't save this change
			}
			this.validateCells( function (valid) {
				if (!valid) {
					$.bootstrapGrowl("Please correct format of red cells", {
						type: 'warning',
						align: 'left',
						delay: 1500,
					});
					return false;
				}
				$.ajax({
					method: "POST",
					url: "ajax/save_data.php",
					data: {
						data:JSON.stringify({change}),
						phenobook: "<?= $phenobook->id ?>",
					}
				})
				.done(function( msg ) {
					$.bootstrapGrowl("Data has been updated", {
						type: 'success',
						align: 'left',
						delay: 1500,
					});
				});
			});

		}
	};
	var hot = new Handsontable(hotElement, hotSettings);
	</script>
