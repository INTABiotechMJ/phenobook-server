<?php
$admin = true;
require "../../../files/php/config/require.php";
$item = Entity::load("User", _request("id"));
$grupos = obj2arr(Entity::listMe("UserGroup","active"));
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

  $selectedGroups = Entity::listMe("UserUserGroup","active AND user = '$item->id'");
  foreach((array) $selectedGroups as $sg){
    $is = false;
    foreach(_post("groups") as $ng){
      if($sg->userGroup->id == $ng){
        $is = true;
      }
    }
    if(!$is){
      $sg->active = 0;
      Entity::update($sg);
    }
  }
  foreach(_post("groups") as $ng){
    $is = false;
    foreach((array) $selectedGroups as $sg){
      if($sg->userGroup->id == $ng){
        $is = true;
      }
    }
    if(!$is){
      $gr = Entity::search("UserGroup",$ng);
      $cg = UserUserGroup();
      $cg->user = $item;
      $cg->userGroup = $gr;
      Entity::save($cg);
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
    <legend>Edit user</legend>
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

        <div class="form-group ">
          <label class=" control-label" for="usuarios">Groups</label>
          <div class="">
            <?php
            $selectedGroups = Entity::listMe("UserUserGroup","active AND user = '$item->id'");
            $arr = array();
            foreach((array)$selectedGroups as $gr){
              $arr[] = $gr->userGroup->id;
            }
            printSelect("groups[]", $arr, $grupos, null, "select-multiple","multiple" );
            ?>
            <span class="help-block"></span>
          </div>
        </div>


        <div class="form-group">
          <label class=" control-label" for="password">Type <span class="red">*</span></label>
          <div class="">

            <div class="btn-group" data-toggle="buttons">

              <label class="btn btn-default <?= $activeOperador ?>">
                <input type="radio" <?= $checkedOperador ?> name="type" id="tipo2" value="<?= User::$TYPE_OPERADOR;?>">
                Operator
              </label>

              <label class="btn btn-default <?= $activeAdmin ?>">
                <input type="radio" <?= $checkedAdmin ?> name="type" id="tipo1" value="<?= User::$TYPE_ADMIN;?>">
                Admin
              </label>

            </div><!--END btn-group-->

          </div><!--END  -->


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
