<?php 
require "../../../files/php/config/require.php";
$item = Entity::load("User", $__user->id);

if($_POST){
  if(_post("save")){
    $item->email = _post("email");
    $item->name = _post("name");
    $item->lastName = _post("lastName");
    $item->lang = _post("lang");
    Entity::update($item);
    $_SESSION["user".__HASH] = $item;
    redirect("index.php?m=".__USER_PROFILE_EDITTED);
  }
  if(_post("changePass")){
    if(_post("oldPassword") != $__user->pass){
      $alert->addError(__USER_PROFILE_OLD_PASSWORD_DO_NOT_MATCH);
    }
    if(_post("password") != _post("password2")){
      $alert->addError(__USER_PROFILE_PASSWORD_DO_NOT_MATCH);
    }
    if(!$alert->hasError){
      $item->pass = _post("password");
      Entity::update($item);
      $_SESSION["user".__HASH] = $item;
      redirect("index.php?m=".__USER_PROFILE_PASSWORD_UPDATED);
    }
  }
}

?>

<div class="row">
  <div class="col-sm-6 ">
    <form class="form-horizontal valid" method="POST" action="<?= $_SERVER["PHP_SELF"]?>">
      <input type="hidden" name="id" value="<?= _request("id"); ?>">
      <fieldset>
        <!-- Form Name -->
        <legend><?= __USER_PROFILE_EDIT ?></legend>
        <!-- Text input-->

         <div class="form-group">
          <label class="col-md-4 control-label" for="name"><?= __USER_PROFILE_USER_TYPE ?> </label>  
          <div class="col-md-4">
            <?= $__user->calcTypeName() ?>
            <span class="help-block"></span>  
          </div>
        </div>
        
      <?php 
      if(!$__user->isSuperAdmin()){
       ?>

       <div class="form-group">
        <label class="col-md-4 control-label" for="name"><?= __USER_PROFILE_GROUP_NAME ?> </label>  
        <div class="col-md-4">
          <?= $__user->userGroup ?>
          <span class="help-block"></span>  
        </div>
      </div>
      <?php 
    }
    ?>

    <div class="form-group">
      <label class="col-md-4 control-label" for="name"><?= __USER_PROFILE_NAME ?> *</label>  
      <div class="col-md-4">
        <input id="name" value="<?= $item->name ?>" name="name" type="text"  class="form-control input-md required">
        <span class="help-block"></span>  
      </div>
    </div>


    <div class="form-group">
      <label class="col-md-4 control-label" for="lastName"><?= __USER_PROFILE_LAST_NAME ?> *</label>  
      <div class="col-md-4">
        <input id="lastName" value="<?= $item->lastName ?>" name="lastName" type="text"  class="form-control input-md required">
        <span class="help-block"></span>  
      </div>
    </div>

    <div class="form-group">
      <label class="col-md-4 control-label" for="email"><?= __USER_PROFILE_EMAIL ?> *</label>  
      <div class="col-md-4">
        <input id="email" value="<?= $item->email ?>" name="email" type="text"  class="form-control input-md email required">
        <span class="help-block"></span>  
      </div>
    </div>


    <div class="form-group">
      <label class="col-md-4 control-label" for="email"><?= __USER_PROFILE_LANG ?> *</label>  
      <div class="col-md-4">
        <?php 
        $langs = array(0 => "English",1 => "Spanish");
        printSelect("lang", $item->lang, $langs, null, "select","" );
        ?>
        <span class="help-block"></span>  
      </div>
    </div>


    <!-- Button -->
    <div class="form-group">
      <div class="col-md-4 col-md-offset-4">
        <input type="submit" name="save" value="<?= __USER_PROFILE_SAVE ?>" class="btn btn-shadow btn-primary">
      </div>
    </div>
  </fieldset>
</form>

</div>

<div class="col-sm-6">
  <form class="form-horizontal valid" method="POST" action="<?= $_SERVER["PHP_SELF"]?>">
    <input type="hidden" name="id" value="<?= _request("id"); ?>">
    <fieldset>
      <!-- Form Name -->
      <legend><?= __USER_PROFILE_CHANGE_PASSWORD ?></legend>
      <!-- Text input-->

      <div class="form-group">
        <label class="col-md-4 control-label" for="oldPassword"><?= __USER_PROFILE_CURRENT_PASSWORD ?>*</label>  
        <div class="col-md-4">
          <input id="oldPassword" value="" name="oldPassword" type="password"  class="form-control input-md required">
          <span class="help-block"></span>  
        </div>
      </div>

      <div class="form-group">
        <label class="col-md-4 control-label" for="password"><?= __USER_PROFILE_NEW_PASSWORD ?> *</label>  
        <div class="col-md-4">
          <input minlength="5" id="password" value="" name="password" type="password"  class="form-control input-md required">
          <span class="help-block"></span>  
        </div>
      </div>

      <div class="form-group">
        <label class="col-md-4 control-label" for="password2"><?= __USER_PROFILE_REPEAT_PASSWORD ?> *</label>  
        <div class="col-md-4">
          <input  minlength="5" id="password2" value="" name="password2" type="password"  class="form-control input-md required">
          <span class="help-block"></span>  
        </div>
      </div>

      <!-- Button -->
      <div class="form-group">

        <div class="col-md-4 col-md-offset-4">
          <input type="submit" name="changePass" value="Guardar" class="btn btn-shadow btn-primary">
        </div>
      </div>

    </fieldset>
  </form>

</div>
</div>
<?php 
require __ROOT."files/php/template/footer.php";
?>