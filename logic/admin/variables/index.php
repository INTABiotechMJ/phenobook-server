<?php 
$admin = true;
require "../../../files/php/config/require.php";
$id_libroCampo = _get("id_ensayo");
$libroCampo = Entity::search("Phenobook","id = '$id_libroCampo' AND active");
$items = Entity::listMe("Variable","active AND libroCampo = '$id_libroCampo' ORDER BY id DESC");
$data = array();
$cont = 1;
foreach ($items as $key => $value) {

	$item = array();
	$cont++;
	$item["Id"] = $value->id;
	$item[__NAME] = $value;
	$item[__TYPE] = $value->tipoCampo;
	if($value->tipoCampo->isOpcion())	{
		$item[__ACTIONS] = "<a href='../Opciones/index.php?id_variable=$value->id' class='btn btn-default btn-sm'>" . __OPTIONS . "</a> ";
	}else{
		
		$item[__ACTIONS] = "";
	}
	$data[] = $item;

}

$items = Entity::listMe("InfoEnsayo","active AND libroCampo = '$id_libroCampo' ORDER BY id DESC");
foreach ($items as $key => $value) {

	$item = array();
	$cont++;
	$item["Id"] = $value->id;
	$item[__VARIABLE_CRUD_TABLE_NAME] = $value;
	$item[__VARIABLE_CRUD_TABLE_TYPE] = "Informative";	
	$item[__VARIABLE_CRUD_TABLE_ACTIONS] = "";
	
	$data[] = $item;

}
echo "<div class='botonera'>";
echo btn(__BACK, "../Phenobook/index.php", ICON_BACK, TYPE_DEFAULT);
echo "</div>";
echo "<legend>".__VARIABLE_CRUD_TITLE." <span class='object-name'>$libroCampo</span></legend>";
echo genTable($data);
require __ROOT."files/php/template/footer.php";