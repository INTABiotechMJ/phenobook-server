<?php
$noMenu = true;
$noHeader = true;
require "../../files/php/config/require.php";
if(!empty(_request("variables")) && !empty(_request("phenobooks"))){
	$ids = _request("phenobooks");
	$ids_vars = implode(_request("variables"),",");
	$variables = Entity::listMe("Variable","active AND id IN ($ids_vars)");
}
//only one phenobook is requested
if(!empty(_request("phenobook"))){
	$phenobook = Entity::load("Phenobook",_request("phenobook"));
	$ids = array(_request("phenobook"));
	$variables = $phenobook->searchVariables();
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
					case FieldType::$TYPE_CATEGORICAL:
					$option = Entity::search("Category","variable = '$v->id' AND id = '$reg->value'");
					if($option){
						$row[$v->name] = $option->name;
					}else{
						$row[$v->name] = "";
					}
					break;
					case FieldType::$TYPE_BOOLEAN:
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
