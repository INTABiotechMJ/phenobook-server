<?php
$admin = true;
require "../../files/php/config/require.php";
$item = Entity::load("Phenobook", _request("id"));
$item->visible = _get("status");
Entity::update($item);
redirect("index.php?m=Phenobook status changed");
