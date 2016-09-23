<nav class="navbar navbar-default" role="navigation">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="#">Phenobook</a>


    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">

        <li><a href="<?= __URL ?>logic/phenobook/add.php">Add</a></li>
        <li><a href="<?= __URL ?>logic/phenobook/index.php">List</a></li>
        <li><a href="<?= __URL ?>logic/phenobook/dataReport.php">Data Report</a></li>
        <li><a href="<?= __URL ?>logic/variableGroups">Variable Groups</a></li>

        <?php
        if($__user->isAdmin())
        {
          ?>
          <li><a href="<?= __URL ?>logic/admin/userGroups">Users Groups</a></li>
          <li><a href="<?= __URL ?>logic/admin/users">Users</a></li>
          <?php
        }
        ?>

      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li>
          <a href="<?= __URL."logic/admin/User/profile.php"?>"><?= $__user; ?>
            <?= !empty($__user->userGroup)?"|":"".$__user->userGroup; ?>
          </a>
        </li>
        <li><a href="<?= __URL."logic/session/logout.php"?>">Exit</a></li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>

<div class="container-fluid">
  <?php
  if(_get("m")){
    $alert->addAviso(_get("m"));
  }
  if(_get("e")){
    $alert->addError(_get("e"));
  }
  $alert->printAlert();
  ?>
