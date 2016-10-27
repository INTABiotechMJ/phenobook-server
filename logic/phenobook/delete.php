<?php
$admin = true;
require "../../files/php/config/require.php";
$item = Entity::load("Phenobook", _request("id"));
if($item->userGroup->id != $__user->userGroup->id){
  raise404();
}
$item->active = 0;
Entity::update($item);
redirect("index.php?m=Phenobook deleted");
?>
