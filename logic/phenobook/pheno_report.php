<?php
require "../../files/php/config/require.php";
$id = _get("id");
$phenobook = Entity::search("Phenobook","id = '$id' AND active");
$variableGroup = Entity::search("VariableGroup","active AND id = '" . $phenobook->variableGroup->id . "'");

$informative = Entity::search("FieldType","active AND type = '" . FieldType::$TYPE_INFORMATIVE . "'");
$check = Entity::search("FieldType","active AND type = '" . FieldType::$TYPE_CHECK . "'");

$variables = Entity::listMe("Variable","active AND variableGroup = '$variableGroup->id' ORDER BY field(fieldType, ".$informative->id.") DESC");
$data = array();

//data
$creation_date = $phenobook->stamp;
$lastReg = Entity::search("Registry", "active AND phenobook = '$phenobook->id' ORDER BY stamp DESC");
if($lastReg){
	$last_update = $lastReg->stamp;
}else{
	$last_update = "Never";
}

$check_variables = Entity::listMe("Variable","active AND variableGroup = '$variableGroup->id' AND fieldType = '$check->id'");
$check_variables_count = count($check_variables);
$variables_to_fill = Entity::listMe("Variable","active AND variableGroup = '$variableGroup->id' AND fieldType != '$check->id' AND fieldType != '$informative->id'");
$informative_variables_count = count(Entity::listMe("Variable","active AND variableGroup = '$variableGroup->id' AND fieldType = '$informative->id'"));
$informative_cells_count = $phenobook->experimental_units_number * $informative_variables_count;
$variable_count = count($variables_to_fill);
$all_cell_count = count($variables) * $phenobook->experimental_units_number;
$cell_count = count($variables_to_fill) * $phenobook->experimental_units_number;

$completed_cells = 0;
$reg = Entity::listMe("Registry","phenobook = '$phenobook->id' AND active AND status");
$informative_cells_filled = 0;
foreach ((array)$reg as $value) {
	if($value->variable->fieldType->isInformative()){
		$informative_cells_filled++;
	}
	if($value->variable->fieldType->isInformative() or $value->variable->fieldType->isCheck()){
		continue;
	}
	$completed_cells++;
}

$variables_to_fill_no_check = Entity::listMe("Variable","active AND variableGroup = '$variableGroup->id' AND fieldType != '$informative->id' AND fieldType != '$check->id'");
$completed_percentage = number_format($completed_cells * 100 / (count($variables_to_fill_no_check) * $phenobook->experimental_units_number),2);
?>
<style media="screen">
.more {
	background-color: rgba(219, 133, 18, 0.05);
}
.has_val:hover{
	background-color: rgba(11, 245, 73, 0.27)!important;
	cursor: pointer;
}
td{
	border: 1px solid rgb(173, 173, 173, 0.2)!important;
}
th.summary a{
	cursor: pointer;
	text-decoration: underline;
	color: rgb(37, 153, 161, 0.7);
}
th{
	border: 1px solid rgb(173, 173, 172)!important;
}
.yes{
	color:rgba(13, 172, 4, 0.85);
}
.no{
	color:rgba(226, 34, 15, 0.78);
}
.grey{
	background-color: rgba(221, 221, 221, 0.37);
}
</style>
<div class='row'>
	<div class='col-md-8'>
		<legend>Results for <span class='object-name'><?= $phenobook ?></span></legend>
	</div>
	<div class='col-md-2'>
	</div>
	<div class='col-md-1'>
		<a href='index.php' class='btn btn-default'>Back to phenobooks</a>
	</div>
</div>
<div class="table-container">

</div>
<hr>
<div class="alert alert-info">
	<ul>
		<li>
			Cells modified more than once are <span class="more">highlighted</span><br>
		</li>
		<li>
			Click on a cell to inspect information about each registry, replace for previous values or fix cells <br>
		</li>
		<li>
			Fixed values <i class='glyphicon glyphicon-pushpin'></i> (not allowed to be modified on mobile)
		</li>
		<li>
			Click on the variable name to see a summary
		</li>
	</ul>
