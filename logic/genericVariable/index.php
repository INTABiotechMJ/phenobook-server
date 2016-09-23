<?php
require "../../files/php/config/require.php";
$classNamePlural = "Variables";
$className = "GenericVariable";
$classNameShow = "Variable";
$grupo = Entity::load("VariableGroup",_request("id"));
?>
<div class="row">
	<div class="col-xs-12">
		<?php
		$items = Entity::listMe($className,"active AND variableGroup = '$grupo->id'");
		$data = array();
		$cont = 1;
		foreach ($items as $key => $value) {
			$item = array();
			$item["Name"] = $value;
			$item["Type"] = $value->fieldType;
			$item["Description"] = $value->description;
			$img_url = "No";
			$item["Actions"] = "<a href='edit.php?idgv=$grupo->id&id=$value->id' class='btn btn-default btn-sm'>Edit</a> ";
			$item["Actions"] .= " <a data-href='delete.php?idgv=$grupo->id&id=$value->id' class='btn btn-default btn-sm ask' data-what='Are you sure?'>Delete</a>";
			if($value->fieldType->isOption())	{
				$item["Actions"] .= " <a href='../options/index.php?id_variable=$value->id' class='btn btn-primary btn-sm'>Options</a> ";
			}
			$data[] = $item;
		}

		echo "<div class='row'>";

		echo "<div class='col-md-8'>";
		echo "<legend>$classNamePlural - Group: <i>$grupo</i></legend>";
		echo "</div>";

		echo "<div class='col-md-3'>";

		echo "</div>";


		echo "<div class='col-md-1'>";
		echo "<a href='add.php?id=$grupo->id' class='btn btn-primary'>Add</a>";
		echo "</div>";

		echo "</div>";
		echo genTable($data,true,"table");
		?>
	</div>
</div>
<?php
require __ROOT . "files/php/template/footer.php";
?>
