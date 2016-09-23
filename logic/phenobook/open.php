<?php
require "../../files/php/config/require.php";
$phenobook = Entity::load("Phenobook",_get("id"));
?>
<legend>
	Phenobook <i><?= $phenobook ?></i>
</legend>
<?php
require __ROOT."files/php/template/footer.php";
?>