</div>
<div class="info table">
	<h4>Phenobook summary</h4>
	<ul>
		<li>
			<b>Creation date:</b> <?= $creation_date ?>
		</li>
		<li>
			<b>Last update:</b> <?= $last_update ?>
		</li>
		<li>
			<b>Informative variable count:</b> <?= $informative_variables_count ?>
		</li>
		<li>
			<b>Variable count:</b> <?= $variable_count + $check_variables_count ?> <span class="downlight">(Informative variables do not count)</span>
		</li>
		<li>
			<b>Informative cells count:</b> <?= $informative_cells_count."  <span class='downlight'>(filled: ".$informative_cells_filled ?>)</span>
		</li>
		<li>
			<b>Variable cells count:</b> <?= $cell_count ?> <span class="downlight">(Check and informative variables do not count)</span>
		</li>
		<li>
			<b>Completed cells:</b> <?= $completed_cells ?>  <span class="downlight">(Check and informative variables do not count)</span>
		</li>
		<li>
			<b>Completed:</b> <?= $completed_percentage ?> % <span class="downlight">(Check and informative variables do not count)</span>
		</li>
	</ul>
</div>
<?php
require __ROOT."files/php/template/footer.php";
?>
<script>
function reload_table(){
	$.ajax({
		method: "POST",
		url: "ajax/pheno_report.php",
		data: {
			id:<?= _request("id") ?>,
		}
	})
	.done(function( msg ) {
		$(".table-container").html(msg);
	});
}
reload_table();

$("body").on("click",".unfix",function(){

	var id = $(this).data("id");
	$.bootstrapGrowl("Registry has been unfixed and it is allowed to overwrite", {
		type: 'success',
	});
	$.ajax({
		method: "POST",
		url: "ajax/inspect_cell.php",
		data: {
			unfix_registry:id,
			phenobook:<?= $phenobook->id ?>,
			variable:g_variable,
			eu:g_eu,
		}
	})
	.done(function( msg ) {
		$(".modal").modal();
		$(".modal-body").html(msg);
		reload_table();
	});
	return false;
});

$("body").on("click",".fix",function(){

	var id = $(this).data("id");
	$.bootstrapGrowl("Registry has been fixed and it is not allowed to overwrite", {
		type: 'success',
	});
	$.ajax({
		method: "POST",
		url: "ajax/inspect_cell.php",
		data: {
			fix_registry:id,
			variable:g_variable,
			phenobook:<?= $phenobook->id ?>,
			eu:g_eu,
		}
	})
	.done(function( msg ) {
		$(".modal").modal();
		$(".modal-body").html(msg);
		reload_table();
	});
	return false;
});
$("body").on("click",".replace-value",function(){
	var id = $(this).data("id");
	$.bootstrapGrowl("Current value has been replaced", {
		type: 'success',
	});
	$.ajax({
		method: "POST",
		url: "ajax/inspect_cell.php",
		data: {
			change_registry:id,
			phenobook:<?= $phenobook->id ?>,
			variable:g_variable,
			eu:g_eu,
		}
	})
	.done(function( msg ) {
		$(".modal").modal();
		$(".modal-body").html(msg);
		reload_table();
	});
	return false;
});
var g_variable;
var g_eu;
$("body").on("click","td",function(){
	var variable = $(this).data("variable");
	var eu = $(this).data("eu");
	g_variable = variable;
	g_eu = eu;
	$.ajax({
		method: "POST",
		url: "ajax/inspect_cell.php",
		data: {
			phenobook:<?= $phenobook->id ?>,
			variable:variable,
			eu:eu,
		}
	})
	.done(function( msg ) {
		$(".modal").modal();
		$(".modal-body").html(msg);
	});
});
$("body").on("click",".summary a",function(){
	var id_variable = $(this).data("id_variable");
	var id_phenobook = $(this).data("id_phenobook");
	$.ajax({
		method: "POST",
		url: "ajax/variable_summary.php",
		data: {
			id_phenobook:id_phenobook,
			id_variable:id_variable,
		}
	})
	.done(function( msg ) {
		$(".modal").modal();
		$(".modal-body").html(msg);
	});
	return false;
});
</script>


<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Information</h4>
			</div>
			<div class="modal-body">

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
