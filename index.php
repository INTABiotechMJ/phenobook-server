<?php
$noLogin = true;
$noMenu = true;
require "files/php/config/require.php";

if($_POST){
  session_start();
  $email = _post("email");
  $pass = _post("pass");
  $user = Entity::search("User","email = '$email' AND pass = '$pass' AND active");
  if($user){
    $_SESSION["user".__HASH] = $user;
    redirect(__URL."logic/");
  }else{
    redirect("index.php?e=Wrong Password");
  }
}
if(_get("m")){
  $alert->addAviso(_get("m"));
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
      <h2 class="form-signin-heading">Login</h2>
      <input type="text" id="email" class="form-control auto required" name="email" placeholder="User"  autocomplete="off" />
      <input type="password" class="form-control required" name="pass" placeholder="Password" autofocus=""/>
      <button id="send" class="btn btn-lg btn-primary btn-block" type="submit">Login</button>
      <span class="hide">
        <a href="<?= __URL ?>logic/session/passRecover.php">Forgot password?</a>
      </span>

    </form>
  </div>
  <?php
  require __ROOT."files/php/template/footer.php";
  ?>
  <script>
    $(".cerrar").click(function(){
      Android.close();
      return false;
    });
    $("#send").click(function(){
      localStorage['user'] = $("#email").val();
    });
    var user = localStorage.getItem('user');
    if(user){
      $("#email").val(localStorage.getItem('user'));
    }else{
      $("#email").focus();
    }
  </script>
