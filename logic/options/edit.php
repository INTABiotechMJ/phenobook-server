<?php
$admin = true;
require "../../files/php/config/require.php";
$id = _request("id");
$item = Entity::load("FieldOption", $id);
if($_POST){
  $item->name = _post("name");
  if(!$alert->hasError){
    Entity::update($item);
    redirect("index.php?id=".$item->variable->id."&m=Option edited");
  }

}
?>
<div class='row'>

  <div class='col-md-8 col-xs-6'>
    <legend>
      Edit option <?= i($item) ?> from variable <?=  i($item->variable) ?>
    </legend>
  </div>
  <div class='col-md-1'>

  </div>

  <div class='col-md-4'>
    <a href='index.php?id=<?= $item->variable->id ?>' class='btn btn-default '>Back to options</a>
  </div>
</div>
<div class="row">
  <div class="col-sm-8 col-md-offset-1">
    <form autocomplete="off" enctype="multipart/form-data" class="form-horizontal valid"
    method="POST" action="<?= $_SERVER["PHP_SELF"]?>">
    <input type="hidden" name="id" value="<?= _request("id") ?>">
    <fieldset>


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
