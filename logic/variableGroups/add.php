<?php
require "../../files/php/config/require.php";
$classNamePlural = "Variable Groups";
$className = "VariableGroup";
$classNameShow = "Variable Group";

if($_POST){
	Entity::begin();
	$item = new VariableGroup();
	$item->name = _post("name");

	if(!$alert->hasError){
		Entity::save($item);
		Entity::commit();
		redirect("../genericVariable/add.php?id=$item->id&m=$classNameShow added");
	}
}
?>
<div class="row">
	<div class="col-xs-12">
		<div class='row'>
			<div class='col-md-11'>
				<legend>Add <?= $classNameShow ?></legend>
			</div>
			<div class='col-md-1'>
				<a href='index.php' class='btn btn-default btn-sm'>Existents</a>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<form action="<?= $_SERVER["PHP_SELF"] ?>" method="POST" class="valid" autocomplete="off">
					<div class="form-group">
						<label for="name">Name *</label>
						<input name="name" type="text" class="form-control required" id="name" value="<?= _post("name") ?>" placeholder="Name">
					</div>
					<div class="form-group">
						<input name="save" type="submit" class="btn btn-primary" value="Save and add variables">
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<?php
require __ROOT . "files/php/template/footer.php";
?>
