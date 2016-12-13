<?php
require "../../files/php/config/require.php";
$classNamePlural = "Variables";
$className = "Variable";
$classNameShow = "Variable";
$id = _request("id");
$item = Entity::search($className,"id = '$id' AND active");
if($item->userGroup->id != $__user->userGroup->id){
	raise404();
}
if($_POST){
	Entity::begin();
	$item->name = _post("name");
	$item->description = _post("description");
	$item->isInformative = _post("isInformative")?1:0;
	if(!$alert->hasError){
		Entity::update($item);
		Entity::commit();
		redirect("index.php?m=$classNameShow edited");
	}
}
?>

<div class="row">
	<div class="col-xs-12">

		<div class='row'>

			<div class='col-md-11'>
				<legend><?= "Edit $classNameShow <i>$item</i>"?></legend>
			</div>

			<div class='col-md-1'>
				<a href='index.php' class='btn btn-default'>Existing</a>
			</div>

		</div>
		<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<form action="<?= $_SERVER["PHP_SELF"] ?>" method="POST" class="valid" autocomplete="off">
					<input type="hidden" name="id" value="<?= _request("id") ?>">
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
						<?php
						echo check("isInformative",$item->isInformative);
						?>
						<label class="control-label" for="isInformative">Is informative</label>
						<span class="help-block">Informative variables are pre-filled and serve as a visual guide to the user</span>
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
