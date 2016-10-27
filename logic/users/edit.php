<?php
require "../../files/php/config/require.php";
$item = Entity::load("User", _request("id"));
if($item->userGroup->id != $__user->userGroup->id && !$__user->isSuperAdmin){
	raise404();
}
$groups = obj2arr(Entity::listMe("UserGroup","active"));

if($_POST){
  $email = _post("email");
  $item->email = $email;
  $item->name = _post("name");
  $item->lastName = _post("lastName");
  if($__user->isAdmin){
    $item->isAdmin = _post("isAdmin")?1:0;
  }
  if($__user->isSuperAdmin){
    $item->userGroup = Entity::load("UserGroup",_post("userGroup"));
  }else{
    $item->userGroup = $__user->userGroup;
  }
  if($__user->id == $item->id && _post("password")){
    if(_post("password") == _post("password2")){
      $item->pass = _post("password");
    }else{
      $alert->addError("Passwords do not match");
    }
  }
  if(User::searchByEmail($email, $item->id)){
    $alert->addError("Email $email is already registered");
  }

  if(!$alert->hasError){
    Entity::update($item);
    redirect("index.php?m=User edited");
  }
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
    <?php
    if($__user->id == $item->id){
      ?>
      <legend>Your profile</legend>
      <?php
    }else{
      ?>
      <legend>Edit user</legend>
      <?php
    }
    ?>
    <form class="form-horizontal valid" method="POST" action="<?= $_SERVER["PHP_SELF"]?>">
      <input type="hidden" name="id" value="<?= _request("id"); ?>">
      <fieldset>
        <!-- Form Name -->
        <!-- Text input-->
        <div class="form-group">
          <label class=" control-label" for="name">Name <span class="red">*</span></label>
          <div class="">
            <input id="name" value="<?= $item->name ?>" name="name" type="text"  class="form-control input-md required">
            <span class="help-block"></span>
          </div>
        </div>

        <div class="form-group">
          <label class=" control-label" for="lastName">Last Name <span class="red">*</span></label>
          <div class="">
            <input id="lastName" value="<?= $item->lastName ?>" name="lastName" type="text"  class="form-control input-md required">
            <span class="help-block"></span>
          </div>
        </div>

        <div class="form-group">
          <label class=" control-label" for="email">Email <span class="red">*</span></label>
          <div class="">
            <input id="email" value="<?= $item->email ?>" name="email" type="text"  class="form-control input-md email required">
            <span class="help-block"></span>
          </div>
        </div>
        <?php

        if($__user->id == $item->id){
          ?>
          <div class="form-group">
            <label class="control-label" for="password">Change password </label>
            <input minlength="4" value="" id="password" name="password" type="password"  class="form-control input-md">
            <span class="help-block">Type something to change your password</span>
          </div>

          <div class="form-group">
            <label class="control-label" for="password">Type your password again </label>
            <input minlength="4" value="" id="password2" name="password2" type="password2"  class="form-control input-md">
            <span class="help-block"></span>
          </div>
          <?php
        }
        ?>
        <?php
        if($__user->isSuperAdmin){
          ?>
          <div class="form-group ">
            <label class=" control-label" for="usuarios">Group <span class="red">*</span></label>
            <div class="">
              <?php
              printSelect("userGroup",$item->userGroup->id, $groups,  null, "select2","" );
              ?>
              <span class="help-block"></span>
            </div>
          </div>
          <?php
        }
        ?>
        <?php
        if($__user->isAdmin){
          ?>
          <div class="form-group">
            <?php
            echo check("isAdmin",$item->isAdmin);
            ?>
            <label class="control-label" for="isAdmin"> Is administrator</label>
            <span class="help-block">
              Administrator users are able to manage other users
            </span>
          </div>
          <?php
        }
        ?>
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
