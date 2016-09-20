<?php 
$noLogin = true;
$noMenu = true;
$noHeader = true;
require "../../../files/php/config/require.php";

$ensayos = Entity::listMe("Ensayo", "active ORDER BY id DESC");
$from = _get("from");
if($from == "mobile"){
	$url = "";
}else{
	$url = __URL;
}
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title><?= __TITLE ?></title>
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1.0, maximum-scale=1" />
	<link rel="stylesheet" href="<?= $url ?>assets/libs/bootstrap/css/bootstrap.min.css" />
	<link rel="stylesheet" href="<?= $url ?>assets/libs/select-bootstrap/css/bootstrap-select.min.css" />

	<link rel="stylesheet" href="<?= $url ?>assets/css/default.css" />
	<link rel="stylesheet" href="<?= $url ?>assets/css/style.css" />
	<link rel="stylesheet" href="<?= $url ?>assets/css/print.css" media="print" />
	<link rel="stylesheet" href="<?= $url ?>assets/libs/select2-3.4.5/select2.css">
	<link rel="stylesheet" href="<?= $url ?>assets/libs/select2-3.4.5/select2-bootstrap.css">

	<link rel="stylesheet" href="<?= $url ?>assets/libs/pickdate/themes/classic.css" />
	<link rel="stylesheet" href="<?= $url ?>assets/libs/pickdate/themes/classic.date.css" />
	<link rel="stylesheet" href="<?= $url ?>assets/libs/pickdate/themes/classic.time.css" />
	<link rel="stylesheet" href="<?= $url ?>assets/libs/growl/css/jquery.growl.css" />

	<script type="text/javascript" src="<?= $url ?>assets/libs/jquery-1.11.0.min.js"></script>

	<script type="text/javascript" src="<?= $url ?>assets/libs/pickdate/picker.js"></script>
	<script type="text/javascript" src="<?= $url ?>assets/libs/pickdate/picker.date.js"></script>
	<script type="text/javascript" src="<?= $url ?>assets/libs/pickdate/picker.time.js"></script>
	<script type="text/javascript" src="<?= $url ?>assets/libs/pickdate/translations/es_ES.js"></script>

	<script type="text/javascript" src="<?= $url ?>assets/libs/jquery.maskedinput.min.js"></script>

	<script type="text/javascript" src="<?= $url ?>assets/libs/bootstrap/js/bootstrap.min.js"></script>

	<script type="text/javascript" src="<?= $url ?>assets/libs/jquery-validation/jquery.validate.min.js"></script>
	<script type="text/javascript" src="<?= $url ?>assets/libs/jquery-validation/messages_es_AR.js"></script>
	<script type="text/javascript" src="<?= $url ?>assets/js/defaults.js"></script>
	<script type="text/javascript" src="<?= $url ?>assets/js/script.js"></script>
	<script type="text/javascript" src="<?= $url ?>assets/libs/growl/js/jquery.growl.js"></script>

</head>

<body>

	<div class="container">
		<a href="#" class="btn-free hide save btn btn-primary btn-lg">
			<i class='glyphicon glyphicon-log-out'></i>
			Guardar y Volver
		</a>
		<div class="contain-ensayos-list">
			<h4>Seleccionar</h4>
			<div class="row">
				<ul class="list-group">
					<?php
					foreach((array) $ensayos as $ensayo){
						?>

						<a href="#" class="ensayo-link" data-id="<?= $ensayo->id ?>">
							<li class="list-group-item">
								<?= $ensayo?>
							</li>
						</a>
						<?php
					}
					?>
				</ul>
			</div>
			<a href="#" class="update btn btn-primary btn-lg">
				<i class='glyphicon glyphicon-refresh'></i>
				Actualizar
			</a>
			<a href="#" class="exit btn btn-primary btn-lg">
				<i class='glyphicon glyphicon-log-out'></i>
				Salir
			</a>
		</div>
		<div class="all-ensayos">
			<?php
			foreach((array) $ensayos as $ensayo){
				$parcelas = Entity::listMe("Parcela", "active AND ensayo = '$ensayo->id'");
				$variables = Entity::listMe("Variable", "active AND ensayo = '$ensayo->id'");
				?>
				<div class="contain-ensayo hide" data-id="<?= $ensayo->id?>">

					
					<div class='item row' data-id='<?= $parcela->id ?>' data-numero="<?= $parcela->numero ?>" id="parcela_<?= $parcela->id ?>">
						<form action="#" method="POST" class="rowForm">
							<?php
							echo "<div class='field-info col-xs-12 col-sm-12'>";
							echo "<div class='field-input main-id'>1</div>";
							echo "</div>";
							$infoEnsayos = Entity::listMe("InfoEnsayo", "active AND ensayo = '$ensayo->id'");
							foreach((array)$infoEnsayos as $infoEnsayo){
								echo "<div class='field-info col-xs-12'>";
								echo "<label class='field-label'>$infoEnsayo</label>";
								echo "<div class='field-input'>ads</div>";
								echo "</div>";
							}
							echo "<div class='clearfix clear'></div>";
							foreach((array) $variables as $variable){

								echo "<div class='field-info col-xs-12 col-md-12'>";
								echo "<label class='field-label'>$variable</label>";
								$variable->nombreCampo = $ensayo->id . "_" . $variable->id;
								echo $variable->tipoCampo->toForm($variable, "");
								echo "</div>";	
							}
							?>
						</form>
					</div>


				</div>
				<?php
			}
			?>
		</div>

		<hr>
		<?php
		echo "<span class='clarito'>".__TITLE."</span>";
		?>
	</div>
