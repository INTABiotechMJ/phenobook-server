<?php
$login = true;
require "../../../files/php/config/require.php";
require "../../logic/class/Session/Recover.php";

if($_POST){
  $email = _post("email");
  $user = User::searchByEmail($email);
  if($user){
    $recover = new Recover();
    $recover->user = $user;
    $recover->datetime = stamp();
    Entity::save($recover);
    bgScript("test.php");
    redirect(__URL."?m=Recibirá instrucciones en su email");
  }else{
    redirect("passRecover.php?e=Email no encontrado");
  }
}
if(_get("m")){
  $alert->addError(_get("m"));
}
if(_get("e")){
  $alert->addError(_get("e"));
}
?>
<div class="container">
  <!--login modal-->
  <div id="loginModal" class="show" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
      <?php $alert->printAlert() ?>
      <div class="modal-content">
        <div class="modal-header">
        </div>
        <div class="modal-body">
          <form class="form valid center-block" action="<?= $_SERVER["PHP_SELF"];?>" method="POST">
            <div class="form-group">
              <input name="email" type="text" class="form-control auto required email input-lg" placeholder="Email">
            </div>
            <div class="form-group">
              <button class="btn btn-primary btn-lg btn-block btn-shadow">Enviar email de recuperación</button>
              <span class="pull-right"><a href="<?= __URL ?>">Volver al inicio</a></span>
            </div>
          </form>
        </div>
        <div class="modal-footer">

        </div>
      </div>
    </div>
  </div>

  <?php 
  require __ROOT."files/php/template/footer.php";
  ?>