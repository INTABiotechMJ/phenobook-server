<?php 
$admin = true;
require "../../../files/php/config/require.php";
$id_variable = _get("id_variable");
$variable = Entity::load("Variable", $id_variable);
$items = Entity::listMe("Opcion","active AND variable = '$id_variable' ORDER BY id DESC");
$data = array();
$cont = 1;
foreach ($items as $key => $value) {

	$item = array();
	$cont++;
	$item["Id"] = $value->id;
	$item[__OPTIONS_CRUD_TABLE_NAME] = $value;
	$item[__OPTIONS_CRUD_TABLE_ACTIONS] = "<a data-href='".__URL."logic/admin/Opciones/delete.php?id=$value->id' class='btn btn-danger btn-sm ask' data-what='¿Seguro?'>" . __DELETE . "</a> ";
	$data[] = $item;

}
echo "<div class='botonera'>";
echo btn(__BACK, "../Variables/index.php?id_ensayo=".$variable->libroCampo->id, ICON_BACK, TYPE_DEFAULT);
echo btn(__ADD, "add.php?id_variable=$id_variable", ICON_ADD);
echo "</div>";
echo "<legend>".__OPTIONS_CRUD_TITLE." <span class='object-name'>$variable</span></legend>";

echo genTable($data);
require __ROOT."files/php/template/footer.php";