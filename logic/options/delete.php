<?php
$admin = true;
require "../../files/php/config/require.php";
$item = Entity::load("FieldOption", _request("id"));
$item->active = 0;
Entity::update($item);
redirect("index.php?id=".$item->genericVariable->id."&m=Option deleted");
