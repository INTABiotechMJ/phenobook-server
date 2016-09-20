<?php
require "../../../files/php/config/require.php";
$classNamePlural = __VARIABLE_GROUP_PLURAL;
$className = __VARIABLE_GROUP_CLASS;
$classNameShow = __VARIABLE_GROUP_CLASS_SHOW;

$id = _request("id");
$item = Entity::search($className,"id = '$id' AND active");

if($_POST){
	Entity::begin();
	$item->nombre = _post("nombre");

	if(!$alert->hasError){
		Entity::update($item);
		Entity::commit();
		redirect("index.php?m=$classNameShow ".__EDITED);
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
					<div class="form-group">
						<label for="nombre"><?= __NAME ?> *</label>
						<input name="nombre" type="text" class="form-control required" id="nombre" value="<?= $item->nombre ?>" placeholder="<?= __NAME ?>">
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
