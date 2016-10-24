<?php
header('Access-Control-Allow-Origin: *');
$noLogin = true;
$noMenu = true;
$noHeader = true;
require "../files/php/config/require.php";
$items = Entity::listMe("UserGroup","active");
echo json_encode($items);
