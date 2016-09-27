<?php
require "../../files/php/config/require.php";
$items = array();
if($__user->isAdmin()){
	$items = Entity::listMe("Phenobook","active ORDER BY id DESC");
}
if($__user->isOperador()){
	$UserPhenobooks = Entity::listMe("PhenobookUser","active AND user = '$__user->id' ORDER BY id DESC");
	$items = array();
	foreach((array)$UserPhenobooks as $ue){
		$phenos = Entity::listMe("Phenobook","active AND id = '" . $ue->phenobook->id . "' ORDER BY id DESC");
		$items = array_merge($items, $phenos);
	}
	$phenos = Entity::listMe("Phenobook","active AND userGroup = '" . $ue->group->id . "' ORDER BY id DESC");
	$items = array_merge($items, $phenos);
}

$data = array();
$cont = 1;
foreach ($items as $key => $value) {
	$assignedString = "";
	$up = Entity::listMe("PhenobookUser", "active AND phenobook = '$value->id'");
	if(!empty($up)){
		$assignedString = "Users: ";
		foreach ((array)$up as $upi) {
			$assignedString .= " $upi->user ";
		}
	}
	$gp = Entity::listMe("PhenobookUserGroup", "active AND phenobook = '$value->id'");
	if(!empty($gp)){
		$assignedString .= " - Groups: ";
		foreach ((array)$gp as $gpi) {
			$assignedString .= " $gpi->userGroup ";
		}
	}
	$item = array();
	$cont++;
	$item["Id"] = $value->id;
	$item["Name"] = $value;
	$item["Assigned to"] = $assignedString;
	$item["Variable Group"] = $value->variableGroup;
	$item["Status"] = $value->visible? "In course" : "Ended";

	$item["Actions"] = "<div class='nowrap'><a href='pheno_report.php?id=$value->id' class='btn btn-default btn-sm'>Inspect results</a> ";
	$item["Actions"] .= "<a href='load.php?id=$value->id' class='btn btn-default btn-sm'>Load manually</a> ";
	$item["Actions"] .= "<a data-href='".__URL."logic/phenobook/export.php?id=$value->id' class='btn btn-default btn-sm'>CSV</a> ";
	if($__user->isAdmin()){
		$item["Actions"] .= "<a href='edit.php?id=$value->id' class='btn btn-default btn-sm'>Edit</a> ";
		if($value->visible){
			$item["Actions"] .= "<a data-href='".__URL."logic/phenobook/end.php?status=0&id=$value->id' class='btn btn-warning btn-sm ask' data-what='Are you sure?'>End</a> ";
		}else{
			$item["Actions"] .= "<a data-href='".__URL."logic/phenobook/end.php?status=1&id=$value->id' class='btn btn-success btn-sm ask' data-what='Are you sure?'>Continue</a> ";
		}
		$item["Actions"] .= "<a data-href='".__URL."logic/phenobook/delete.php?id=$value->id' class='btn btn-danger btn-sm ask' data-what='Are you sure?'>Delete</a></div> ";
		$item["Actions"] .= "</div>";
	}
	$data[] = $item;

}

echo "<div class='row'>";

echo "<div class='col-md-8'>";
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
