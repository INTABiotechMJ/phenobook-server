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

    </div>
    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li><a href="<?= __URL ?>logic/phenobook/index.php">Phenobooks</a></li>
        <li><a href="<?= __URL ?>logic/phenobook/data_report.php">Data Report</a></li>
        <li><a href="<?= __URL ?>logic/variables">Variables</a></li>
        <?php
        if($__user->isSuperAdmin)
        {
          ?>
          <li><a href="<?= __URL ?>logic/userGroups">Users Groups</a></li>
          <?php
        }
        ?>
        <?php
        if($__user->isAdmin)
        {
          ?>
          <li><a href="<?= __URL ?>logic/users">Users</a></li>
          <?php
        }
        ?>

      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li>
          <a href="<?= __URL."logic/users/edit.php?id=$__user->id"?>"><?= $__user; ?> |
            <?= $__user->userGroup; ?>
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
