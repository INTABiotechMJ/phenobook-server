<?php
$noHeader = true;
$noMenu = true;
require "../../../files/php/config/require.php";
$data =  json_decode($_POST["data"]);
$phenobook = Entity::load("Phenobook",_post("phenobook"));
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
  $prev_registry = Entity::listMe("Registry","variable = '$variable->id' AND experimental_unit_number = '$position'");
  foreach ($prev_registry as $pr) {
    if($pr){
      $pr->status = 0;
      Entity::update($pr);
    }
  }
  $registry = new Registry();
  $registry->variable = $variable;
  $registry->experimental_unit_number = $position;
  $registry->stamp = stamp();
  $registry->status = 1;
  if($variable->fieldType->isOption()){
    $option = Entity::search("FieldOption","variable = '$variable->id' AND name = '$new_value'");
    $registry->value = $option->id;
  }else{
    $registry->value = $new_value;
  }
  $registry->user = $__user;
  Entity::save($registry);
}
return true;
