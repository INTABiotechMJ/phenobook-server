<?php
$noMenu = true;
$noHeader = true;
require "../../files/php/config/require.php";
if(!empty(_request("variableGroup")) && !empty(_request("phenobooks"))){
	$ids = _request("phenobooks");
	$variableGroup = Entity::load("VariableGroup",_request("variableGroup"));
	$informative = Entity::search("FieldType","active AND type = '" . FieldType::$TYPE_INFORMATIVE . "'");
	$variables = Entity::listMe("Variable","active AND variableGroup = '$variableGroup->id' ORDER BY field(fieldType, ".$informative->id.") DESC");
}
if(!empty(_request("pheno"))){
	$phenobook = Entity::load("Phenobook",_request("pheno"));
	$ids = array(_request("pheno"));
	$variableGroup = Entity::load("VariableGroup",$phenobook->variableGroup->id);
	$informative = Entity::search("FieldType","active AND type = '" . FieldType::$TYPE_INFORMATIVE . "'");
	$variables = Entity::listMe("Variable","active AND variableGroup = '$variableGroup->id' ORDER BY field(fieldType, ".$informative->id.") DESC");
}

$data = array();
$row = array();
$ids_str = "(".implode($ids,",").")";
$phenos = Entity::listMe("Phenobook","active AND id IN $ids_str ORDER BY id");
foreach ($phenos as $pheno) {
	for ($i=1; $i <= $pheno->experimental_units_number; $i++) {
		$row = array();
		if(!_request("pheno")){
			$row["Phenobook"] = $pheno;
		}
		$row["Experimental Unit"] = $i;
		foreach((array)$variables as $v){
			$reg = Entity::search("Registry","active AND phenobook = '$pheno->id' AND status AND experimental_unit_number = '$i' AND variable = '$v->id' ORDER BY experimental_unit_number, id DESC");
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
}
if(_request("pheno")){
	downcsv($phenobook->name,$data);
}else{
	downcsv("report",$data);
}
