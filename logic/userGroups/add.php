<?php
require "../../files/php/config/require.php";
$classNamePlural = "User Groups";
$className = "UserGroup";
$classNameShow = "User Group";

if($_POST){
	Entity::begin();
	$item = new UserGroup();
	$item->name = _post("name");

	if(!$alert->hasError){
		Entity::save($item);
		Entity::commit();
		redirect("index.php?m=$classNameShow added");
	}
}
?>

<div class="row">
	<div class="col-xs-12">

		<div class='row'>

			<div class='col-md-11'>
			</div>

			<div class='col-md-1'>
				<a href='index.php' class='btn btn-primary '>Existing</a>
			</div>

		</div>
		<div class="row">
			<div class="col-sm-8 col-md-offset-1">
				<legend><?= "Add " . $classNameShow ?></legend>
				<form action="<?= $_SERVER["PHP_SELF"] ?>" method="POST" class="valid" autocomplete="off">
					<div class="form-group">
						<label for="name">Name <span class="red">*</span></label>
						<input name="name" type="text" class="form-control required" id="name" value="<?= _post("name") ?>" placeholder="Name">
					</div>
					<div class="form-group">
						<input name="save" type="submit" class="btn btn-primary" value="Save">
					</div>
					<hr>
					<span class="red">*</span> denotes a required field
				</form>
			</div>
		</div>

	</div>
</div>

<?php
require __ROOT . "files/php/template/footer.php";
?>
