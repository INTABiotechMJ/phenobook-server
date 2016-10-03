<?php
$noMenu = true;
$noHeader = true;
require "../../files/php/config/require.php";
$id = _get("id");
$data = array();

$phenobook = Entity::search("Phenobook","id = '$id' AND active");
$variableGroup = Entity::search("VariableGroup","active AND id = '" . $phenobook->variableGroup->id . "'");
$informative = Entity::search("FieldType","active AND type = '" . FieldType::$TYPE_INFORMATIVE . "'");
$variables = Entity::listMe("Variable","active AND variableGroup = '$variableGroup->id' ORDER BY field(fieldType, ".$informative->id.") DESC");
$data = array();
$row = array();
for ($i=1; $i <= $phenobook->experimental_units_number; $i++) {
	$row = array();
	$anyreg = false;
	$eu = 'Experimental Unit';
	$row[$eu] = $i;
	foreach((array)$variables as $v){
		$class = "";
		$has_val = "";
		$more = "";
		$reg = Entity::search("Registry"," phenobook = '$phenobook->id' AND active AND status AND experimental_unit_number = '$i' AND variable = '$v->id' ORDER BY experimental_unit_number, id DESC");
		if($reg){
			switch ($v->fieldType->type) {
				case FieldType::$TYPE_OPTION:
				$option = Entity::search("FieldOption","variable = '$v->id' AND id = '$reg->value'");
				if($option){
					$row[$v->name] = $option->name;
				}else{
					$row[$v->name] = "";
				}
				break;
				case FieldType::$TYPE_CHECK:
				$row[$v->name] = $reg->value?"yes":"";
				break;
				default:
				$row[$v->name] = $reg->value;
				break;
			}
		}else{
			$row[$v->name] = "";
		}
	}
	$data[] = $row;
}

downcsv($phenobook->name,$data);
