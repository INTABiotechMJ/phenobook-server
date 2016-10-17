<?php
require "../../files/php/config/require.php";
$variables = obj2arr(Entity::listMe("Variable","active AND userGroup = '".$__user->userGroup->id."'"));
$phenobooks = obj2arr(Entity::listMe("Phenobook","active AND userGroup = '".$__user->userGroup->id."'"));
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
</style>
<div class='row'>
	<div class='col-md-8'>
	</div>
	<div class='col-md-2'>
	</div>
	<div class='col-md-1'>

	</div>
</div>
<div class=" row">
	<div class="col-md-offset-1 col-md-8">
		<legend>Data report</legend>
	</div>
</div>
<form class="valid" action="data_csv.php" method="post">
	<div class="filters row">
		<div class="col-md-offset-1 col-md-3">
			<div class="form-group">
				<label class="control-label" for="file">Select Variables <span class="red">*</span></label>
				<?php
				printSelect("variables[]", _post("variables"), $variables, null, "select2 required multiple variables" ,"multiple");
				?>
				<span class="help-block">
				</span>
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-group">
				<label class="control-label" for="phenobooks">Select Phenobooks <span class="red">*</span></label>
				<?php
				printSelect("phenobooks[]", _post("phenobooks"), $phenobooks, null, "select2 required phenobooks multiple","multiple" );
				?>
				<span class="help-block">
				</span>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-offset-1 col-md-3">
			<input type="submit" name="submit" value="Search" id="search" class="btn btn-primary">
			<input type="submit" name="download" value="Download CSV" id="download" class="btn btn-primary">
			<hr>
			<span class="red">*</span> denotes a required field
		</div>
	</div>
</form>
<div class="row">
	<div class="col-md-offset-1 col-md-9" style="margin-top:1em;">
		<div class="alert alert-info">
			Select one variable group and then select phenobooks
			you want in the report (only phenobooks with selected
			variable group will be available)
		</div>
	</div>
</div>
<?php

?>
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
<?php
if($_POST){
	?>
	<hr>
	<span class="more" style="color: black">Has been modified more than once</span> <br>
	<?php
}
?>
<?php
require __ROOT."files/php/template/footer.php";
?>
<script>

$("body").on("change",".variableGroup",function(){
	var $pheno = $(".phenobooks");
	$pheno.select2('val', '')
	var id = $(this).val();
	$.ajax({
		method: "POST",
		url: "ajax/search_phenobook.php",
		data: {
			variableGroup:id,
		}
	})
	.done(function(data) {
		$pheno.empty();
		$.each(JSON.parse(data), function(value,key) {
			$pheno.append($("<option></option>")
			.attr("value", value).text(key));
		});
	});
	return false;
});

$(".variables").trigger("change");

$("#search").click(function(){
	reload_table();
	return false;
});

function reload_table(){
	if(!$(".valid").valid()){
		return;
	}
	var ids = $(".phenobooks").val();
	var variables = $(".variables").val();
	$.ajax({
		method: "POST",
		url: "ajax/data_report.php",
		data: {
			phenobooks:ids,
			variables:variables,
		}
	})
	.done(function(data) {
		$(".table-container").html(data);
	});
	return false;
}



$("body").on("click",".unfix",function(){
	var id = $(this).data("id");
	var phenobook = $(this).data("id_phenobook");
	$.bootstrapGrowl("Registry has been unfixed and it is allowed to overwrite", {
		type: 'success',
	});
	$.ajax({
		method: "POST",
		url: "ajax/inspect_cell.php",
		data: {
			unfix_registry:id,
			variable:g_variable,
			phenobook:phenobook,
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
	var phenobook = $(this).data("id_phenobook");
	$.bootstrapGrowl("Registry has been fixed and it is not allowed to be overwritten on mobile", {
		type: 'success',
	});
	$.ajax({
		method: "POST",
		url: "ajax/inspect_cell.php",
		data: {
			fix_registry:id,
			phenobook:phenobook,
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
$("body").on("click",".replace-value",function(){
	var id = $(this).data("id");
	var id_phenobook = $(this).data("id_phenobook");
	$.bootstrapGrowl("Current value has been replaced", {
		type: 'success',
	});
	$.ajax({
		method: "POST",
		url: "ajax/inspect_cell.php",
		data: {
			change_registry:id,
			variable:g_variable,
			eu:g_eu,
			id_phenobook:id_phenobook,
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
	var id_phenobook = $(this).data("id_phenobook");
	g_variable = variable;
	g_eu = eu;
	$.ajax({
		method: "POST",
		url: "ajax/inspect_cell.php",
		data: {
			variable:variable,
			phenobook:id_phenobook,
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
	var id_phenobooks = $(this).data("id_phenobooks");
	$.ajax({
		method: "POST",
		url: "ajax/variable_summary.php",
		data: {
			id_phenobooks:id_phenobooks,
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
