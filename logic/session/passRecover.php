<?php
$login = true;
require "../../../files/php/config/require.php";
require "../../logic/class/Session/Recover.php";
require __ROOT."files/php/class/EMail.php";

if($_POST){
  $email = _post("email");
  $user = User::searchByEmail($email);
  if($user){
    $recover = new Recover();
    $recover->user = $user;
    $recover->datetime = stamp();
    $hash = hash("SHA256", uniqid());
    $recover->hash = $hash;

    $email_obj = new Email();
    $email_obj->email_to = $email;
    $email_obj->subject = "Password recovery - " . __TITLE;
    $email_obj->datetimeCreated = stamp();
    $body = "Para reestablecer su contraseña haga click en el siguiente link ";
    $body .= " <a href='".__URL_FULL."logic/session/setNewPass.php?id=$hash' target='_blank'>";
    $body .= __URL_FULL."logic/session/setNewPass.php?id=$hash</a> ";
    $body .= " o copie y pegue la dirección en su navegador.<br/>";
    $email_obj->body = $body;

    Entity::save($email_obj);
    Entity::save($recover);
    bgScript("test.php");
    redirect(__URL."?m=Recibirá un link en su email válido por 1 día");
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
  <div class="wrapper">
    <?php $alert->printAlert() ?>
    <form class="form-signin form valid center-block" action="<?= $_SERVER["PHP_SELF"];?>" method="POST">
      <h2 class="form-signin-heading">Password recover</h2>
      <input type="text" class="form-control auto required email" name="email" placeholder="Email" required="" autofocus="" />
      <button class="btn btn-lg btn-primary btn-block" type="submit">Send</button>
      <span>
        <a href="<?= __URL ?>">Inicio</a>
      </span>
    </form>
  </div>

  <?php
  require __ROOT."files/php/template/footer.php";
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
