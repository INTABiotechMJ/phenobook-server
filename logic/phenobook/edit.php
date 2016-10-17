<?php
$admin = true;
require "../../files/php/config/require.php";
$userGroups = obj2arr(Entity::listMe("UserGroup","active"));
$informativeVariables = obj2arr(Entity::listMe("Variable","active AND userGroup = '" . $__user->userGroup->id . "' AND isInformative "));
$variables = obj2arr(Entity::listMe("Variable","active AND userGroup = '" . $__user->userGroup->id . "' AND NOT isInformative"));

$item = Entity::load("Phenobook",_request("id"));
$selectedVariables = array();
$selectedInformativeVariables = array();
$oldSelected = Entity::listMe("PhenobookVariable","phenobook = '$item->id' AND active");
foreach((array)$oldSelected as $os){
  if($os->variable->isInformative){
    $selectedInformativeVariables[] = $os->variable;
  }else{
    $selectedVariables[] = $os->variable;
  }
}
$selectedVariables = obj2arr($selectedVariables);
$selectedInformativeVariables = obj2arr($selectedInformativeVariables);

if($_POST){
  Entity::begin();
  $item->name = _post("name");
  $item->description = _post("description");
  $item->experimental_units_number = _post("experimental_units_number");
  $item->experimental_unit_name = _post("experimental_unit_name");

  $phenobookVariables = _post("variables")?_post("variables"):array();
  $phenobookInformativeVariables = _post("informativeVariables")?_post("informativeVariables"):array();
  $newSelected = array_merge($phenobookVariables, $phenobookInformativeVariables);


  if(!$alert->hasError){
    Entity::update($item);

    foreach((array)$oldSelected as $os){
      $os->active = 0;
      Entity::update($os);
    }
    foreach((array)$newSelected as $ns){
      $pv = new PhenobookVariable();
      $pv->phenobook = $item;
      $pv->variable = Entity::load("Variable",$ns);
      Entity::save($pv);
    }
    Entity::commit();
    redirect("index.php?id=$item->id&m=Phenobook edited");
  }

}


?>
<div class="row">

  <div class="col-sm-8 col-md-offset-1">
    <form autocomplete="off" enctype="multipart/form-data" class="form-horizontal valid"
    method="POST" action="<?= $_SERVER["PHP_SELF"]?>">
    <input type="hidden" name="id" value="<?= _request("id") ?>">
    <fieldset>
      <!-- Form Name -->
      <legend>Edit Phenobook <i><?= $item ?></i></legend>

      <div class="form-group">
        <label class=" control-label" for="name">Name <span class="red">*</span></label>
        <input placeholder="Phenobook name" id="name" name="name" value="<?= $item->name; ?>" type="text" class="form-control input-md required">
      </div>


      <div class="form-group">
        <label class=" control-label" for="experimental_units_number">Experimental units <span class="red">*</span></label>
        <input placeholder="Experimental units" id="experimental_units_number" name="experimental_units_number" value="<?= $item->experimental_units_number; ?>" type="number" class="form-control int input-md required">
        <span class="help-block">
          Number of experimental units
        </span>
      </div>

      <div class="form-group">
        <label class=" control-label" for="experimental_unit_name">Experimental unit name</label>
        <input placeholder="Experimental unit name" id="experimental_unit_name" name="experimental_unit_name" value="<?= $item->experimental_unit_name; ?>" type="text" class="form-control input-md">
        <span class="help-block">
          If left blank, it will use just <i>Experimental Unit</i> as name
        </span>
      </div>

      <div class="form-group">
        <label class=" control-label" for="description">Description</label>
        <textarea name="description" id="description" class="form-control" cols="30" rows="3"><?= $item->description; ?></textarea>
        <span class="help-block"></span>
      </div>

      <div class="form-group">
        <label class=" control-label" for="file">Select informative variables</label>
        <?php
        printSelect("informativeVariables[]", $selectedInformativeVariables, $informativeVariables, null, "select2 select-multiple","multiple" );
        ?>
        <span class="help-block">
          Informative variables will serve as a guide to the user when making observations
        </span>
      </div>

      <div class="form-group">
        <label class=" control-label" for="file">Select variables <span class="red">*</span></label>
        <?php
        printSelect("variables[]", $selectedVariables, $variables, null, "select2 select-multiple","multiple" );
        ?>
        <span class="help-block">
          Variables that will be recorded throught observation. At least one is required.
        </span>
      </div>

      <hr>
      <!-- Button -->
      <div class="form-group">
        <div class="">
          <input type="submit" name="save" value="Save" class="btn btn-shadow btn-primary">
        </div>
      </div>
      <hr>
      <span class="red">*</span> denotes a required field

    </fieldset>
  </form>
</div>
</div>
<?php
require __ROOT."files/php/template/footer.php";
?>
