<?php
$admin = true;
require "../../files/php/config/require.php";
$userGroups = obj2arr(Entity::listMe("UserGroup","active"));
$variableGroups = obj2arr(Entity::listMe("VariableGroup","active"));

if($_POST){
  Entity::begin();
  $item = new Phenobook();
  $item->name = _post("name");
  $item->description = _post("description");

  $dir = __ROOT."files/uploads/" . date("Y") . "/". date("m") ."/";

  if (!file_exists($dir)) {
    mkdir($dir, 0777, true);
  }

  $result = subir($_FILES["archivo"],array("csv"));
  if(!empty($result["error"])){
    $alert->addError($result["msg"]);
  }

  if(!$alert->hasError){
    Entity::save($item);

    $phenobookUsers = _post("users");
    foreach((array)$phenobookUsers  as $pu){
      $us_obj = new PhenobookUser();
      $us_obj->user = Entity::load("User", $pu);
      $us_obj->phenobook = $item;
      Entity::save($us_obj);
    }

    $phenobookGroup = _post("userGroups");
    foreach((array)$phenobookGroup  as $pg){
      $us_obj = new PhenobookUserGroup();
      $us_obj->group = Entity::load("Group", $pg);
      $us_obj->phenobook = $item;
      Entity::save($us_obj);
    }

    $import = new Import();
    $import->file = $result["filename"];
    $import->path = $result["name"];
    $import->phenobook = $item;
    Entity::save($import);
    Entity::commit();
    redirect("add_result.php?id=$item->id&m=Phenobook added");
  }

}

$users = obj2arr(Entity::listMe("User","active AND 1"));

?>

<div class="row">

  <div class="col-sm-8 col-md-offset-1">
    <form autocomplete="off" enctype="multipart/form-data" class="form-horizontal valid"
    method="POST" action="<?= $_SERVER["PHP_SELF"]?>">
    <fieldset>
      <!-- Form Name -->
      <legend>Add Phenobook</legend>

      <div class="form-group">
        <label class=" control-label" for="name">Name <span class="red">*</span></label>
        <input placeholder="Phenobook name" id="name" name="name" value="<?= _post("name"); ?>" type="text" class="form-control input-md required">
      </div>


      <div class="form-group">
        <label class=" control-label" for="experimentalUnitsNumber">Experimental units <span class="red">*</span></label>
        <input placeholder="Experimental units" id="experimentalUnitsNumber" name="experimentalUnitsNumber" value="<?= _post("experimentalUnitsNumber"); ?>" type="number" class="form-control int input-md required">
        <span class="help-block">
          Number of experimental units
        </span>
      </div>

      <div class="form-content">
        <b>
          Visible for
        </b>
        <div class="form-group ">
          <label class=" control-label" for="users">Users</label>
          <?php
          printSelect("users[]", _post("users"), $users, null, "select-multiple users","multiple" );
          ?>
          <span class="help-block">These uses will have access to this Phenobook</span>
        </div>
        <div class="form-group ">
          <label class=" control-label" for="userGroups">Groups</label>
          <?php
          printSelect("userGroups[]", _post("userGroups"), $userGroups, null, "select-multiple userGroups","multiple" );
          ?>
          <span class="help-block">Users of these groups will have access to this Phenobook</span>
        </div>
      </div>

      <div class="form-group">
        <label class=" control-label" for="description">Description</label>
        <textarea name="description" id="description" class="form-control" cols="30" rows="3"><?= _post("description"); ?></textarea>
        <span class="help-block"></span>
      </div>

      <div class="form-group">
        <label class=" control-label" for="file">Select Variable Group <span class="red">*</span></label>
        <?php
        printSelect("variableGroup", _post("variableGroup"), $variableGroups, null, "select2",null );
        ?>
        <span class="help-block">
        </span>
      </div>


      <hr>
      <!-- Button -->
      <div class="form-group">
        <div class="">
          <input type="submit" name="save" value="Continue" class="btn btn-shadow btn-primary">
        </div>
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
