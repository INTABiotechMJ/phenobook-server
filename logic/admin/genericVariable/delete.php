<?php
require "../../../files/php/config/require.php";
$classNamePlural = __VARIABLE_GEN_GROUP_PLURAL;
$className = __VARIABLE_GEN_GROUP_CLASS;
$classNameShow = __VARIABLE_GEN_GROUP_CLASS_SHOW;
$id = _request("id");
$item = Entity::search($className, "id = '$id'");
$item->active = 0;
$idgv = _get("idgv");
if(!$alert->hasError){
	Entity::update($item);
	redirect("index.php?id=$idgv&m=$className ".__DELETED);
}
