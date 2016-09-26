<?php
require "../../../../files/php/config/require.php";
$header = false;
echo "<div class='table-responsive '>";
echo "<table class='table table-hover table-stripped'>";
echo "<tr>";
echo "<th class='grey'>";
echo "EU";
echo "</th>";
foreach((array)$variables as $v){
	if($v->fieldType->isInformative()){
		echo "<th class=''>";
	}else{
		echo "<th>";
	}
	echo $v;
	echo "</th>";
}
echo "</tr>";
for ($i=1; $i <= $phenobook->experimental_units_number; $i++) {
	$row = array();
	$anyreg = false;
	foreach((array)$variables as $v){
		$anyreg = Entity::search("Registry","active AND status AND experimental_unit_number = '$i' AND variable = '$v->id' ORDER BY experimental_unit_number, id DESC");
		if($anyreg){
			break;
		}
	}
	if($anyreg){
		echo "<tr><td class='grey'>$i</td>";
	}else{
		continue;
	}
	foreach((array)$variables as $v){
		$class = "";
		$has_val = "";
		$more = "";
		$prev_reg = Entity::search("Registry","active AND NOT status AND experimental_unit_number = '$i' AND variable = '$v->id' ORDER BY experimental_unit_number, id DESC");
		$reg = Entity::search("Registry","active AND status AND experimental_unit_number = '$i' AND variable = '$v->id' ORDER BY experimental_unit_number, id DESC");
		if($v->fieldType->isInformative()){
			$class = "info";
		}
		if($reg){
			$has_val = "has_val";
			switch ($v->fieldType->type) {
				case FieldType::$TYPE_OPTION:
				$option = Entity::search("FieldOption","variable = '$v->id' AND id = '$reg->value'");
				$value = $option->name;
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
