<?php 
$admin = true;
require "../../../files/php/config/require.php";
$item = Entity::load("Phenobook", _request("id"));
$item->active = 0;
Entity::update($item);
redirect("index.php?m=".__TRIAL_CRUD_DELETED_MESSAGE);
?>