<?php 
require "../../files/php/config/require.php";
$id_libroCampo = _get("id_ensayo");
$libroCampo = Entity::search("Phenobook","id = '$id_libroCampo' AND active");
$variables = Entity::listMe("Variable","active AND libroCampo = '".$libroCampo->id."'");
$data = array();

foreach ($variables as $v) {
	$item = array();
	$item[__NAME] = $v;
	$item[__TYPE] = $v->tipoCampo;
	$item[__ACTIONS] = "<div class='nowrap'> ";
	$tipos = $v->tipoCampo->searchTipoGrafico();
	foreach($tipos as $tipoG){
		$item[__ACTIONS] .= "<input type='checkbox' class='' value='$v->id"."_".$tipoG->id."' id='i_$v->id"."_".$tipoG->id."'>";
		$item[__ACTIONS] .= "<label for='i_$v->id"."_".$tipoG->id."'>$tipoG</label> | ";
	}
	$item[__ACTIONS] .= "</div>";
	$data[] = $item;
}
echo "<div class='botonera'>";
echo btn(__BACK, "../admin/Phenobook/index.php", ICON_BACK, TYPE_DEFAULT);
echo "</div>";
echo "<legend>".__FIELDBOOK_CLASS_SHOW." <span class='object-name'>$libroCampo</span></legend>";

?>
<form action="<?= $_SERVER["PHP_SELF"] ?>" method="POST" class="main valid" autocomplete="off">
	<?php 
	echo genTable($data, true,null, "small tdmark");
	if(!empty($data)){
		?>
		<div class="form-group">
			<input name="save" type="submit" class="btn btn-default btn-lg" value="<?= __GEN ?>">
		</div>
		<?php 
	}
	?>
</form>
<?php 

require __ROOT."files/php/template/footer.php";
?>