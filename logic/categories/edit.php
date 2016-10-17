<?php
$admin = true;
require "../../files/php/config/require.php";
$id = _request("id");
$item = Entity::load("Category", $id);
if($_POST){
  $item->name = _post("name");
  if(!$alert->hasError){
    Entity::update($item);
    redirect("index.php?id=".$item->variable->id."&m=Category edited");
  }

}
?>
<div class='row'>

  <div class='col-md-8 col-xs-6'>
    <legend>
      Edit Category <?= i($item) ?> from variable <?=  i($item->variable) ?>
    </legend>
  </div>
  <div class='col-md-1'>

  </div>

  <div class='col-md-4'>
    <a href='index.php?id=<?= $item->variable->id ?>' class='btn btn-default '>Back to categories</a>
  </div>
</div>
<div class="row">
  <div class="col-sm-8 col-md-offset-1 col-xs-10 col-xs-offset-1">
    <form autocomplete="off" enctype="multipart/form-data" class="form-horizontal valid"
    method="POST" action="<?= $_SERVER["PHP_SELF"]?>">
    <input type="hidden" name="id" value="<?= _request("id") ?>">
    <fieldset>
      <div class="form-group">
        <label class="control-label" for="name">Name <span class="red">*</span></label>
        <input id="name" name="name" value="<?= $item->name; ?>" type="text"  class="form-control input-md required">
        <span class="help-block"></span>
      </div>
      <!-- Button -->
      <div class="form-group">
        <input type="submit" name="save" value="Save" class="btn btn-primary">
      </div>

    </fieldset>
  </form>
</div>
</div>
<?php
require __ROOT."files/php/template/footer.php";
?>
