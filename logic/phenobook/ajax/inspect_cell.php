<?php
$noHeader = true;
$noMenu = true;
require "../../../files/php/config/require.php";
$eu = _request("eu");
$id_variable = _request("variable");
$id = _request("phenobook");
$phenobook = Entity::search("Phenobook","id = '$id' AND active");

$change_registry = _request("change_registry");
if($change_registry){
	$registry_all = Entity::listMe("Registry","active AND phenobook = '$phenobook->id' AND variable = '$id_variable' AND experimental_unit_number = '$eu' ORDER BY id DESC");
	foreach((array)$registry_all as $r){
		if($r->id != $change_registry){
			$r->status = 0;
		}else{
			$r->status = 1;
		}
		Entity::update($r);
	}
}

$fix_registry = _request("fix_registry");
if($fix_registry){
	$registry = Entity::search("Registry","active AND phenobook = '$phenobook->id' AND id = '$fix_registry'");
	$registry->fixed = 1;
	Entity::update($registry);
}

$unfix_registry = _request("unfix_registry");
if($unfix_registry){
	$registry = Entity::search("Registry","active AND phenobook = '$phenobook->id' AND id = '$unfix_registry'");
	$registry->fixed = 0;
	Entity::update($registry);
}

$registry = Entity::search("Registry","active AND phenobook = '$phenobook->id' AND status AND variable = '$id_variable' AND experimental_unit_number = '$eu' ORDER BY id DESC");
$registry_other = Entity::listMe("Registry","active AND phenobook = '$phenobook->id' AND NOT status AND variable = '$id_variable' AND experimental_unit_number = '$eu' ORDER BY id DESC");
?>
<h5>Registry </h5>
<b>Variable:</b> <?= $registry->variable ?> <br>
<b>Experimental Unit: </b><?= $eu ?>

<hr>

<h5>Current value</h5>
<?php
reg($registry,true);
if($registry_other){
	echo "<h5>Other values</h5>";
}
foreach ($registry_other as $r) {
	reg($r);
}
function reg($registry, $first=false){
	echo "<b>";
	echo "Value: ";
	echo "</b>";
	echo $registry;
	echo "<br>";
	echo "<b>";
	echo "Taken on: ";
	echo "</b>";
	echo $registry->mobile ? "Mobile":"Server";
	echo "<br>";
	if(!$registry->mobile){
		echo "<b>";
		echo "Server time: ";
		echo "</b>";
		echo $registry->stamp;
		echo "<br>";
	}else{
		echo "<b>";
		echo "Mobile time: ";
		echo "</b>";
		echo !empty($registry->localStamp)?$registry->localStamp:"-";
		echo "<br>";
	}
	echo "<b>";
	echo "Location adquired: ";
	echo "</b>";
	echo $registry->latitude . " - " . $registry->longitude;
	echo "<br>";
	echo "<b>";
	echo "User: ";
	echo "</b>";
	echo $registry->user;
	if($first){
		if($registry->fixed){
			echo "<br/><a href='#' data-id='$registry->id' class='btn unfix btn-xs btn-warning'>Unfix this value</a>";
		}else{
			echo "<br/><a href='#' data-id='$registry->id' class='btn fix btn-xs btn-warning'>Fix this value</a>";
		}
	}else{
		echo "<br/><a href='#' data-id='$registry->id' class='replace-value btn btn-xs btn-success'>use this value</a>";
	}
	echo "<hr>";
}
