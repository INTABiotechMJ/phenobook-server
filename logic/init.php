<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
</head>
<body>


	<?php

	require "../files/php/config/config.php";
	require "../files/php/class/Object.php";

	$classes = array();
	foreach (glob(__ROOT . 'logic/class/*', GLOB_ONLYDIR) as $dir) {
		foreach (glob("$dir/*.php") as $filename) {
			//echo $filename."<br>";
			include_once $filename;
			$basename = basename($filename, ".php");
			$classes[] = $basename;
		}
	}

	require "../files/php/class/EMail.php";
	$classes[] = "EMail";
	require "../files/php/class/TableMapper.php";
	echo initDB($classes);

	function __autoload($class_name) {
		foreach (glob(__ROOT . '/logic/class/*', GLOB_ONLYDIR) as $dir) {
			if (is_file("$dir/$class_name.php")) {
				require_once "$dir/$class_name.php";
			}
		}
	}
	echo "<hr>";
	echo "INSERT INTO UserGroup (name ) VALUES ('Default'); <br/>";

	echo "<hr>";
	echo "INSERT INTO User (name, email, pass,userGroup ) VALUES ('admin','admin','admin','1'); <br/>";
	echo "<hr>";
	echo "INSERT INTO FieldType (name, type ) VALUES ('Text','1'); <br/>";
	echo "<hr>";
	echo "INSERT INTO FieldType (name, type ) VALUES ('Option','2'); <br/>";
	echo "<hr>";
	echo "INSERT INTO FieldType (name, type ) VALUES ('Check','3'); <br/>";
	echo "<hr>";
	echo "INSERT INTO FieldType (name, type ) VALUES ('Number','4'); <br/>";
	echo "<hr>";
	echo "INSERT INTO FieldType (name, type ) VALUES ('Date','5'); <br/>";
	echo "<hr>";
	echo "INSERT INTO FieldType (name, type ) VALUES ('Photo','6'); <br/>";
	echo "<hr>";
	echo "INSERT INTO FieldType (name, type ) VALUES ('Date Time','7'); <br/>";
	echo "<hr>";
	echo "INSERT INTO FieldType (name, type ) VALUES ('Time','8'); <br/>";
	echo "<hr>";
	echo "INSERT INTO FieldType (name, type ) VALUES ('Informative','12'); <br/>";
	echo "<hr>";
	//echo "INSERT INTO FieldType (name, type ) VALUES ('Audio','11'); <br/>";
	echo "<hr>";



	?>

</body>
</html>


<hr>
