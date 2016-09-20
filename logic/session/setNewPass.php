<?php
$login = true;
require "../../../files/php/config/require.php";
require "../../logic/class/Session/Recover.php";
require __ROOT."files/php/class/EMail.php";

$email = "";
$recover = Recover::loadValid(_request("id"));
if(!$recover){
  redirect(__URL."?e=El link de recuperación no es válido o ha expirado");
}else{
  $email = $recover->user->email;
}

if($_POST){

  $user = $recover->user;
  if($user){
    if(_post("pass") != _post("pass2")){
      $alert->addError("Las contraseñas no coinciden");
    }
    if(!$alert->hasError){
      $user->pass = _post("pass");
      Entity::update($user);
      redirect(__URL."?m=Contraseña actualizada");
    }
  }
}
if(_get("m")){
  $alert->addError(_get("m"));
}
if(_get("e")){
  $alert->addError(_get("e"));
}
?>
<style>
  .wrapper {  
    margin-top: 10px;
    margin-bottom: 80px;
  }
  .form-signin input, .form-signin button{
    margin: 10px;
  }
  .form-signin {
    max-width: 380px;
    padding: 15px 35px 45px;
    margin: 0 auto;
    background-color: #fff;
    border: 1px solid rgba(0,0,0,0.1);  
  }
</style>
<div class="container">
  <div class="wrapper">
    <?php $alert->printAlert() ?>
    <form class="form-signin form valid center-block" action="<?= $_SERVER["PHP_SELF"];?>" method="POST">
      <h3 class="form-signin-heading">Reestablecer contraseña</h3>
      <div class="center">
        <?= $email; ?>
      </div>
      <input type="hidden" name="id" value="<?= _request("id"); ?>">
      <input minlength="5"  type="password" class="form-control required" name="pass" placeholder="Contraseña" required=""/>
      <input minlength="5" type="password" class="form-control required" name="pass2" placeholder="Repetir contraseña" required=""/>
      <button class="btn btn-lg btn-primary btn-block" type="submit">Cambiar</button>   
    </form>
  </div>

  <?php 
  require __ROOT."files/php/template/footer.php";
  ?>