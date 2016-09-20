<?php
require "../../../files/php/config/require.php";
$classNamePlural = __VARIABLE_GROUP_PLURAL;
$className = __VARIABLE_GROUP_CLASS;
$classNameShow = __VARIABLE_GROUP_CLASS_SHOW;
?>
<div class="row">
	<div class="col-xs-12">
		<?php
		$items = Entity::listMe($className,"active");
		$data = array();
		$cont = 1;
		foreach ($items as $key => $value) {
			$item = array();
			$item[__NAME] = $value;
			$img_url = "No";
			$item[__ACTIONS] = "<a href='edit.php?id=$value->id' class='btn btn-default btn-sm'>".__EDIT."</a> ";
			$item[__ACTIONS] .= "<a href='../VariableGenerica/index.php?id=$value->id' class='btn btn-primary btn-sm'>".__VARIABLE_GEN_GROUP_PLURAL."</a> ";
			$item[__ACTIONS] .= "<a data-href='delete.php?id=$value->id' class='btn btn-default btn-sm ask' data-what='".__SURE."'>".__DELETE."</a>";
			$data[] = $item;

		}

		echo "<div class='row'>";

		echo "<div class='col-md-8'>";
		echo "<legend>$classNamePlural</legend>";
		echo "</div>";

		echo "<div class='col-md-3'>";
		echo "<input type='text' name='search' class='sercheable form-control' data-target='table' placeholder='Filtrar' >";
		echo "</div>";


		echo "<div class='col-md-1'>";
		echo "<a href='add.php' class='btn btn-primary btn-sm btn-shadow'>".__ADD."</a>";
		echo "</div>";

		echo "</div>";
		echo genTable($data,true,"table");
		?>
	</div>
</div>
<?php
require __ROOT . "files/php/template/footer.php";
?>
