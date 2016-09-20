<?php 
$noLogin = true;
$noMenu = true;
$noHeader = true;
require "../../../files/php/config/require.php";


echo "DROP TABLE IF EXISTS User<br/>";
echo "CREATE TABLE User (id INT, name VARCHAR(200), lastName VARCHAR(200), email VARCHAR(200), pass VARCHAR(200), passChanged INT , type INT, active INT)<br/>";
echo makeMySQL("User");

echo "DROP TABLE IF EXISTS Ensayo<br/>";
echo "CREATE TABLE Ensayo (id INT, nombre VARCHAR(200), visible tinyint(4) , active tinyint(4) , descripcion text, campo_numero VARCHAR(200))<br/>";
echo makeMySQL("Ensayo");

/*
echo makeMySQL("Parcela");
echo makeMySQL("Variable");
echo makeMySQL("Registro");
echo makeMySQL("UserPhenobook");
*/

function makeMySQL($table){
	$c = $GLOBALS["conn"];


	$SQL = "SELECT * FROM $table WHERE active";
	$result = $c->query($SQL);
	$rows = $result->fetchall(PDO::FETCH_ASSOC);
	foreach ((array) $rows as $row) {

		$colnamesarr = array_keys($row);
		$colnames = implode(",", $colnamesarr);

		$insert = "INSERT INTO $table ($colnames) VALUES ";

		$colvalues = implode("','", $row);
		$insert .= "('$colvalues')";

		echo $insert."<br/>";
	}
}