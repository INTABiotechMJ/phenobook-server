<?php
$admin = true;
require "../../../files/php/config/require.php";
$items = array();
if($__user->isSuperAdmin()){
	$items = Entity::listMe("User","active");
}
if($__user->isAdmin() && isset($__user->userGroup->id)){
	$items = Entity::listMe("User","active AND grupo = '" . $__user->userGroup->id . "' AND (type = '".User::$TYPE_ADMIN."' OR type = '".User::$TYPE_OPERADOR."')");
}
$data = array();
$cont = 1;
foreach ($items as $key => $value) {
	$item = array();
	$item["#"] = $cont++;
	$item[__USER_CRUD_TABLE_NAME] = $value;
	$item[__USER_CRUD_TABLE_EMAIL] = $value->email;
	$item[__USER_CRUD_TABLE_TYPE] = $value->calcTypeName();

	$item[__USER_CRUD_TABLE_GROUP] = $value->grupo;

	if($value->id == $__user->id){
		$item[__USER_CRUD_TABLE_ACTIONS] = "";
		$item[__USER_CRUD_TABLE_ACTIONS] .= "";
	}else{

		$item[__USER_CRUD_TABLE_ACTIONS] = "<a href='edit.php?id=$value->id' class='btn btn-default btn-sm'>" . __EDIT . "</a>";
		$item[__USER_CRUD_TABLE_ACTIONS] .= "<a data-href='delete.php?id=$value->id' class='btn btn-default btn-sm ask' data-what='¿Esta seguro?'>" . __DELETE . "</a>";
	}
	$data[] = $item;

}
echo "<div class='botonera'>";
echo "<a href='add.php' class='btn btn-primary btn-sm btn-shadow'>".__USER_CRUD_ADD."</a>";
echo "</div>";

echo "<legend>".__USER_CRUD_TITLE."</legend>";
echo genTable($data);
require __ROOT."files/php/template/footer.php";
?>
