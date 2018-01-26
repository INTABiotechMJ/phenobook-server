<?php
define("__PATH_SITE_ID_PROD", "getphenobook.com");
define("__PATH_SITE_ID_DEV", "localhost/phenobook/");

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
	define("__URL_FULL", "http://localhost/phenobook/"); //URL containing http/s. Ends with trailing slash
	define("__URL", "/versioned/software/phenobook/server/");//Location path.Ends with trailing slash
	define("__TITLE", "Phenobook");
	//DATABASE PARAMETERS
	define("__DBNAME", "yourdbname");
	define("__DBPASS", "password");
	define("__DBUSER", "yourdbuser");
	define("__DBHOST", "localhost");
}
if(__PRODUCTION){
	define("__URL_FULL", "http://yoururl.com/"); //URL containing http/s. Ends with trailing slash
	define("__URL", "/");//Location path.Ends with trailing slash
	define("__TITLE", "Phenobook");
	//DATABASE PARAMETERS
	define("__DBNAME", "yourdbname");
	define("__DBPASS", "yourpassword");
	define("__DBUSER", "yourdbuser");
	define("__DBHOST", "localhost");
}

define("__HASH", "12sj12os2s1jsoqwa"); // Random string
date_default_timezone_set("America/Argentina/Buenos_Aires");
if (!empty($_SERVER["DOCUMENT_ROOT"])) {
	define("__ROOT", $_SERVER["DOCUMENT_ROOT"] . __URL);
}

$__URL = __URL;
