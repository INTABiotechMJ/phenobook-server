<?php 
$admin = true;
require "../../../files/php/config/require.php";
$item = Entity::load("Opcion", _request("id"));
$item->active = 0;
Entity::update($item);
redirect("index.php?id_variable=".$item->variable->id."&m=".__OPTIONS_CRUD_DELETED_MESSAGES);