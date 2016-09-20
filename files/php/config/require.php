<?php
$testing = true;
error_reporting(E_ALL);
ini_set('display_errors', 1);
require "config.php";

require __ROOT. "files/php/class/functions.php";
require __ROOT. "files/php/class/Alert.php";
require __ROOT. "files/php/class/StrFilter.php";

require __ROOT. "files/php/class/Entity.php";
require __ROOT. "files/php/class/Object.php";
require __ROOT . "logic/class/Session/User.php";

if(empty($noLogin)){
	if(isset($admin) && $admin){
		require "control_admin.php";
	}else{
		require "control.php";
	}
}else{
	if(isset($_SESSION["user".__HASH])){
		$__user = $_SESSION["user".__HASH];
	}
}
$alert = new Alert();
$strFilter = new StrFilter();
if(!isset($noHeader)){
	require __ROOT."files/php/template/header.php";
}
if(!isset($noMenu)){
	require __ROOT."files/php/template/menu.php";
}

require __ROOT."files/php/class/EMail.php";
require __ROOT."files/php/class/elements/Select.php";

try {
	$GLOBALS["conn"] = new PDO("mysql:".__DBHOST."=localhost;dbname=".__DBNAME, __DBUSER, __DBPASS,
		array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
	$GLOBALS["conn"]->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){
	echo $e->getMessage();
}

function __autoload($class_name) {
	foreach (glob(__ROOT . '/logic/class/*', GLOB_ONLYDIR) as $dir) {
		if (is_file("$dir/$class_name.php")) {
			require_once "$dir/$class_name.php";
		}
	}
}
