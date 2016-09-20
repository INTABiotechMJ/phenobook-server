<?php 
$admin = true;
require "../../../files/php/config/require.php";
$item = Entity::load("User", _request("id"));
$grupos = obj2arr(Entity::listMe("Group","active"));
$checkedAdmin = "";
$activeAdmin = "";
$checkedOperador = "";
$activeOperador = "";
if($item->type == User::$TYPE_OPERADOR){
  $checkedOperador = "checked";
  $activeOperador = "active";
}
if($item->type == User::$TYPE_ADMIN){
  $checkedAdmin = "checked";
  $activeAdmin = "active";
}

if($_POST){
  $email = _post("email");
  $item->email = $email;
  $item->lang = _post("lang");
  $item->name = _post("name");
  $item->lastName = _post("lastName");
  $item->type = _post("type");
  $item->grupo = Entity::load("Group", _post("grupo"));

  if(User::searchByEmail($email, $item->id)){
    $alert->addError("El email $email ya se encuentra registrado en otro usuario");
  }

  if(!$alert->hasError){
    Entity::update($item);
    redirect("index.php?m=Usuario editado");
  }
}

?>

<div class="row">
  <div class="col-sm-8 col-md-offset-1">
    <form class="form-horizontal valid" method="POST" action="<?= $_SERVER["PHP_SELF"]?>">
      <input type="hidden" name="id" value="<?= _request("id"); ?>">
      <fieldset>
        <!-- Form Name -->
        <legend><?= __USER_CRUD_EDIT_TITLE ?></legend>
        <!-- Text input-->
        <div class="form-group">
          <label class="col-md-4 control-label" for="name"><?= __USER_CRUD_EDIT_NAME ?> *</label>  
          <div class="col-md-4">
            <input id="name" value="<?= $item->name ?>" name="name" type="text"  class="form-control input-md required">
            <span class="help-block"></span>  
          </div>
        </div>

        <div class="form-group">
          <label class="col-md-4 control-label" for="lastName"><?= __USER_CRUD_EDIT_LAST_NAME ?> *</label>  
          <div class="col-md-4">
            <input id="lastName" value="<?= $item->lastName ?>" name="lastName" type="text"  class="form-control input-md required">
            <span class="help-block"></span>  
          </div>
        </div>

        <div class="form-group">
          <label class="col-md-4 control-label" for="email">Email *</label>  
          <div class="col-md-4">
            <input id="email" value="<?= $item->email ?>" name="email" type="text"  class="form-control input-md email required">
            <span class="help-block"></span>  
          </div>
        </div>

        <?php 
        if($__user->isSuperAdmin()){
         ?>
         <div class="form-group ">
          <label class="col-md-4 control-label" for="usuarios"><?= __USER_CRUD_EDIT_GROUP ?></label>  
          <div class="col-md-4">
            <?php
            printSelect("grupo", $item->grupo->id, $grupos, null, "select","" );
            ?>
            <span class="help-block"></span>  
          </div>
        </div>
        <?php } ?>

        <div class="form-group">
          <label class="col-md-4 control-label" for="email"><?= __USER_CRUD_ADD_LANG ?> *</label>  
          <div class="col-md-4">
            <?php 
            $langs = array(0 => "English",1 => "Spanish");
            printSelect("lang", $item->lang, $langs, null, "select","" );
            ?>
            <span class="help-block"></span>  
          </div>
        </div>


        <div class="form-group">
          <label class="col-md-4 control-label" for="password"><?= __USER_CRUD_EDIT_USER_TYPE ?> *</label>  
          <div class="col-md-4">

            <div class="btn-group" data-toggle="buttons">

              <label class="btn btn-default <?= $activeOperador ?>">
                <input type="radio" <?= $checkedOperador ?> name="type" id="tipo2" value="<?= User::$TYPE_OPERADOR;?>">
                <?= __USER_CRUD_EDIT_TYPE_OPERATOR ?>
              </label>              

              <label class="btn btn-default <?= $activeAdmin ?>">
                <input type="radio" <?= $checkedAdmin ?> name="type" id="tipo1" value="<?= User::$TYPE_ADMIN;?>">
                <?= __USER_CRUD_EDIT_TYPE_ADMIN ?>
              </label>

            </div><!--END btn-group-->

          </div><!--END col-md-4 -->


        </div>

        <!-- Button -->
        <div class="form-group">

          <div class="col-md-4 col-md-offset-4">
            <input type="submit" name="save" value="<?= __USER_CRUD_EDIT_SAVE ?>" class="btn btn-shadow btn-primary"> 
            <a href="index.php" class="btn btn-shadow btn-default"><?= __USER_CRUD_EDIT_BACK ?></a>
          </div>
        </div>

      </fieldset>
    </form>

  </div>
</div>

<?php 
require __ROOT."files/php/template/footer.php";
?>