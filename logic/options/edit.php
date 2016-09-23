<?php
$admin = true;
require "../../files/php/config/require.php";
$id_opcion = _request("id");

$item = Entity::load("FieldOption", $id_opcion);
if($_POST){
  $item->name = _post("name");
  if(!$alert->hasError){
    Entity::update($item);
    redirect("index.php?id=".$item->genericVariable->id."&m=Option edited");
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
      <legend>Edit option <?= i($item) ?> from variable <?=  i($item->genericVariable) ?> </legend>

      <div class="form-group">
        <label class="col-md-4 control-label" for="name">Name <span class="red">*</span></label>
        <div class="col-md-5">
          <input id="name" name="name" value="<?= $item->name; ?>" type="text"  class="form-control input-md required">
          <span class="help-block"></span>
        </div>
      </div>


      <!-- Button -->
      <div class="form-group">
        <div class="col-md-4 col-md-offset-4">
          <input type="submit" name="save" value="Save" class="btn btn-primary">
        </div>
      </div>

    </fieldset>
  </form>
</div>
</div>
<?php
require __ROOT."files/php/template/footer.php";
?>
