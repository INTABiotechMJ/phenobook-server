<?php 
$admin = true;
require "../../../files/php/config/require.php";
$id_opcion = _request("id_opcion");

$item = Entity::load("Opcion", $id_opcion);
if($_POST){

  $item->nombre = _post("nombre");
  $item->variable = $variable;
  if(!$alert->hasError){
    Entity::save($item);
    redirect("index.php?id_variable=".$item->variable->id."&m=".__OPTION_CRUD_EDIT_EDITTED);
  }

}
?>

<div class="row">
  <div class="col-sm-8 col-md-offset-1">
    <form autocomplete="off" enctype="multipart/form-data" class="form-horizontal valid" 
    method="POST" action="<?= $_SERVER["PHP_SELF"]?>">
    <input type="hidden" name="id_opcion" value="<?= _request("id_opcion") ?>">
    <fieldset>
      <!-- Form Name -->
      <legend><?= __OPTIONS_CRUD_EDIT_TITLE_1 ?> <?= i($opcion) ?> <?= __OPTIONS_CRUD_EDIT_TITLE_2 ?> <?=  i($item->variable) ?> </legend>

      <div class="form-group">
        <label class="col-md-4 control-label" for="nombre"><?= __OPTIONS_CRUD_EDIT_NAME ?> *</label>  
        <div class="col-md-5">
          <input id="nombre" name="nombre" value="<?= $item->nombre; ?>" type="text"  class="form-control input-md required">
          <span class="help-block"></span>  
        </div>
      </div>


      <!-- Button -->
      <div class="form-group">
        <div class="col-md-4 col-md-offset-4">
          <input type="submit" name="save" value="<?= __OPTIONS_CRUD_EDIT_SAVE ?>" class="btn btn-shadow btn-primary">
        </div>
      </div>

    </fieldset>
  </form>
</div>
</div>
<?php 
require __ROOT."files/php/template/footer.php";
?>