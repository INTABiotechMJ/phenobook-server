<?php
require "../../files/php/config/require.php";
$id = _get("id");
$phenobook = Entity::search("Phenobook","id = '$id' AND active");
if($phenobook->userGroup->id != $__user->userGroup->id){
	raise404();
}
$variables = $phenobook->searchVariables();
$data = array();
if(empty($variables)){
	redirect("index.php?m=Selected Phenobook has no variables");
}
//data
$creation_date = $phenobook->stamp;
$lastReg = Entity::search("Registry", "active AND phenobook = '$phenobook->id' ORDER BY stamp DESC");
if($lastReg){
	$last_update = $lastReg->stamp;
}else{
	$last_update = "Never";
}


$variables_boolean = count($phenobook->searchNonInformativeVariables(FieldType::$TYPE_BOOLEAN));
$variables_to_fill = $phenobook->searchNonInformativeVariables();
$informative_variables_count = count($phenobook->searchInformativeVariables());
$informative_cells_count = $phenobook->experimental_units_number * $informative_variables_count;
$variable_count = count($variables_to_fill) - $variables_boolean;
$all_cell_count = count($variables) * $phenobook->experimental_units_number;
$cell_count = count($variables_to_fill) * $phenobook->experimental_units_number;
$completed_cells = 0;
$reg = Entity::listMe("Registry","phenobook = '$phenobook->id' AND active AND status");
$informative_cells_filled = 0;
foreach ((array)$reg as $value) {
	if(empty($value->value)){
		continue;
	}
	if($value->variable->isInformative){
		$informative_cells_filled++;
		continue;
	}
	if($value->variable->isInformative or $value->variable->fieldType->isBoolean()){
		continue;
	}
	$completed_cells++;
}
if($variable_count > 0){
	$completed_percentage = number_format($completed_cells * 100 / ($variable_count * $phenobook->experimental_units_number),2);
}


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
	<div class='col-md-8 col-xs-6'>
		<legend>Results for <span class='object-name'><?= $phenobook ?></span></legend>
	</div>
	<div class='col-md-1'>
	</div>
	<div class='col-md-2'>
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
			Fixed values are marked with <i class='glyphicon glyphicon-pushpin'></i> (not allowed to be modified on mobile)
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
		<li class="hide">
			<b>Informative variable count:</b> <?= $informative_variables_count ?>
		</li>
		<li class="hide">
			<b>Variable count:</b> <?= $variable_count + $variables_boolean ?> <span class="downlight">(Informative variables do not count)</span>
		</li>
		<li class="hide">
			<b>Informative cells count:</b> <?= $informative_cells_count."  <span class='downlight'>(filled: ".$informative_cells_filled ?>)</span>
		</li>
		<li class="hide">
			<b>Variable cells count:</b> <?= $cell_count ?> <span class="downlight">(Check and informative variables do not count)</span>
		</li>
		<li>
			<b>Completed cells:</b> <?= $completed_cells ?> of <?= $cell_count ?> (<?= $completed_percentage ?>%)  <span class="downlight">(Check and informative variables do not count)</span>
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
		url: "ajax/data_report.php",
		data: {
			phenobook:<?= _request("id") ?>,
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
	$.bootstrapGrowl("Registry has been fixed and it is not allowed to be overwritten on mobile", {
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
$("body").on("click","td.has_val",function(){
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
	var id_phenobook = $(this).data("id_phenobooks");
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
