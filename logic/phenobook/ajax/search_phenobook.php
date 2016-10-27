<?php
$noHeader = true;
$noMenu = true;
require "../../../files/php/config/require.php";
$phenobooks = Entity::listMe("Phenobook","active AND userGroup = '".$__user->userGroup->id."'");
echo json_encode(obj2arr($phenobooks));
