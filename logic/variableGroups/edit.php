<?php
require "../../files/php/config/require.php";
$classNamePlural = "Variable Groups";
$className = "VariableGroup";
$classNameShow = "Variable Group";

$id = _request("id");
$item = Entity::search($className,"id = '$id' AND active");

if($_POST){
	Entity::begin();
	$item->name = _post("name");
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
			</div>

			<div class='col-md-1'>
				<a href='index.php' class='btn btn-default'>Existents</a>
			</div>

		</div>
		<div class="row">
			  <div class="col-sm-8 col-md-offset-1">
				<legend><?= "Edit " . $classNameShow ?></legend>
				<form action="<?= $_SERVER["PHP_SELF"] ?>" method="POST" class="valid" autocomplete="off">
					<input type="hidden" name="id" value="<?= _request("id") ?>">
					<div class="form-group">
						<label for="name">Name <span class="red">*</span></label>
						<input name="name" type="text" class="form-control required" id="name" value="<?= $item->name ?>" placeholder="Name">
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
