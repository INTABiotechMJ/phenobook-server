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
	$importacion = Entity::search("Importacion","phenobook = '$value->id'");
	if($importacion){
		$download = "<a href='".__URL."$importacion->path' class='btn btn-sm btn-default' target='_blank'><i class='glyphicon glyphicon-download-alt'></i></a>";
	}else{
		$download =  "";
	}
	$item = array();
	$cont++;
	$item["Id"] = $value->id;
	$item["Name"] = $value;
	$item["File"] = $download;
	$item["Experimental Unit"] = $value->campo_numero;
	$item["Assigned to"] = !empty($value->userGroup)? $value->userGroup : $value->selectedUsers2String();
	$item["Status"] = $value->visible? "In course" : "Ended";

	$item["Actions"] = "<div class='nowrap'><a href='open.php?id_ensayo=$value->id' class='btn btn-primary btn-sm'>".__DATA."</a> ";
	$item["Actions"] .= "<a href='load.php?id_ensayo=$value->id' class='btn btn-primary btn-sm'>".__LOAD."</a> ";
	$item["Actions"] .= "<a href='../../Reportes/graphs.php?id_ensayo=$value->id' class='btn btn-primary btn-sm hide'>".__GRAPHS."</a> ";
	if($__user->isAdmin()){
		$item["Actions"] .= "<a href='../Variables/index.php?id_ensayo=$value->id' class='btn btn-default btn-sm'>Variables</a> ";
		$item["Actions"] .= "<a href='edit.php?id=$value->id' class='btn btn-default btn-sm'>" . __EDIT . "</a> ";
		if($value->visible){
			$item["Actions"] .= "<a data-href='".__URL."logic/phenobook/end.php?status=0&id=$value->id' class='btn btn-warning btn-sm ask' data-what='¿Seguro?'>" . __END . "</a> ";
		}else{
			$item["Actions"] .= "<a data-href='".__URL."logic/phenobook/end.php?status=1&id=$value->id' class='btn btn-success btn-sm ask' data-what='¿Seguro?'>" . __CONTINUE . "</a> ";
		}
		$item["Actions"] .= "<a data-href='".__URL."logic/phenobook/delete.php?id=$value->id' class='btn btn-danger btn-sm ask' data-what='¿Seguro?'>" . __DELETE . "</a></div> ";
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
