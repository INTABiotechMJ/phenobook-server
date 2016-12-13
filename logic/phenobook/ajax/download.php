<?php
$noHeader = true;
$noMenu = true;
require "../../../files/php/config/require.php";
$registry = Entity::load("Registry",_request("id"));
header("Content-type: text/plain");
header("Content-Disposition: attachment; filename=".$registry->phenobook."_".$registry->variable."_".$registry->experimental_unit_number.".txt");
echo $registry->value;
