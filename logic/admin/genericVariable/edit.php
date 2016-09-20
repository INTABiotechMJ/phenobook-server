<?php
require "../../../files/php/config/require.php";
$classNamePlural = __VARIABLE_GEN_GROUP_PLURAL;
$className = __VARIABLE_GEN_GROUP_CLASS;
$classNameShow = __VARIABLE_GEN_GROUP_CLASS_SHOW;
$id = _request("id");
$item = Entity::search($className,"id = '$id' AND active");

if($_POST){
	$idgv = _post("idgv");
	Entity::begin();
	$item->nombre = _post("nombre");
	$item->descripcion = _post("descripcion");
	$item->tipoCampo = Entity::load("FieldType",_post("tipoCampo"));
	if(!$alert->hasError){
		Entity::update($item);
		Entity::commit();
		redirect("index.php?id=$idgv&m=$classNameShow ".__EDITED);
	}
}
?>

<div class="row">
	<div class="col-xs-12">

		<div class='row'>

			<div class='col-md-11'>
				<legend><?= __EDIT . " " . $classNameShow ?></legend>
			</div>

			<div class='col-md-1'>
				<a href='add.php' class='btn btn-primary btn-sm btn-shadow'><?= __ADD ?></a>
			</div>

		</div>
		<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<form action="<?= $_SERVER["PHP_SELF"] ?>" method="POST" class="valid" autocomplete="off">
					<input type="hidden" name="id" value="<?= _request("id") ?>">
					<input type="hidden" name="idgv" value="<?= _request("idgv") ?>">
					<div class="form-group">
						<label for="nombre"><?= __NAME ?> *</label>
						<input name="nombre" type="text" class="form-control required" id="nombre" value="<?= $item->nombre ?>" placeholder="<?= __NAME ?>">
					</div>
					<div class="form-group">
						<label for="descripcion"><?= __DESCRIPTION ?></label>
						<input name="descripcion" type="text" class="form-control" id="descripcion" value="<?= $item->descripcion ?>" placeholder="<?= __DESCRIPTION ?>">
					</div>
					<div class="form-group">
						<label for="tipoCampo"><?= __TYPE ?> *</label>
						<?php
						$tiposCampo = obj2arr(Entity::listMe("FieldType","active"));
						printSelect("tipoCampo", $item->tipoCampo->id, $tiposCampo, null, "select2 tiposCampo","" );
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
