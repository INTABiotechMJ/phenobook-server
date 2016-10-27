<?php
require "../../files/php/config/require.php";
$item = Entity::load("Phenobook",_request("id"));
if($item->userGroup->id != $__user->userGroup->id){
  raise404();
}

$variables = Entity::listMe("Variable","active AND userGroup = '".$__user->userGroup->id."'");
$selectedVariables = array();
$selectedPhenobookVariables = Entity::listMe("PhenobookVariable","phenobook = '$item->id' AND active");

foreach((array)$selectedPhenobookVariables as $spv){
  $selectedVariables[] = $spv->variable;
}

if($_POST){
  Entity::begin();
  $item->name = _post("name");
  $item->description = _post("description");
  $item->experimental_units_number = _post("experimental_units_number");
  $item->experimental_unit_name = _post("experimental_unit_name");

  $newVariables = _post("to");

  if(!$alert->hasError){
    Entity::update($item);

    foreach((array)$selectedPhenobookVariables as $os){
      $os->active = 0;
      Entity::update($os);
    }
    foreach((array)$newVariables as $ns){
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
        <label class="control-label" for="variables">Variables</label>
        <div class="row">
          <div class="col-xs-5">
            <select name="from[]" id="search" class="form-control" size="8" multiple="multiple">
              <?php
              foreach((array)$variables as $v){
                echo "<option value='$v->id'>".$v->__toStringLong()."</option>";
              }
              ?>
            </select>
          </div>

          <div class="col-xs-2">
            <button type="button" id="search_rightAll" class="btn btn-block"><i class="glyphicon glyphicon-forward"></i></button>
            <button type="button" id="search_rightSelected" class="btn btn-block"><i class="glyphicon glyphicon-chevron-right"></i></button>
            <button type="button" id="search_leftSelected" class="btn btn-block"><i class="glyphicon glyphicon-chevron-left"></i></button>
            <button type="button" id="search_leftAll" class="btn btn-block"><i class="glyphicon glyphicon-backward"></i></button>
          </div>

          <div class="col-xs-5">
            <select name="to[]" id="search_to" class="form-control" size="8" multiple="multiple">
                <?php
                foreach((array)$selectedVariables as $v){
                  echo "<option value='$v->id'>".$v->__toStringLong()."</option>";
                }
                ?>
            </select>

            <div class="row" style="margin-top:5px;">
              <div class="col-sm-6">
                <button type="button" id="search_move_up" class="btn btn-block"><i class="glyphicon glyphicon-arrow-up"></i></button>
              </div>
              <div class="col-sm-6">
                <button type="button" id="search_move_down" class="btn btn-block col-sm-6"><i class="glyphicon glyphicon-arrow-down"></i></button>
              </div>
            </div>

          </div>
        </div>

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

<script type="text/javascript">
jQuery(document).ready(function($) {
  $('#search').multiselect({
    keepRenderingSort: true,
    search: {
      left: '<input type="text" name="q" class="form-control" placeholder="Search..." />',
      //right: '<input type="text" name="q" class="form-control" placeholder="Search..." />',
    }
  });
});
</script>
