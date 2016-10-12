<?php
$admin = true;
require "../../files/php/config/require.php";
$id = _request("id");
$variable = Entity::load("Variable", $id);
if($_POST){

  $item = new FieldOption();
  $item->name = _post("name");
  $item->variable = $variable;
  if(!$alert->hasError){
    Entity::save($item);
    if(_post("saveOther")){
      redirect("add.php?id=$id&m=Option added");
    }
    redirect("index.php?id=$id&m=Option added");
  }
}
?>
<div class='row'>

  <div class='col-md-8 col-xs-6'>
    <legend>New option for variable <?=  i($variable) ?> </legend>
  </div>
  <div class='col-md-1'>

  </div>

  <div class='col-md-4'>
    <a href='index.php?id=<?= $variable->id ?>' class='btn btn-default '>Back to options</a>
  </div>
</div>

<div class="row">
  <div class="col-sm-8 col-md-offset-1">
    <form autocomplete="off" enctype="multipart/form-data" class="form-horizontal valid"
    method="POST" action="<?= $_SERVER["PHP_SELF"]?>">
    <input type="hidden" name="id" value="<?= _request("id") ?>">
    <fieldset>
      <!-- Form Name -->

      <div class="form-group">
        <label class="col-md-4 control-label" for="name">Name <span class="red">*</span></label>
        <div class="col-md-5">
          <input id="name" name="name" value="<?= _post("name"); ?>" type="text"  class="form-control input-md required">
          <span class="help-block"></span>
        </div>
      </div>
      <!-- Button -->
      <div class="form-group">
        <div class="col-md-4 col-md-offset-4">
          <input type="submit" name="save" value="Save" class="btn btn-primary">
          <input type="submit" name="saveOther" value="Save and add another" class="btn btn-primary">
        </div>
      </div>

    </fieldset>
  </form>
</div>
</div>
<?php
require __ROOT."files/php/template/footer.php";
?>
