<?php
$admin = true;
require "../../../files/php/config/require.php";
$grupos = obj2arr(Entity::listMe("UserGroup","active"));

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
    $userGroups = _post("userGroups");
    foreach((array)$userGroups  as $o){
      $us_obj = new UserUserGroup();
      $us_obj->user = Entity::load("User", $item);
      $us_obj->userGroup = $o;
      Entity::save($us_obj);
    }
    if(_post("sendEmail")){
      Entity::save($email_obj);
    }
    redirect("index.php?m=User added");
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

<div class='row'>
	<div class='col-md-11'>
	</div>
	<div class='col-md-1'>
		<a href='index.php' class='btn btn-default '>Existents</a>
	</div>
</div>

<div class="row">
  <div class="col-sm-8 col-md-offset-1">
    <legend>Add user</legend>
    <form class="form-horizontal valid" method="POST" action="<?= $_SERVER["PHP_SELF"]?>">
      <fieldset>
        <!-- Form Name -->
        <!-- Text input-->
        <div class="form-group">
          <label class="control-label" for="name">First Name <span class="red">*</span></label>
          <input id="name" name="name" value="<?= _post("name"); ?>" type="text"  class="form-control input-md required">
          <span class="help-block"></span>
        </div>

        <div class="form-group">
          <label class="control-label" for="lastName">Last name <span class="red">*</span></label>
          <input id="lastName" name="lastName" value="<?= _post("lastName"); ?>" type="text"  class="form-control input-md required">
          <span class="help-block"></span>
        </div>

        <div class="form-group">
          <label class="control-label" for="userGroup">Groups</label>
          <?php
          printSelect("userGroups[]", _post("userGroups"), $grupos, null, "select select-multiple","multiple" );
          ?>
          <span class="help-block"></span>
        </div>

        <div class="form-group">
          <label class="control-label" for="email">Email <span class="red">*</span></label>
          <input id="email" name="email" value="<?= _post("email"); ?>" type="text"  class="form-control input-md email required">
          <span class="help-block">Will be used for login</span>
        </div>

        <div class="form-group">
          <label class="control-label" for="password">Password <span class="red">*</span> </label>
          <input minlength="4" maxlength="4" value="<?= $password;?>" id="password" name="password" value="<?= _post("password"); ?>" type="text"  class="form-control input-md required">
        </div>

        <div class="form-group">
          <label class="control-label" for="type">User type <span class="red">*</span> </label>
          <div class="btn-group" data-toggle="buttons">
            <?= form_option_grouped("Operator", "type", "type1", User::$TYPE_OPERADOR, _post("type"), true ); ?>
            <?= form_option_grouped("Administrator", "type", "type2", User::$TYPE_ADMIN, _post("type")); ?>
          </div><!--END btn-group-->
          <span class="help-block">

          </span>
        </div>

        <div class="form-group hide">
          <label class="control-label" for="sendEmail"></label>
          <?=  form_check("sendEmail" , "Send email",_post("sendEmail")) ?>
        </div>


        <!-- Button -->
        <div class="form-group">
          <input type="submit" name="save" value="Save" class="btn btn-primary">
        </div>
        <hr>
        <span class="red">*</span> denotes a required field


      </fieldset>
    </form>

  </div>
</div>

<?php
require __ROOT."files/php/template/footer.php";
?>
