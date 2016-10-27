<?php
$admin = true;
require "../../files/php/config/require.php";
$item = Entity::load("Category", _request("id"));
if($item->variable->userGroup->id != $__user->userGroup->id){
  raise404();
}
$item->active = 0;
Entity::update($item);
redirect("index.php?id=".$item->Variable->id."&m=Category deleted");
