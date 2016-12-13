<?php
define("__PATH_SITE_ID_PROD", "biotecmj.inta.gob.ar/");
define("__PATH_SITE_ID_DEV", "localhost/versioned/software/phenobook/server/");
$url = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
if (strpos($url, __PATH_SITE_ID_PROD) !== false) {
	define("__PRODUCTION", true);
	define("__DEV", false);
}
if (strpos($url, __PATH_SITE_ID_DEV) !== false) {
	define("__PRODUCTION", false);
	define("__DEV", true);
}
if(__DEV){
	define("__URL_FULL", "http://localhost/versioned/software/phenobook/server/"); //URL containing http/s. Ends with trailing slash
	define("__URL", "/versioned/software/phenobook/server/");//Location path.Ends with trailing slash
	define("__TITLE", "Phenobook");
	//DATABASE PARAMETERS
	define("__DBNAME", "phenobook");
	define("__DBPASS", "manolin");
	define("__DBUSER", "root");
	define("__DBHOST", "localhost");
}
if(__PRODUCTION){
	define("__URL_FULL", "http://biotecmj.inta.gob.ar/campo/phenobook/"); //URL containing http/s. Ends with trailing slash
	define("__URL", "/campo/phenobook/");//Location path.Ends with trailing slash
	define("__TITLE", "Phenobook");
	//DATABASE PARAMETERS
	define("__DBNAME", "phenobook");
	define("__DBPASS", "GyLhMn9bqNnPNVtp");
	define("__DBUSER", "phenobook");
	define("__DBHOST", "localhost");
}

define("__HASH", "23ke2d3kd22"); // Random string
date_default_timezone_set("America/Argentina/Buenos_Aires");
if (!empty($_SERVER["DOCUMENT_ROOT"])) {
	define("__ROOT", $_SERVER["DOCUMENT_ROOT"] . __URL);
}

$__URL = __URL;
