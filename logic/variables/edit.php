<?php
require "../../files/php/config/require.php";
$classNamePlural = "Variables";
$className = "Variable";
$classNameShow = "Variable";
$id = _request("id");
$item = Entity::search($className,"id = '$id' AND active");

if($_POST){
	$idgv = _post("idgv");
	Entity::begin();
	$item->name = _post("name");
	$item->description = _post("description");
	//$item->fieldType = Entity::load("FieldType",_post("fieldType"));
	if(!$alert->hasError){
		Entity::update($item);
		Entity::commit();
		redirect("index.php?id=$idgv&m=$classNameShow edited");
	}
}
?>

<div class="row">
	<div class="col-xs-12">

		<div class='row'>

			<div class='col-md-11'>
				<legend><?= "Edit " . $classNameShow ?></legend>
			</div>

			<div class='col-md-1'>
				<a href='add.php' class='btn btn-default'>Add</a>
			</div>

		</div>
		<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<form action="<?= $_SERVER["PHP_SELF"] ?>" method="POST" class="valid" autocomplete="off">
					<input type="hidden" name="id" value="<?= _request("id") ?>">
					<input type="hidden" name="idgv" value="<?= _request("idgv") ?>">
					<div class="form-group">
						<label for="name">Name <span class="red">*</span></label>
						<input name="name" type="text" class="form-control required" id="name" value="<?= $item->name ?>" placeholder="Name">
					</div>
					<div class="form-group">
						<label for="description">Description</label>
						<input name="description" type="text" class="form-control" id="description" value="<?= $item->description ?>" placeholder="Description">
					</div>
					<div class="form-group">
						<label for="fieldType">Type <span class="red">*</span></label>
						<?php
						$tiposCampo = obj2arr(Entity::listMe("FieldType","active"));
						printSelect("fieldType", $item->fieldType->id, $tiposCampo, null, "select2 disabled tiposCampo","disabled='disabled'" );
						 ?>
					</div>
					<div class="form-group">
						<input name="save" type="submit" class="btn btn-primary" value="Save">
					</div>
				</form>
			</div>
		</div>

	</div>
</div>

<?php
require __ROOT . "files/php/template/footer.php";
?>
