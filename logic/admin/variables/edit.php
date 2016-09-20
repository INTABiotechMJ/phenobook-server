<?php 
$admin = true;
require "../../../files/php/config/require.php";
$id = _request("id");

$operadores = obj2arr(Entity::listMe("User", "active AND type = '".User::$TYPE_OPERADOR. "'"));

$item = Entity::load("Campana", $id);

//operadores
$rels = Entity::listMe("ManyCampanaUser", "active AND campana = '$id'");
$selectedOperadores = array();
foreach((array)$rels as $rel){
  $selectedOperadores[] =  $rel->user;
}
if(!empty($selectedOperadores)){
  $selectedOperadores = obj2arr($selectedOperadores);
}
//end operadores

if($_POST){
  
  $item->nombre = _post("nombre");


  $rels = Entity::listMe("ManyCampanaUser", "active AND campana = '$id'");
  foreach((array)$rels as $rel){
    $rel->active = "0";
    Entity::update($rel);
  }


  $ops = _post("operadores");
  foreach((array)$ops  as $o){
    $mcu = new ManyCampanaUser();
    $mcu->campana = $item;
    $mcu->user = Entity::load("User", $o);
    Entity::save($mcu);
  }

  if(!$alert->hasError){
    Entity::update($item);

    redirect("index.php?m=".__VARIABLE_CRUD_EDIT_EDITED);
  }

}

?>

<div class="row">
  <div class="col-sm-8 col-md-offset-1">
    <form autocomplete="off" enctype="multipart/form-data" class="form-horizontal valid" 
    method="POST" action="<?= $_SERVER["PHP_SELF"]?>">
    <input type="hidden" name="id" value="<?= _request("id"); ?>">
    <fieldset>
      <!-- Form Name -->
      <legend><?= __VARIABLE_CRUD_EDIT_TITLE ?></legend>

      <div class="form-group">
        <label class="col-md-4 control-label" for="nombre"><?= __VARIABLE_CRUD_EDIT_NAME ?> *</label>  
        <div class="col-md-5">
          <input id="nombre" name="nombre" value="<?= $item->nombre; ?>" type="text"  class="form-control input-md required">
          <span class="help-block"></span>  
        </div>
      </div>


      <div class="form-group">
        <label class="col-md-4 control-label" for="operadores"><?= __VARIABLE_CRUD_EDIT_ASSIGNED_OPERATORS ?></label>  
        <div class="col-md-5">
          <?php
          printSelect("operadores[]", $selectedOperadores, $operadores, null, "selectpicker required",'data-live-search="true" multiple data-selected-text-format="count>3"' );
          ?>
          <span class="help-block">

          </span>  
        </div>
      </div>

      <!-- Button -->
      <div class="form-group">
        <div class="col-md-4 col-md-offset-4">
          <input type="submit" name="save" value="<?= __VARIABLE_CRUD_EDIT_SAVE ?>" class="btn btn-shadow btn-primary">
        </div>
      </div>

    </fieldset>
  </form>

</div>
</div>

<?php 
require __ROOT."files/php/template/footer.php";
?>
