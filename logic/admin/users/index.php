<?php
$admin = true;
require "../../../files/php/config/require.php";
$items = array();

$items = Entity::listMe("User","active");

$data = array();
$cont = 1;
foreach ($items as $key => $value) {
	$item = array();
	$item["#"] = $cont++;
	$item["Name"] = $value;
	$item["Email"] = $value->email;
	$item["Type"] = $value->calcTypeName();
	$groups = $value->getUserGroups();
	if(!$groups){
		$groups = array();
	}else{
		$groups = obj2arr($groups);
	}
	$item["Groups"] = implode(",",$groups);

	if($value->id == $__user->id){
		$item["Actions"] = "";
		$item["Actions"] .= "";
	}else{

		$item["Actions"] = "<a href='edit.php?id=$value->id' class='btn btn-default btn-sm'>Edit</a>";
		$item["Actions"] .= "<a data-href='delete.php?id=$value->id' class='btn btn-danger btn-sm ask' data-what='Are you sure?'>Delete</a>";
	}
	$data[] = $item;

}

echo "<div class='row'>";

echo "<div class='col-md-8'>";
echo "<legend>Users</legend>";
echo "</div>";

echo "<div class='col-md-3'>";

echo "</div>";


echo "<div class='col-md-1'>";
echo "<a href='add.php' class='btn btn-primary'>Add</a>";
echo "</div>";

echo "</div>";

echo genTable($data,true,"table");
require __ROOT."files/php/template/footer.php";
?>
