<?php 
require "../../../files/php/config/require.php";
$classNamePlural = __GROUP_PLURAL;
$className = __GROUP_CLASS;
$classNameShow = __GROUP_CLASS_SHOW;

$id = _request("id");
$item = Entity::search($className, "id = '$id'");
$item->active = 0;
if(!$alert->hasError){
	Entity::update($item);
	redirect("index.php?m=$className ".__DELETED);
}
