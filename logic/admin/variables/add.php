<?php 
$admin = true;
require "../../../files/php/config/require.php";

if($_POST){

  $item = new Variable();
  $item->nombre = _post("nombre");

  $dir = __ROOT."files/uploads/" . date("Y") . "/". date("m") ."/";

  if (!file_exists($dir)) {
    mkdir($dir, 0777, true);
  }

  $fileName = time() . rand(0, 10000) .".". ext($_FILES["archivo"]["name"]);
  $res = subir($_FILES["archivo"], $dir.$fileName);
  if($res){
    $alert->addError($res);
  }


  if(!$alert->hasError){
    Entity::save($item);


    $importacion = new Importacion();
    $importacion->archivo = $fileName;
    $importacion->path = "$dir$fileName";
    $importacion->ensayo = $item;

    Entity::save($importacion);
    redirect("add_result.php?id=$item->id&m=Ensayo agregado");
  }

}
?>

<div class="row">
  <div class="col-sm-8 col-md-offset-1">
    <form autocomplete="off" enctype="multipart/form-data" class="form-horizontal valid" 
    method="POST" action="<?= $_SERVER["PHP_SELF"]?>">
    <fieldset>
      <!-- Form Name -->
      <legend><?= __TRIAL_CRUD_ADD_TITLE ?></legend>

      <div class="form-group">
        <label class="col-md-4 control-label" for="nombre"><?= __TRIAL_CRUD_ADD_NAME ?> *</label>  
        <div class="col-md-5">
          <input id="nombre" name="nombre" value="<?= _post("nombre"); ?>" type="text"  class="form-control input-md required">
          <span class="help-block"></span>  
        </div>
      </div>


      <div class="form-group">
        <label class="col-md-4 control-label" for="archivo"><?= __TRIAL_CRUD_ADD_FILE ?> *</label>  
        <div class="col-md-5">
          <input type="file" name="archivo" class="form-control input-md required">
          <span class="help-block">
          <?= __TRIAL_CRUD_ADD_INSTRUCTIONS ?>
          </span>  
        </div>
      </div>

      <!-- Button -->
      <div class="form-group">
        <div class="col-md-4 col-md-offset-4">
          <input type="submit" name="save" value="<?= __TRIAL_CRUD_ADD_SAVE ?>" class="btn btn-shadow btn-primary">
        </div>
      </div>

    </fieldset>
  </form>
</div>
</div>
<?php 
require __ROOT."files/php/template/footer.php";
?>