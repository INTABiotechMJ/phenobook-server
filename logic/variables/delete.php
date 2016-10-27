<?php
require "../../files/php/config/require.php";
$classNamePlural = "Variables";
$className = "Variable";
$classNameShow = "Variable";
$id = _request("id");
$item = Entity::search($className, "id = '$id'");
if($item->userGroup->id != $__user->userGroup->id){
	raise404();
}
$item->active = 0;
$idgv = _get("idgv");
if(!$alert->hasError){
	Entity::update($item);
	redirect("index.php?id=$idgv&m=$className deleted");
}
