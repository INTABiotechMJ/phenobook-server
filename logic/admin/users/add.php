<?php 
$admin = true;
require "../../../files/php/config/require.php";
$grupos = obj2arr(Entity::listMe("Group","active"));

if($_POST){
  $email = _post("email");
  $password = _post("password");

  if(User::searchByEmail($email)){
    $alert->addError("El email $email ya se encuentra registrado");
  }

  $item = new User();
  $item->email = $email;
  $item->name = _post("name");
  $item->lang = _post("lang");
  $item->lastName = _post("lastName");
  $item->pass = $password;
  $item->type = _post("type");
  if($__user->isSuperAdmin()){
    $item->grupo = Entity::load("Group", _post("grupo"));
  }else{
    $item->grupo = $__user->userGroup;
  }

  if(_post("sendEmail")){
    $email_obj = new Email();
    $email_obj->email_to = $item->email;
    $email_obj->subject = "Acceso al sistema " . __TITLE;
    $email_obj->datetimeCreated = stamp();
    $body = "Se ha creado un usuario para acceso al sistema. Para acceder haga click en ";
    $body .= " <a href='".__URL_FULL."' target='_blank'>".__URL_FULL."</a> ";
    $body .= " o copie y pegue la dirección en su navegador.<br/>";
    $body .= " Su usuario: <b>$email</b><br/>";
    $body .= " Su contraseña: <b>$item->pass</b>";
    $email_obj->body = $body;
  }

  if(!$alert->hasError){
    Entity::save($item);
    if(_post("sendEmail")){
      Entity::save($email_obj);
    }
    redirect("index.php?m=Usuario agregado");
  }

}else{
  $password = randomPassword(4);
}

function randomPassword($length) {
  $alphabet = "0123456789";
  $pass = array(); 
  $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
  for ($i = 0; $i < $length; $i++) {
    $n = rand(0, $alphaLength);
    $pass[] = $alphabet[$n];
  }
  return implode($pass); //turn the array into a string
}

?>

<div class="row">
  <div class="col-sm-8 col-md-offset-1">
    <form class="form-horizontal valid" method="POST" action="<?= $_SERVER["PHP_SELF"]?>">
      <fieldset>
        <!-- Form Name -->
        <legend><?= __USER_CRUD_ADD ?></legend>
        <!-- Text input-->
        <div class="form-group">
          <label class="col-md-4 control-label" for="name"><?= __USER_CRUD_ADD_NAME ?> *</label>  
          <div class="col-md-4">
            <input id="name" name="name" value="<?= _post("name"); ?>" type="text"  class="form-control input-md required">
            <span class="help-block"></span>  
          </div>
        </div>

        <div class="form-group">
          <label class="col-md-4 control-label" for="lastName"><?= __USER_CRUD_ADD_LAST_NAME ?> *</label>  
          <div class="col-md-4">
            <input id="lastName" name="lastName" value="<?= _post("lastName"); ?>" type="text"  class="form-control input-md required">
            <span class="help-block"></span>  
          </div>
        </div>

        <div class="form-group">
          <label class="col-md-4 control-label" for="email"><?= __USER_CRUD_ADD_EMAIL ?> *</label>  
          <div class="col-md-4">
            <input id="email" name="email" value="<?= _post("email"); ?>" type="text"  class="form-control input-md email required">
            <span class="help-block"></span>  
          </div>
        </div>

        <div class="form-group">
          <label class="col-md-4 control-label" for="email"><?= __USER_CRUD_ADD_LANG ?> *</label>  
          <div class="col-md-4">
            <?php 
            $langs = array(0 => "English",1 => "Spanish");
            printSelect("lang", _post("lang"), $langs, null, "select","" );
            ?>
            <span class="help-block"></span>  
          </div>
        </div>

        <?php 
        if($__user->isSuperAdmin()){
         ?>
         <div class="form-group ">
          <label class="col-md-4 control-label" for="usuarios"><?= __USER_CRUD_ADD_GROUP ?></label>  
          <div class="col-md-4">
            <?php
            printSelect("grupo", null, $grupos, null, "select","" );
            ?>
            <span class="help-block"></span>  
          </div>
        </div>
        <?php } ?>


        <div class="form-group">
          <label class="col-md-4 control-label" for="password"><?= __USER_CRUD_ADD_PASS ?> *</label>  
          <div class="col-md-4">
            <input minlength="4" maxlength="4" value="<?= $password;?>" id="password" name="password" value="<?= _post("password"); ?>" type="text"  class="form-control input-md required">
          </div>
        </div>

        <div class="form-group">
          <label class="col-md-4 control-label" for="password"><?= __USER_CRUD_ADD_USER_TYPE ?> *</label>  
          <div class="col-md-4">

            <div class="btn-group" data-toggle="buttons">
              <?= form_option_grouped(__USER_CRUD_ADD_USER_TYPE_OPERATOR, "type", "type1", User::$TYPE_OPERADOR, _post("type"), true ); ?>
              <?= form_option_grouped(__USER_CRUD_ADD_USER_TYPE_ADMIN, "type", "type2", User::$TYPE_ADMIN, _post("type")); ?>
            </div><!--END btn-group-->
            <span class="help-block">

            </span>
          </div><!--END col-md-4 -->
        </div>

        <div class="form-group hide">
          <label class="col-md-4 control-label" for="sendEmail"></label>  
          <div class="col-md-6">
            <?=  form_check("sendEmail" , __USER_CRUD_ADD_SEND_EMAIL,_post("sendEmail")) ?>
          </div>
        </div>


        <!-- Button -->
        <div class="form-group">
          <div class="col-md-4 col-md-offset-4">
            <input type="submit" name="save" value="<?= __USER_CRUD_ADD_SAVE ?>" class="btn btn-shadow btn-primary">
          </div>
        </div>

      </fieldset>
    </form>

  </div>
</div>

<?php 
require __ROOT."files/php/template/footer.php";
?>