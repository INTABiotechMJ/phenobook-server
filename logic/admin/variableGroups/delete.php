<?php
require "../../../files/php/config/require.php";
$classNamePlural = __VARIABLE_GROUP_PLURAL;
$className = __VARIABLE_GROUP_CLASS;
$classNameShow = __VARIABLE_GROUP_CLASS_SHOW;

$id = _request("id");
$item = Entity::search($className, "id = '$id'");
$item->active = 0;
if(!$alert->hasError){
	Entity::update($item);
	redirect("index.php?m=$className ".__DELETED);
}
