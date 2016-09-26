<?php
$admin = true;
require "../../files/php/config/require.php";
$userGroups = obj2arr(Entity::listMe("UserGroup","active"));
$users = obj2arr(Entity::listMe("User","active"));
$variableGroups = obj2arr(Entity::listMe("VariableGroup","active"));

$item = Entity::load("Phenobook",_request("id"));

$userPhenobooksSelected = Entity::listMe("PhenobookUser","active AND phenobook = '$item->id' ORDER BY id DESC");
$userGroupsSelected = Entity::listMe("PhenobookUserGroup","active AND phenobook = '$item->id' ORDER BY id DESC");
$selectedGroups = array();
$selectedUsers = array();
foreach ($userPhenobooksSelected as $value) {
  $selectedUsers[] = $value->user;
}
foreach ($userGroupsSelected as $value) {
  $selectedGroups[] = $value->userGroup;
}
$selectedUsers = obj2arr($selectedUsers);
$selectedGroups = obj2arr($selectedGroups);
if($_POST){
  Entity::begin();
  $item->name = _post("name");
  $item->description = _post("description");
  $item->variableGroup = Entity::load("VariableGroup",_post("variableGroup"));
  $item->experimental_units_number = _post("experimental_units_number");

  if(!$alert->hasError){
    Entity::update($item);

    foreach ($userPhenobooksSelected as $value) {
      $value->active = 0;
      Entity::update($value);
    }
    foreach ($userGroupsSelected as $value) {
      $value->active = 0;
      Entity::update($value);
    }

    $phenobookUsers = _post("users");
    foreach((array)$phenobookUsers  as $pu){
      if(empty($pu)){
        continue;
      }
      $us_obj = new PhenobookUser();
      $us_obj->user = Entity::load("User", $pu);
      $us_obj->phenobook = $item;
      Entity::save($us_obj);
    }

    $phenobookGroup = _post("userGroups");
    foreach((array)$phenobookGroup  as $pg){
      if(empty($pg)){
        continue;
      }
      $us_obj = new PhenobookUserGroup();
      $us_obj->userGroup = Entity::load("UserGroup", $pg);
      $us_obj->phenobook = $item;
      Entity::save($us_obj);
    }

    Entity::commit();
    redirect("index.php?id=$item->id&m=Phenobook edited");
  }

}

?>
<div class="row">

  <div class="col-sm-8 col-md-offset-1">
    <form autocomplete="off" enctype="multipart/form-data" class="form-horizontal valid"
    method="POST" action="<?= $_SERVER["PHP_SELF"]?>">
    <input type="hidden" name="id" value="<?= _request("id") ?>">
    <fieldset>
      <!-- Form Name -->
      <legend>Edit Phenobook <i><?= $item ?></i></legend>

      <div class="form-group">
        <label class=" control-label" for="name">Name <span class="red">*</span></label>
        <input placeholder="Phenobook name" id="name" name="name" value="<?= $item->name; ?>" type="text" class="form-control input-md required">
      </div>


      <div class="form-group">
        <label class=" control-label" for="experimental_units_number">Experimental units <span class="red">*</span></label>
        <input placeholder="Experimental units" id="experimental_units_number" name="experimental_units_number" value="<?= $item->experimental_units_number; ?>" type="number" class="form-control int input-md required">
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
          printSelect("users[]", $selectedUsers, $users, null, "select-multiple users","multiple" );
          ?>
          <span class="help-block">These uses will have access to this Phenobook</span>
        </div>
        <div class="form-group ">
          <label class=" control-label" for="userGroups">Groups</label>
          <?php
          printSelect("userGroups[]", $selectedGroups, $userGroups, null, "select-multiple userGroups","multiple" );
          ?>
          <span class="help-block">Users of these groups will have access to this Phenobook</span>
        </div>
      </div>

      <div class="form-group">
        <label class=" control-label" for="description">Description</label>
        <textarea name="description" id="description" class="form-control" cols="30" rows="3"><?= $item->description; ?></textarea>
        <span class="help-block"></span>
      </div>

      <div class="form-group">
        <label class=" control-label" for="file">Select Variable Group <span class="red">*</span></label>
        <?php
        printSelect("variableGroup", $item->variableGroup, $variableGroups, null, "select2",null );
        ?>
        <span class="help-block">
        </span>
      </div>


      <hr>
      <!-- Button -->
      <div class="form-group">
        <div class="">
          <input type="submit" name="save" value="Save" class="btn btn-shadow btn-primary">
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
