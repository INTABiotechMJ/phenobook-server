<?php 
$admin = true;
require "../../../files/php/config/require.php";
$id = _request("id");


$users_ensayo = Entity::listMe("User", "active AND grupo = '".$__user->userGroup->id."'");
if(!empty($users_ensayo)){
  $users = obj2arr($users_ensayo);
}

$rels = Entity::listMe("UserPhenobook", "active AND libroCampo = '$id'");

$selectedUsers = array();


foreach((array)$rels as $rel){
  if(!empty($rel->user)){
    $selectedUsers[] =  $rel->user;
  }
}

if(!empty($selectedUsers)){
  $selectedUsers = obj2arr($selectedUsers);
}


$item = Entity::load("Phenobook", $id);
if($_POST){

  $rels = Entity::listMe("UserPhenobook", "active AND libroCampo = '$id'");
  foreach((array)$rels as $rel){
    $rel->active = "0";
    Entity::update($rel);
  }


  $usrs = _post("usuarios");
  foreach((array)$usrs  as $c){
    $pc = new UserPhenobook();
    $pc->libroCampo = $item;
    $pc->user = Entity::load("User", $c);
    Entity::save($pc);
  }

  $item->nombre = _post("nombre");
  $item->descripcion = _post("descripcion");


  if(!$alert->hasError){
    Entity::update($item);

    redirect("index.php?m=".__TRIAL_EDITED);
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
      <legend>Edit Phenobook</legend>

      <div class="form-group">
        <label class="col-md-4 control-label" for="nombre"><?= __TRIAL_EDIT_NAME ?> *</label>  
        <div class="col-md-5">
          <input id="nombre" name="nombre" value="<?= $item->nombre; ?>" type="text"  class="form-control input-md required">
          <span class="help-block"></span>  
        </div>
      </div>


      <div class="form-group ">
        <label class="col-md-4 control-label" for="usuarios"><?= __TRIAL_EDIT_ASSIGNED_USERS ?></label>  
        <div class="col-md-4">
          <?php
          printSelect("usuarios[]", $selectedUsers, $users, null, "select-multiple select2",'multiple' );
          ?>
          <span class="help-block"></span>  
        </div>
      </div>


      <div class="form-group">
        <label class="col-md-4 control-label" for="descripcion"><?= __TRIAL_EDIT_DESCRIPTIONS ?> *</label>  
        <div class="col-md-5">
          <textarea name="descripcion" id="descripcion" class="form-control" cols="30" rows="5"><?= $item->descripcion; ?></textarea>
          <span class="help-block"></span>  
        </div>
      </div>




      <!-- Button -->
      <div class="form-group">
        <div class="col-md-4 col-md-offset-4">
          <input type="submit" name="save" value="<?= __TRIAL_EDIT_SAVE ?>" class="btn btn-shadow btn-primary">
        </div>
      </div>

    </fieldset>
  </form>

</div>
</div>

<?php 
require __ROOT."files/php/template/footer.php";
?>
