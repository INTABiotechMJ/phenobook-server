<?php
require "../../files/php/config/require.php";
$variableGroups = obj2arr(Entity::listMe("VariableGroup","active"));
?>
<style media="screen">
.more {
	background-color: rgba(219, 133, 18, 0.05);
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
td{
	border: 1px solid rgb(173, 173, 173, 0.2)!important;
}
th{
	border: 1px solid rgb(173, 173, 172)!important;
}
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
				<label class="control-label" for="file">Select Variable Group <span class="red">*</span></label>
				<?php
				printSelect("variableGroup", _post("variableGroup"), $variableGroups, null, "select2 required variableGroup" ,null );
				?>
				<span class="help-block">
				</span>
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-group">
				<label class="control-label" for="phenobooks">Select Phenobooks <span class="red">*</span></label>
				<?php
				printSelect("phenobooks[]", _post("phenobooks"), null, null, "select2 required phenobooks select-multiple","multiple" );
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
<div class="table-container">

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
$(".variableGroup").trigger("change");
$("#search").click(function(){
	var ids = $(".phenobooks").val();
	var variableGroup = $(".variableGroup").val();
	$.ajax({
		method: "POST",
		url: "ajax/data_report.php",
		data: {
			ids:ids,
			variableGroup:variableGroup,
		}
	})
	.done(function(data) {
		$(".table-container").html(data);
	});
	return false;
});
</script>
