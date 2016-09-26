<?php
require "../../files/php/config/require.php";
$id = _get("id");
$phenobook = Entity::search("Phenobook","id = '$id' AND active");
$variableGroup = Entity::search("VariableGroup","active AND id = '" . $phenobook->variableGroup->id . "'");

$informative = Entity::search("FieldType","active AND type = '" . FieldType::$TYPE_INFORMATIVE . "'");

$variables = Entity::listMe("Variable","active AND variableGroup = '$variableGroup->id' ORDER BY field(fieldType, ".$informative->id.") DESC");
$data = array();

?>
<style media="screen">
.more {
	background-color: rgba(219, 133, 18, 0.1);
}
.has_val:hover{
	background-color: rgba(11, 245, 73, 0.27)!important;
	cursor: pointer;
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
<?php
$header = false;
echo "<div class='table-responsive '>";
echo "<table class='table table-hover table-stripped'>";
echo "<tr>";
echo "<th class='grey'>";
echo "EU";
echo "</th>";
foreach((array)$variables as $v){
	if($v->fieldType->isInformative()){
		echo "<th class=''>";
	}else{
		echo "<th>";
	}
	echo $v;
	echo "</th>";
}
echo "</tr>";
for ($i=1; $i <= $phenobook->experimental_units_number; $i++) {
	$row = array();
	$anyreg = false;
	foreach((array)$variables as $v){
		$anyreg = Entity::search("Registry","active AND status AND experimental_unit_number = '$i' AND variable = '$v->id' ORDER BY experimental_unit_number, id DESC");
		if($anyreg){
			break;
		}
	}
	if($anyreg){
		echo "<tr><td class='grey'>$i</td>";
	}else{
		continue;
	}
	foreach((array)$variables as $v){
		$class = "";
		$has_val = "";
		$more = "";
		$prev_reg = Entity::search("Registry","active AND NOT status AND experimental_unit_number = '$i' AND variable = '$v->id' ORDER BY experimental_unit_number, id DESC");
		$reg = Entity::search("Registry","active AND status AND experimental_unit_number = '$i' AND variable = '$v->id' ORDER BY experimental_unit_number, id DESC");
		if($v->fieldType->isInformative()){
			$class = "info";
		}
		if($reg){
			$has_val = "has_val";
			switch ($v->fieldType->type) {
				case FieldType::$TYPE_OPTION:
				$option = Entity::search("FieldOption","variable = '$v->id' AND id = '$reg->value'");
				$value = $option->name;
				break;
				case FieldType::$TYPE_CHECK:
				$value = $reg->value?"<span class='yes'>yes</i>":"";
				break;
				case FieldType::$TYPE_PHOTO:
				$value = $reg->calcPhoto();
				break;
				default:
				$value = $reg->value;
				break;
			}
			if($prev_reg){
				//more than one record
				$more = "more";
			}else{
				$more = "";
			}

		}else{
			if($v->fieldType->isCheck()){
				$value = "";
			}else{
				$value = "";
			}
		}
		echo "<td data-variable='$v->id' data-eu='$i' class='$has_val $class $more'>";
		echo $value;
		if($reg && $reg->fixed){
			echo "<i class='glyphicon glyphicon-pushpin'></i>";
		}
		echo "</td>";
	}
	if($anyreg){
		echo "</tr>";
	}
}
echo "</div>";
echo "</table>";
?>
<hr>
<span class="more" style="color: black">Has been modified more than once</span> <br>
Click on a cell to inspect information about each registry
<?php
require __ROOT."files/php/template/footer.php";
?>
<script>
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
			variable:g_variable,
			eu:g_eu,
		}
	})
	.done(function( msg ) {
		$(".modal").modal();
		$(".modal-body").html(msg);
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
			eu:g_eu,
		}
	})
	.done(function( msg ) {
		$(".modal").modal();
		$(".modal-body").html(msg);
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
			variable:g_variable,
			eu:g_eu,
		}
	})
	.done(function( msg ) {
		$(".modal").modal();
		$(".modal-body").html(msg);
	});
	return false;
});
var g_variable;
var g_eu;
$("td").click( function(e) {
	var variable = $(this).data("variable");
	var eu = $(this).data("eu");
	g_variable = variable;
	g_eu = eu;
	$.ajax({
		method: "POST",
		url: "ajax/inspect_cell.php",
		data: {
			variable:variable,
			eu:eu,
		}
	})
	.done(function( msg ) {
		$(".modal").modal();
		$(".modal-body").html(msg);
	});
});
</script>


<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Registry information</h4>
			</div>
			<div class="modal-body">

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
