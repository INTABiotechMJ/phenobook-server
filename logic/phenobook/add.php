<?php
$admin = true;
require "../../files/php/config/require.php";
$userGroups = obj2arr(Entity::listMe("UserGroup","active"));
$variableGroups = obj2arr(Entity::listMe("VariableGroup","active AND userGroup = '" + $__user->userGroup->id + "' "));

if($_POST){
  Entity::begin();
  $item = new Phenobook();
  $item->name = _post("name");
  $item->description = _post("description");
  $item->stamp = stamp();
  $item->variableGroup = Entity::load("VariableGroup",_post("variableGroup"));
  $item->userGroup = $__user->userGroup;
  $item->experimental_units_number = _post("experimental_units_number");

  if(!$alert->hasError){
    Entity::save($item);
    Entity::commit();
    redirect("index.php?id=$item->id&m=Phenobook added");
  }

}

$users = obj2arr(Entity::listMe("User","active AND 1"));

?>
<div class="row">

  <div class="col-sm-8 col-md-offset-1">
    <form autocomplete="off" enctype="multipart/form-data" class="form-horizontal valid"
    method="POST" action="<?= $_SERVER["PHP_SELF"]?>">
    <fieldset>
      <!-- Form Name -->
      <legend>Add Phenobook</legend>

      <div class="form-group">
        <label class=" control-label" for="name">Name <span class="red">*</span></label>
        <input placeholder="Phenobook name" id="name" name="name" value="<?= _post("name"); ?>" type="text" class="form-control input-md required">
      </div>


      <div class="form-group">
        <label class=" control-label" for="experimental_units_number">Experimental units <span class="red">*</span></label>
        <input placeholder="Experimental units" id="experimental_units_number" name="experimental_units_number" value="<?= _post("experimental_units_number"); ?>" type="number" class="form-control int input-md required">
        <span class="help-block">
          Number of experimental units
        </span>
      </div>
      <div class="form-group">
        <label class=" control-label" for="description">Description</label>
        <textarea name="description" id="description" class="form-control" cols="30" rows="3"><?= _post("description"); ?></textarea>
        <span class="help-block"></span>
      </div>
      <div class="form-group">
        <label class=" control-label" for="file">Select Variable Group <span class="red">*</span></label>
        <?php
        printSelect("variableGroup", _post("variableGroup"), $variableGroups, null, "select2",null );
        ?>
        <span class="help-block">
        </span>
      </div>
      <hr>
      <!-- Button -->
      <div class="form-group">
        <div class="">
          <input type="submit" name="save" value="Continue" class="btn btn-shadow btn-primary">
        </div>
      </div>
      <hr>
      <span class="red">*</span> denotes a required field <br>
      This phenobook will be visible to your working group <i><?=  $__user->userGroup ?></i>
    </fieldset>
  </form>
</div>
</div>
<?php
require __ROOT."files/php/template/footer.php";
?>
