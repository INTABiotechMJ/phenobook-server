<?php
$noHeader = true;
$noMenu = true;
require "../../../files/php/config/require.php";
$variable_group_id = _request("variableGroup");
$phenobooks = Entity::listMe("Phenobook","active AND variableGroup = '$variable_group_id'");
echo json_encode(obj2arr($phenobooks));
