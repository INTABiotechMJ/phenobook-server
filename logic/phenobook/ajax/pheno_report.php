<?php
$noMenu = true;
$noHeader = true;
require "../../../files/php/config/require.php";
$id = _request("id");
$phenobook = Entity::search("Phenobook","id = '$id' AND active");
$variableGroup = Entity::search("VariableGroup","active AND id = '" . $phenobook->variableGroup->id . "'");
$informative = Entity::search("FieldType","active AND type = '" . FieldType::$TYPE_INFORMATIVE . "'");
$variables = Entity::listMe("Variable","active AND variableGroup = '$variableGroup->id' ORDER BY field(fieldType, ".$informative->id.") DESC");
$data = array();

$header = false;
echo "<div class='table-responsive '>";
echo "<table class='table table-hover table-stripped'>";
echo "<tr>";
echo "<th class='grey'>";
echo "EU";
echo "</th>";
foreach((array)$variables as $v){
	if($v->fieldType->isInformative()){
		echo "<th class='summary'>";
	}else{
		echo "<th class='summary'>";
	}
	echo "<a href='#'  data-id_variable='$v->id' data-id_phenobook='$phenobook->id'>".$v."</a>";
	echo "</th>";
}
echo "</tr>";
for ($i=1; $i <= $phenobook->experimental_units_number; $i++) {
	$row = array();
	$anyreg = false;
	foreach((array)$variables as $v){
		$anyreg = Entity::search("Registry"," phenobook = '$phenobook->id' AND active AND status AND experimental_unit_number = '$i' AND variable = '$v->id' ORDER BY experimental_unit_number, id DESC");
		if($anyreg){
			break;
		}
	}
	#if($anyreg){
		echo "<tr><td class='grey'>$i</td>";
	#}else{
	#	continue;
	#}
	foreach((array)$variables as $v){
		$class = "";
		$has_val = "";
		$more = "";
		$prev_reg = Entity::search("Registry"," phenobook = '$phenobook->id' AND active AND NOT status AND experimental_unit_number = '$i' AND variable = '$v->id' ORDER BY experimental_unit_number, id DESC");
		$reg = Entity::search("Registry"," phenobook = '$phenobook->id' AND active AND status AND experimental_unit_number = '$i' AND variable = '$v->id' ORDER BY experimental_unit_number, id DESC");
		if($v->fieldType->isInformative()){
			$class = "info";
		}
		if($reg){
			$has_val = "has_val";
			switch ($v->fieldType->type) {
				case FieldType::$TYPE_OPTION:
				$option = Entity::search("FieldOption","variable = '$v->id' AND id = '$reg->value'");
				if($option){
					$value = $option->name;
				}
				break;
				case FieldType::$TYPE_CHECK:
				$value = $reg->value?"<span class='yes'>yes</i>":"";
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
		echo "<td data-variable='$v->id' data-eu='$i' class='$has_val $class $more'>";
		echo $value;
		if($reg && $reg->fixed){
			echo "<i class='glyphicon glyphicon-pushpin'></i>";
		}
		echo "</td>";
	}
	if($anyreg){
		echo "</tr>";
	}
}
echo "</div>";
echo "</table>";
