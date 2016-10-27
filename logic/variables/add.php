<?php
require "../../files/php/config/require.php";
$classNamePlural = "Variables";
$className = "Variable";
$classNameShow = "Variable";

if($_POST){
	Entity::begin();
	$item = new Variable();
	$item->name = _post("name");
	$item->isInformative = _post("isInformative")?1:0;
	$item->description = _post("description");
	$item->userGroup = $__user->userGroup;
	$item->fieldType = Entity::load("FieldType",_post("fieldType"));

	if(!$alert->hasError){
		Entity::save($item);
		Entity::commit();
		if(_post("save_add")){
			redirect("add.php?m=$classNameShow added");
		}
		if(_post("save_back")){
			redirect("index.php?m=$classNameShow added");
		}
	}
}
?>

<div class="row">
	<div class="col-xs-12">

		<div class='row'>

			<div class='col-md-8 col-xs-6'>
				<legend>Add <i>variable</i></legend>
			</div>
			<div class='col-md-3'>

			</div>

			<div class='col-md-2'>
				<a href='index.php' class='btn btn-default '>Existents</a>
			</div>

		</div>
		<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<form action="<?= $_SERVER["PHP_SELF"] ?>" method="POST" class="valid" autocomplete="off">
					<input type="hidden" name="id" value="<?= _request("id") ?>">
					<div class="form-group">
						<label for="name">Name <span class="red">*</span></label>
						<input name="name" type="text" class="form-control required" id="name" value="<?= _post("name") ?>" placeholder="Name">
					</div>
					<div class="form-group">
						<label for="description">Description</label>
						<input name="description" type="text" class="form-control" id="description" value="<?= _post("description") ?>" placeholder="Description">
					</div>
					<div class="form-group">
						<label for="fieldType">Type <span class="red">*</span></label>
						<?php
						$fieldTypes = obj2arr(Entity::listMe("FieldType","active"));
						printSelect("fieldType", _post("fieldType"), $fieldTypes, null, "select2","" );
						?>
					</div>
					<div class="form-group">
						<input type="checkbox" name="isInformative" value="1" id="isInformative">
						<label class="control-label" for="isInformative">Is informative</label>
						<span class="help-block">Informative variables are pre-filled and serve as a visual guide to the user</span>
					</div>
					<div class="form-group">
						<input name="save_add" type="submit" class="btn btn-primary" value="Save and add another">
						<input name="save_back" type="submit" class="btn btn-primary" value="Save and finish">
						<a href="index.php" class="btn btn-default">Discard this and finish</a>
					</div>
				</form>
			</div>
		</div>

	</div>
</div>
<?php
require __ROOT . "files/php/template/footer.php";
?>
<script type="text/javascript">
$("#fieldType").change(function(){

});
</script>
