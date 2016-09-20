<?php
require "../../../files/php/config/require.php";
$items = array();
if($__user->isAdmin() || $__user->isSuperAdmin()){
	$items = Entity::listMe("Phenobook","active AND grupo = '".$__user->userGroup->id."' ORDER BY id DESC");
}
if($__user->isOperador()){
	$UserPhenobooks = Entity::listMe("UserPhenobook","active AND user = '$__user->id' ORDER BY id DESC");
	$items = array();
	foreach((array)$UserPhenobooks as $ue){
		$ensayos = Entity::listMe("Phenobook","active AND id = '" . $ue->ensayo->id . "' ORDER BY id DESC");
		$items = array_merge($items, $ensayos);
	}
}

$data = array();
$cont = 1;
foreach ($items as $key => $value) {
	$importacion = Entity::search("Importacion","libroCampo = '$value->id'");
	if($importacion){
		$download = "<a href='".__URL."$importacion->path' class='btn btn-sm btn-default' target='_blank'><i class='glyphicon glyphicon-download-alt'></i></a>";
	}else{
		$download =  "";
	}
	$item = array();
	$cont++;
	$item["Id"] = $value->id;
	$item[__TRIAL_CRUD_TABLE_NAME] = $value;
	$item[__TRIAL_CRUD_TABLE_FILE] = $download;
	$item[__TRIAL_CRUD_TABLE_EXPERIMANTAL_UNIT] = $value->campo_numero;
	$item[__TRIAL_CRUD_TABLE_GROUP] = $value->grupo;
	$item[__TRIAL_CRUD_TABLE_ASSIGNED_USERS] = $value->selectedUsers2String();
	$item[__TRIAL_CRUD_TABLE_STATUS] = $value->visible? __TRIAL_CRUD_TABLE_STATUS_IN_COURSE :__TRIAL_CRUD_TABLE_STATUS_ENDED;

	$item[__TRIAL_CRUD_TABLE_ACTIONS] = "<div class='nowrap'><a href='open.php?id_ensayo=$value->id' class='btn btn-primary btn-sm'>".__DATA."</a> ";
	$item[__TRIAL_CRUD_TABLE_ACTIONS] .= "<a href='load.php?id_ensayo=$value->id' class='btn btn-primary btn-sm'>".__LOAD."</a> ";
	$item[__TRIAL_CRUD_TABLE_ACTIONS] .= "<a href='../../Reportes/graphs.php?id_ensayo=$value->id' class='btn btn-primary btn-sm hide'>".__GRAPHS."</a> ";
	if($__user->isAdmin() || $__user->isSuperAdmin()){
		$item[__TRIAL_CRUD_TABLE_ACTIONS] .= "<a href='../Variables/index.php?id_ensayo=$value->id' class='btn btn-default btn-sm'>Variables</a> ";
		$item[__TRIAL_CRUD_TABLE_ACTIONS] .= "<a href='edit.php?id=$value->id' class='btn btn-default btn-sm'>" . __EDIT . "</a> ";
		if($value->visible){
			$item[__TRIAL_CRUD_TABLE_ACTIONS] .= "<a data-href='".__URL."logic/admin/Phenobook/end.php?status=0&id=$value->id' class='btn btn-warning btn-sm ask' data-what='¿Seguro?'>" . __END . "</a> ";
		}else{
			$item[__TRIAL_CRUD_TABLE_ACTIONS] .= "<a data-href='".__URL."logic/admin/Phenobook/end.php?status=1&id=$value->id' class='btn btn-success btn-sm ask' data-what='¿Seguro?'>" . __CONTINUE . "</a> ";
		}
		$item[__TRIAL_CRUD_TABLE_ACTIONS] .= "<a data-href='".__URL."logic/admin/Phenobook/delete.php?id=$value->id' class='btn btn-danger btn-sm ask' data-what='¿Seguro?'>" . __DELETE . "</a></div> ";
	}
	$data[] = $item;

}
echo "<div class='botonera'>";
if($__user->isAdmin() || $__user->isSuperAdmin()){
	echo btn("add Phenobook", "add.php", ICON_ADD);
}
echo "</div>";
echo "<legend>Phenobooks</legend>";
echo genTable($data, "small");
require __ROOT."files/php/template/footer.php";
?>
