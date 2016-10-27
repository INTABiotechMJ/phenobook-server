<?php
require "../../files/php/config/require.php";
$items = Entity::listMe("Phenobook","active AND userGroup = '" . $__user->userGroup->id . "' ORDER BY id DESC");
$data = array();
$cont = 1;
foreach ($items as $key => $value) {

	$item = array();
	$cont++;
	$item["Id"] = $value->id;
	$item["Name"] = $value;
	$item["Group"] = $value->userGroup;
	$item["Status"] = $value->visible? "In course" : "Ended";

	$item["Actions"] = "<div class='nowrap'><a href='pheno_report.php?id=$value->id' class='btn btn-default btn-sm'>Inspect results</a> ";
	$item["Actions"] .= "<a href='load.php?id=$value->id' class='btn btn-default btn-sm'>Load data manually</a> ";
	$item["Actions"] .= "<a href='".__URL."logic/phenobook/data_csv.php?phenobook=$value->id' class='btn btn-default btn-sm'>CSV export</a> ";
	$item["Actions"] .= "<a href='edit.php?id=$value->id' class='btn btn-default btn-sm'>Edit</a> ";
	if($value->visible){
		$item["Actions"] .= "<a data-href='".__URL."logic/phenobook/end.php?status=0&id=$value->id' class='btn btn-warning btn-sm ask' data-what='Are you sure?'>End</a> ";
	}else{
		$item["Actions"] .= "<a data-href='".__URL."logic/phenobook/end.php?status=1&id=$value->id' class='btn btn-success btn-sm ask' data-what='Are you sure?'>Continue</a> ";
	}
	if($__user->isAdmin || $__user->isSuperAdmin){
		$item["Actions"] .= "<a data-href='".__URL."logic/phenobook/delete.php?id=$value->id' class='btn btn-danger btn-sm ask' data-what='Are you sure?'>Delete</a></div> ";
	}
	$item["Actions"] .= "</div>";
	$data[] = $item;

}

echo "<div class='row'>";

echo "<div class='col-md-8 col-xs-6'>";
echo "<legend>Phenobooks</legend>";
echo "</div>";

echo "<div class='col-md-3'>";

echo "</div>";

echo "<div class='col-md-1'>";
echo "<a href='add.php' class='btn btn-primary'>Add</a>";
echo "</div>";

echo "</div>";

echo genTable($data, "small table");
require __ROOT."files/php/template/footer.php";
?>
