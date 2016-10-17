<?php
$noMenu = true;
$noHeader = true;
require "../../../files/php/config/require.php";

$phenobook = false;
if(!empty(_request("variableGroup")) && !empty(_request("phenobooks"))){
	$ids = _request("phenobooks");
	$variableGroup = Entity::load("VariableGroup",_request("variableGroup"));
	$informative = Entity::search("FieldType","active AND type = '" . FieldType::$TYPE_INFORMATIVE . "'");
	$variables = Entity::listMe("Variable","active AND variableGroup = '$variableGroup->id' ORDER BY field(fieldType, ".$informative->id.") DESC");
}
if(!empty(_request("phenobook"))){
	$phenobook = Entity::load("Phenobook",_request("phenobook"));
	$ids = array(_request("phenobook"));
	$variableGroup = Entity::load("VariableGroup",$phenobook->variableGroup->id);
	$informative = Entity::search("FieldType","active AND type = '" . FieldType::$TYPE_INFORMATIVE . "'");
	$variables = Entity::listMe("Variable","active AND variableGroup = '$variableGroup->id' ORDER BY field(fieldType, ".$informative->id.") DESC");
}
if(empty($variables)){
	die(eAlert("Selected variable group has no variables yet"));
}
$data = array();

$header = false;
echo "<div class='table-responsive '>";
echo "<table class='table table-hover table-stripped'>";
echo "<tr>";
if(!$phenobook){
	echo "<th class='grey'>";
	echo "Phenobook";
	echo "</th>";
}
echo "<th class='grey'>";
echo "EU";
echo "</th>";
foreach((array)$variables as $v){
	echo "<th class='summary'>";
	echo "<a href='#'  data-id_variable='$v->id' data-id_phenobooks='".implode($ids,",")."'>".$v."</a>";
	echo "</th>";
}
echo "</tr>";
$ids_str = "(".implode($ids,",").")";
$phenos = Entity::listMe("Phenobook","active AND id IN $ids_str ORDER BY id");
foreach ($phenos as $pheno) {
	for ($i=1; $i <= $pheno->experimental_units_number; $i++) {
		$row = array();
		$anyreg = false;
		foreach((array)$variables as $v){
			$anyreg = Entity::search("Registry","active AND phenobook = '$pheno->id' AND status AND experimental_unit_number = '$i' AND variable = '$v->id' ORDER BY experimental_unit_number, id DESC");
			if($anyreg){
				break;
			}
		}
		echo "<tr>";
		if(!$phenobook){
			echo "<td class='grey'>$pheno</td>";
		}
		echo "<td class='grey'>$i</td>";
		foreach((array)$variables as $v){
			$class = "";
			$has_val = "";
			$more = "";
			$prev_reg = Entity::search("Registry","active AND phenobook = '$pheno->id' AND NOT status AND experimental_unit_number = '$i' AND variable = '$v->id' ORDER BY experimental_unit_number, id DESC");
			$reg = Entity::search("Registry","active AND phenobook = '$pheno->id' AND status AND experimental_unit_number = '$i' AND variable = '$v->id' ORDER BY experimental_unit_number, id DESC");
			if($v->fieldType->isInformative()){
				$class = "info";
			}
			if($reg){
				$has_val = "has_val";
				switch ($v->fieldType->type) {
					case FieldType::$TYPE_OPTION:
					$option = Entity::search("Category","variable = '$v->id' AND id = '$reg->value'");
					if($option){
						$value = $option->name;
					}
					break;
					case FieldType::$TYPE_CHECK:
					$value = $reg->value==1?"<span class='yes'>yes</i>":"";
					break;
					case FieldType::$TYPE_PHOTO:
					$value = $reg->calcPhoto();
					break;
					default:
					$value = $reg->value;
					break;
				}
				if($prev_reg){
					//more than one record
					$more = "more";
				}else{
					$more = "";
				}

			}else{
				if($v->fieldType->isCheck()){
					$value = "";
				}else{
					$value = "";
				}
			}
			echo "<td data-variable='$v->id' data-id_phenobook='$pheno->id' data-eu='$i' class='$has_val $class $more'>";
			echo $value;
			if($reg && $reg->fixed){
				echo "<i class='glyphicon glyphicon-pushpin'></i>";
			}
			echo "</td>";
		}
		#if($anyreg){
		echo "</tr>";
		#}
	}
}
echo "</div>";
echo "</table>";
