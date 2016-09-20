<?php 
$admin = true;
require "../../../files/php/config/require.php";
$id_variable = _request("id_variable");
$variable = Entity::load("Variable", $id_variable);
if($_POST){

  $item = new Opcion();
  $item->nombre = _post("nombre");
  $item->variable = $variable;
  if(!$alert->hasError){
    Entity::save($item);
    redirect("index.php?id_variable=$id_variable&m=Opcion agregada");
  }

}
?>

<div class="row">
  <div class="col-sm-8 col-md-offset-1">
    <form autocomplete="off" enctype="multipart/form-data" class="form-horizontal valid" 
    method="POST" action="<?= $_SERVER["PHP_SELF"]?>">
    <input type="hidden" name="id_variable" value="<?= _request("id_variable") ?>">
    <fieldset>
      <!-- Form Name -->
      <legend><?= __OPTIONS_CRUD_NEW_MESSAGE ?> <?=  i($variable) ?> </legend>
  
      <div class="form-group">
        <label class="col-md-4 control-label" for="nombre"><?= __OPTIONS_CRUD_NAME ?> *</label>  
        <div class="col-md-5">
          <input id="nombre" name="nombre" value="<?= _post("nombre"); ?>" type="text"  class="form-control input-md required">
          <span class="help-block"></span>  
        </div>
      </div>


      <!-- Button -->
      <div class="form-group">
        <div class="col-md-4 col-md-offset-4">
          <input type="submit" name="save" value="<?= __OPTIONS_CRUD_SAVE ?>" class="btn btn-shadow btn-primary">
        </div>
      </div>

    </fieldset>
  </form>
</div>
</div>
<?php 
require __ROOT."files/php/template/footer.php";
?>