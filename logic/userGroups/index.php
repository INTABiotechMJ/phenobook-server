<?php
require "../../files/php/config/require.php";
$classNamePlural = "User Groups";
$className = "UserGroup";
$classNameShow = "User Group";

?>
<div class="row">
	<div class="col-xs-12">
		<?php
		$items = Entity::listMe($className,"active");
		$data = array();
		$cont = 1;
		foreach ($items as $key => $value) {
			$item = array();
			$item["Name"] = $value;
			$item["Actions"] = "<a href='edit.php?id=$value->id' class='btn btn-default btn-sm'>Edit</a> ";
			$item["Actions"] .= "<a data-href='delete.php?id=$value->id' class='btn btn-danger btn-sm ask' data-what='Are you sure?'>Delete</a>";
			$data[] = $item;

		}

		echo "<div class='row'>";

		echo "<div class='col-md-8 col-xs-6'>";
		echo "<legend>$classNamePlural</legend>";
		echo "</div>";

		echo "<div class='col-md-3'>";

		echo "</div>";


		echo "<div class='col-md-1'>";
		echo "<a href='add.php' class='btn btn-primary'>Add</a>";
		echo "</div>";

		echo "</div>";
		echo genTable($data,true,"table");
		?>
	</div>
</div>
<?php
require __ROOT . "files/php/template/footer.php";
?>
