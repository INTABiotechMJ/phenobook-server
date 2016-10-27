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
    redirect(__URL."logic/phenobook");
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
.store-badge{
  max-width: 100px;
}
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
.img-logo{
  width: 80px;
  margin-left: 10px;
  margin-top: 10px;
}
</style>
<div class="container">
  <div class="row">
    <?php $alert->printAlert() ?>
    <div class="col-md-4">

      <form class="form-signin form valid center-block" action="<?= $_SERVER["PHP_SELF"];?>" method="POST">
        <h2 class="form-signin-heading">Login</h2>
        <input type="text" id="email" class="form-control auto required" name="email" placeholder="User"  autocomplete="off" />
        <input type="password" class="form-control required" name="pass" placeholder="Password" autofocus=""/>
        <button id="send" class="btn btn-lg btn-primary btn-block" type="submit">Login</button>
        <span class="hide">
          <a href="<?= __URL ?>logic/session/passRecover.php">Forgot password?</a>
        </span>

      </form>
      <div class="text-center">
        <img class="img-logo" src="assets/img/inta.png" alt="" />
        <img class="img-logo" src="assets/img/conicet.png" alt="" />
      </div>
    </div>
    <div class="col-md-8">
      <h4>
        What is Phenobook?
      </h4>
      It is an open source software for phenotypic data collection. It consists
      on a server software and a mobile application.
      <p>
        It can be easily implemented in collaborative research and development projects involving data collecting and forward analyses. Adopting Phenobook is expected to improve the involved processes by minimizing input errors, resulting in higher quality and reliability of the research outcomes.
      </p>
      <hr>
      <h4>
        Getting started
      </h4>
      You can check <a href="https://intabiotechmj.github.io/phenobook-server/" target="_blank">online doncumentation on github</a>.
      <hr>
      <h4>
        Get phenobook for mobile
      </h4>
      <a href="https://play.google.com/store/apps/details?id=manolo.field" target="_blank"><img class="store-badge" src="<?= __URL ?>assets/img/google-play-badge.png" alt="Get it on Google Play" /></a> <br>
      <a href="https://play.google.com/store/apps/details?id=manolo.field" target="_blank"><img class="store-badge" src="<?= __URL ?>assets/img/app-store-badge.png" alt="Download on the Apple Store" /></a> <br>
      <p>
        You can also download the
        <a href="<?= __URL ?>app.apk" target="_blank">APK file</a> for Android devices <br>
      </p>
      <hr>
      <h4>
        Fork phenobook on github
      </h4>
      <p>
        Server: <a target="_blank" href="https://github.com/INTABiotechMJ/phenobook-server">https://github.com/INTABiotechMJ/phenobook-server</a> <br>
        Mobile: <a target="_blank" href="https://github.com/INTABiotechMJ/phenobook-mobile">https://github.com/INTABiotechMJ/phenobook-mobile</a>
      </p>
      <hr>
      <h4>
        Want access to this hosted version?
      </h4>
      <p>
        Email us at vanzetti.leonardo[at]inta.gob.ar with
        information about you and your institution.
      </p>
    </div>
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