</body>

	<?php
	foreach((array) $parcelas as $parcela){
		echo $parcela;
	}
	?>
<script>

	var current_item = -1;
	var current_ensayo = -1;
	var changed = false;
	var updating = false;

	$(".save").click(function(){
		$(this).addClass("hide");
		$(".exit").removeClass("hide");
		$(".contain-ensayos-list").removeClass("hide");
		$(".contain-ensayo").addClass("hide");
		if(current_item != -1 ){
			save(current_item)
		}
		current_item = -1
	});
	$(".exit").click(function(){
		<?php
		if(!_get("test")){
			?>
			AndroidFunction.exit();
			<?php
		}
		?>
	});
	$(".ensayo-link").click(function(){
		$(".save").removeClass("hide");
		$(".exit").addClass("hide");
		$(".contain-ensayos-list").addClass("hide");
		$(".contain-ensayo").addClass("hide");
		var id = $(this).attr("data-id");
		current_ensayo = id;
		$(".contain-ensayo[data-id='" + id + "']").removeClass("hide");
	});

	$("input, select, .takephoto").focus(function(){
		$(".item").removeClass("active");
		var $parent = $(this).parents(".item");
		var id = $parent.data("id");
		$parent.addClass("active");
		if(current_item != id && current_item != -1 ){
			save(current_item)
		}
		current_item = id;
	});

	$("input, select").change(function(){
		changed = true;
	});

	function save(id){
		if(changed){
			var $item = $(".item[data-id='" + id + "']");
			var $form = $item.find(".rowForm");
			<?php
			if(!_get("test")){
				?>
				AndroidFunction.save(current_ensayo, $item.data("id"), $form.serialize());
				<?php
			}
			?>
			changed = false;
		}
	}

	function saveReady(id, msg){
		var $item = $(".item[data-id='" + id + "']");
		var numero = $item.data("numero");
		if(msg == "OK"){
			$.growl({ title: "Registrado", message: "N: " + numero });
		}else{
			$.growl.error({ title: "Error al registrar", message: "Id: " + id });
		}
	}

	$(".takephoto").click(function(){
		$(this).removeClass("btn-default");
		$(this).addClass("btn-primary");
		$(".item").removeClass("active");
		var $parent = $(this).parents(".item");
		var id = $parent.data("id");
		$parent.addClass("active");
		if(current_item != id && current_item != -1 ){
			save(current_item)
		}
		current_item = id;

		changed = true;
		$(this).next("input").val($(this).data("name"));
		<?php
		if(!_get("test")){
			?>
			AndroidFunction.photo($(this).data("name"), current_item, current_ensayo);
			<?php
		}
		?>
		return false;
	});

	function sendPhotoName(name, newName){
		$("#" + name).val(newName);
	}

	$(".update").click(function(){
		if(updating){
			return;
		}
		updating = true;
		<?php
		if(!_get("test")){
			?>
			AndroidFunction.update();
			<?php
		}
		?>
		$(this).addClass("disabled");
		$.growl({ title: "Actualizando", message: "..." });
	});
	
	function moveTo(id_ensayo, id_parcela){
		current_ensayo = id_ensayo;
		$(".save").removeClass("hide");
		$(".exit").addClass("hide");
		$(".contain-ensayos-list").addClass("hide");
		$(".contain-ensayo").addClass("hide");
		$(".contain-ensayo[data-id='" + id_ensayo + "']").removeClass("hide");
		window.location.hash= "parcela_" + id_parcela;  

	}

	function setValue(id, value){
		if($("#" + id).length){
			$("#" + id).val(value);
		}
		if($("#" + id).data("type") == "photo"){
			$("#" + id).removeClass("btn-default");
			$("#" + id).addClass("btn-primary");
		}
	}

	function updateReady(status, msg){
		if(msg != ""){
			msgShow = "[" + msg + "]";
		}else{
			msgShow = "";
		}
		if(status == "OK"){
			$.growl.notice({ title: "Actualizaci&oacute;n Exitosa", message: "Recargando" });
			$(this).removeClass("disabled");
			var load = setTimeout(function() {
				<?php
				if(!_get("test")){
					?>
					AndroidFunction.navigateHome();
					<?php
				}
				?>
				updating = false;
			}, 1000);
		}

		if(status == "ERROR"){
			$.growl.error({ title: "Actualizaci&oacute;n Err&oacute;nea", message: "No se puede establecer una conexi&oacute;n con el servidor" + msgShow });
			$(".update").removeClass("disabled");
			updating = false;
			//var load = setTimeout(function() {
			//	AndroidFunction.navigateHome();
			//}, 5000);
}

}
$("form").submit(function(e){
	e.preventDefault();
	return false;
})

document.addEventListener("DOMContentLoaded", function(event) { 
	<?php
	if(!_get("test")){
		?>
		AndroidFunction.documentReady();
		<?php
	}
	?>
});
</script>

</html>