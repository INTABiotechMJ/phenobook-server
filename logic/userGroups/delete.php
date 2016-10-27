<?php
$superAdmin = true;
require "../../files/php/config/require.php";
$classNamePlural = "User Groups";
$className = "UserGroup";
$classNameShow = "User Group";

$id = _request("id");
$item = Entity::search($className, "id = '$id'");
$item->active = 0;
if(!$alert->hasError){
	Entity::update($item);
	redirect("index.php?m=$classNameShow deleted");
}
