<?php
require "../../../files/php/config/require.php";
$classNamePlural = __GROUP_PLURAL;
$className = __GROUP_CLASS;
$classNameShow = __GROUP_CLASS_SHOW;

if($_POST){
	Entity::begin();
	$item = new Group();
	$item->nombre = _post("nombre");

	if(!$alert->hasError){
		Entity::save($item);
		Entity::commit();
		redirect("index.php?m=$classNameShow ".__ADDED);
	}
}
?>

<div class="row">
	<div class="col-xs-12">

		<div class='row'>

			<div class='col-md-11'>
				<legend><?= __ADD . " " . $classNameShow ?></legend>
			</div>

			<div class='col-md-1'>
				<a href='index.php' class='btn btn-primary btn-sm btn-shadow'><?= __EXISTENTS ?></a>
			</div>

		</div>
		<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<form action="<?= $_SERVER["PHP_SELF"] ?>" method="POST" class="valid" autocomplete="off">
					<div class="form-group">
						<label for="nombre"><?= __NAME ?> *</label>
						<input name="nombre" type="text" class="form-control required" id="nombre" value="<?= _post("nombre") ?>" placeholder="<?= __NAME ?>">
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
