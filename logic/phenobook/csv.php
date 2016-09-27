<?php
require "../../files/php/config/require.php";
$id = _get("id");
$data = array();
$noMenu = true;
$noHeader = true;

$phenobook = Entity::search("Phenobook","id = '$id' AND active");
$variableGroup = Entity::search("VariableGroup","active AND id = '" . $phenobook->variableGroup->id . "'");
$informative = Entity::search("FieldType","active AND type = '" . FieldType::$TYPE_INFORMATIVE . "'");
$variables = Entity::listMe("Variable","active AND variableGroup = '$variableGroup->id' ORDER BY field(fieldType, ".$informative->id.") DESC");
$data = array();
foreach((array)$variables as $v){
	echo $v;
}
for ($i=1; $i <= $phenobook->experimental_units_number; $i++) {
	$row = array();
	echo "<tr><td class='grey'>$i</td>";
		$reg = Entity::search("Registry"," phenobook = '$phenobook->id' AND active AND status AND experimental_unit_number = '$i' AND variable = '$v->id' ORDER BY experimental_unit_number, id DESC");
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
		echo $value;
	}
}
