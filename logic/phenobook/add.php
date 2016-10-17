<?php
$admin = true;
require "../../files/php/config/require.php";
$userGroups = obj2arr(Entity::listMe("UserGroup","active"));
$informativeVariables = obj2arr(Entity::listMe("Variable","active AND userGroup = '" . $__user->userGroup->id . "' AND isInformative "));
$variables = obj2arr(Entity::listMe("Variable","active AND userGroup = '" . $__user->userGroup->id . "' AND NOT isInformative"));

if($_POST){
  Entity::begin();
  $item = new Phenobook();
  $item->name = _post("name");
  $item->description = _post("description");
  $item->experimental_units_number = _post("experimental_units_number");
  $item->experimental_unit_name = _post("experimental_unit_name");
  $item->stamp = stamp();
  $item->userGroup = $__user->userGroup;

  $phenobookVariables = _post("variables");
  $phenobookInformativeVariables = _post("informativeVariables");

  if(!$alert->hasError){
    Entity::save($item);
    $all = array_merge($phenobookVariables, $phenobookInformativeVariables);
    foreach((array)$all as $v){
      $pv = new PhenobookVariable();
      $pv->phenobook = $item;
      $pv->variable = Entity::load("Variable",$v);
      Entity::save($pv);
    }

    Entity::commit();
    if(_post("save-finish")){
      redirect("index.php?id=$item->id&m=Phenobook added");
    }
    redirect("load.php?informative=1&id=$item->id&m=Phenobook added. You can complete informative variables now");
  }
}

$users = obj2arr(Entity::listMe("User","active AND 1"));

?>
<div class="row">

  <div class="col-sm-8 col-md-offset-1 col-xs-10 col-xs-offset-1">
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
        <label class=" control-label" for="experimental_unit_name">Experimental unit name</label>
        <input placeholder="Experimental unit name" id="experimental_unit_name" name="experimental_unit_name" value="<?= _post("experimental_unit_name"); ?>" type="text" class="form-control input-md">
        <span class="help-block">
          If left blank, it will use just <i>Experimental Unit</i> as name
        </span>
      </div>

      <div class="form-group">
        <label class=" control-label" for="description">Description</label>
        <textarea name="description" id="description" class="form-control" cols="30" rows="3"><?= _post("description"); ?></textarea>
        <span class="help-block"></span>
      </div>
      <div class="form-group">
        <label class=" control-label" for="file">Select informative variables</label>
        <?php
        printSelect("informativeVariables[]", _post("informativeVariables"), $informativeVariables, null, "select2 select-multiple","multiple" );
        ?>
        <span class="help-block">
          Informative variables will serve as a guide to the user when making observations
        </span>
      </div>

      <div class="form-group">
        <label class=" control-label" for="file">Select variables <span class="red">*</span></label>
        <?php
        printSelect("variables[]", _post("variables"), $variables, null, "select2 select-multiple","multiple" );
        ?>
        <span class="help-block">
          Variables that will be recorded throught observation. At least one is required.
        </span>
      </div>

      <hr>
      <!-- Button -->
      <div class="form-group">
        <div class="">
          <input type="submit" name="save-finish" value="Save and finish" class="btn btn-shadow btn-primary">
          <input type="submit" name="save" value="Save and complete informative variables values" class="btn btn-shadow btn-primary">
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
