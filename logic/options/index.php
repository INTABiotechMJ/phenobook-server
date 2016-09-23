<?php
$admin = true;
require "../../files/php/config/require.php";
$id = _get("id");
$variable = Entity::load("Variable", $id);
$items = Entity::listMe("FieldOption","active AND Variable = '$id' ORDER BY id DESC");
$data = array();
$cont = 1;
foreach ($items as $key => $value) {

	$item = array();
	$cont++;
	$item["Id"] = $value->id;
	$item["Name"] = $value;
	$item["Actions"] = "<a href='edit.php?id=$value->id' class='btn btn-default btn-sm'>Edit</a> ";
	$item["Actions"] .= "<a data-href='delete.php?id=$value->id' class='btn btn-danger btn-sm ask' data-what=Are you sure?'>Delete</a> ";
	$data[] = $item;

}
echo "<div class='botonera'>";
echo btn("Back to variables", "../Variable/index.php?id=".$variable->variableGroup->id, null, TYPE_DEFAULT);
echo btn("Add option", "add.php?id=$id");
echo "</div>";
echo "<legend>Options of variable <span class='object-name'>$variable</span></legend>";

echo genTable($data);
require __ROOT."files/php/template/footer.php";
