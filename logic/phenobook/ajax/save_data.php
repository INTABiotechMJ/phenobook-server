<?php
$noHeader = true;
$noMenu = true;
require "../../../files/php/config/require.php";
$data =  json_decode($_POST["data"]);
$phenobook = Entity::load("Phenobook",_post("phenobook"));
var_dump($data);
foreach ($data->change as $value) {
  $position = $value[0] + 1;
  $col_name = $value[1];
  $prev_value = $value[2];
  $new_value = $value[3];
  $SQL = "active AND variableGroup = '".$phenobook->variableGroup->id."' AND name = '$col_name'";
  $variable = Entity::search("Variable",$SQL);
  if(!$variable){
    return false;
  }
  $registry = new Registry();
  $registry->variable = $variable;
  $registry->experimental_unit_number = $position;
  $registry->stamp = stamp();
  $registry->status = 1;
  $registry->value = $new_value;
  $registry->user = $__user;
  Entity::save($registry);
}
return true;
