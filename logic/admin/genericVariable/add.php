<?php
require "../../../files/php/config/require.php";
$classNamePlural = __VARIABLE_GEN_GROUP_PLURAL;
$className = __VARIABLE_GEN_GROUP_CLASS;
$classNameShow = __VARIABLE_GEN_GROUP_CLASS_SHOW;

$grupoVariable = Entity::load("GroupVariable",_request("id"));
if($_POST){
	Entity::begin();
	$item = new VariableGenerica();
	$item->nombre = _post("nombre");
	$item->descripcion = _post("descripcion");
	$item->grupoVariable = $grupoVariable;
	$item->tipoCampo = Entity::load("FieldType",_post("tipoCampo"));

	if(!$alert->hasError){
		Entity::save($item);
		Entity::commit();
		redirect("index.php?id=$grupoVariable->id&m=$classNameShow ".__ADDED);
	}
}
?>

<div class="row">
	<div class="col-xs-12">

		<div class='row'>

			<div class='col-md-11'>
				<legend><?= __ADD . " " . $classNameShow ?> - <?= __VARIABLE_GROUP_CLASS_SHOW . " <i>$grupoVariable</i> " ?> </legend>
			</div>

			<div class='col-md-1'>
				<a href='index.php' class='btn btn-primary btn-sm btn-shadow'><?= __EXISTENTS ?></a>
			</div>

		</div>
		<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<form action="<?= $_SERVER["PHP_SELF"] ?>" method="POST" class="valid" autocomplete="off">
					<input type="hidden" name="id" value="<?= _request("id") ?>">
					<div class="form-group">
						<label for="nombre"><?= __NAME ?> *</label>
						<input name="nombre" type="text" class="form-control required" id="nombre" value="<?= _post("nombre") ?>" placeholder="<?= __NAME ?>">
					</div>
					<div class="form-group">
						<label for="descripcion"><?= __DESCRIPTION ?></label>
						<input name="descripcion" type="text" class="form-control" id="descripcion" value="<?= _post("descripcion") ?>" placeholder="<?= __DESCRIPTION ?>">
					</div>
					<div class="form-group">
						<label for="tipoCampo"><?= __TYPE ?> *</label>
						<?php
						$tiposCampo = obj2arr(Entity::listMe("FieldType","active"));
						printSelect("tipoCampo", null, $tiposCampo, null, "select2 tiposCampo","" );
						 ?>
					</div>
					<div class="form-group">
						<input name="save" type="submit" class="btn btn-default" value="<?= __SAVE ?>">
					</div>
				</form>
			</div>
		</div>

	</div>
</div>

<?php
require __ROOT . "files/php/template/footer.php";
?>
