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
	

	<link rel="stylesheet" href="<?= $url ?>assets/css/default.css" />
	<link rel="stylesheet" href="<?= $url ?>assets/css/style.css" />
	<link rel="stylesheet" href="<?= $url ?>assets/css/print.css" media="print" />
	<link rel="stylesheet" href="<?= $url ?>assets/libs/select2-3.4.5/select2.css">
	<link rel="stylesheet" href="<?= $url ?>assets/libs/select2-3.4.5/select2-bootstrap.css">

	<link rel="stylesheet" href="<?= $url ?>assets/libs/pickdate/themes/default.css" />
	<link rel="stylesheet" href="<?= $url ?>assets/libs/pickdate/themes/default.date.css" />
	<link rel="stylesheet" href="<?= $url ?>assets/libs/pickdate/themes/default.time.css" />
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
	<script type="text/javascript" src="<?= $url ?>assets/libs/growl/js/jquery.growl.js"></script>

</head>

<body class="hide">

	<div class="container">
		<a href="#" class="btn-free hide save btn btn-primary btn-lg">
			<i class='glyphicon glyphicon-log-out'></i>
			
		</a>

		<div class="users">
			<h4>Seleccionar usuario</h4>
			<ul class="list-group">
				<?php
				$users = Entity::listMe("User", "active");
				foreach ((array)$users as $u) {
					echo "<a href='#' class='user-login-link' data-pass='$u->pass' data-id='$u->id' data-name='$u'><li class='list-group-item'>$u</li></a>";
				}
				?>
			</ul>
			<a href="#" class="exit btn btn-primary btn-lg">
				<i class='glyphicon glyphicon-log-out'></i>
				Salir
			</a>
		</div>
		<div class="enter-pass hide">
			<div class="pass-warn alert alert-danger hide" role="alert">
				<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
				<span class="sr-only">Error:</span>
				PIN Incorrecto. Espere...
			</div>
			<div class="pass-empty-warn alert alert-warning hide" role="alert">
				<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>

				Ingrese su PIN
			</div>
			<div class="well">
				Usuario: <span class="username"></span>
			</div>
			<p>
				<input type="number" class="form-control pass input-lg" id="pass" name="pass" placeholder="PIN">
			</p>
			<p>
				<a href="#" class="btn btn-default btn-lg enter-pass-btn">
					<i class="glyphicon glyphicon-log-in"></i>
					Ingresar
				</a> 
				<a href="#" class="btn btn-lg btn-default pull-right" id="pass_back">
					<i class='glyphicon glyphicon-arrow-left'></i>
					volver
				</a>
			</p>
		</div>
		<div class="contain-ensayos-list hide">
			<h5>
				Usuario: 
				<span class="username"></span>
			</h5>
			<h4>Seleccionar ensayo</h4>
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
			<hr>
			<div class="center">
				<a href="#" class="change_user btn btn-default btn-md">
					<i class='glyphicon glyphicon-refresh'></i>
					Cambiar Usuario
				</a>
			</div>
		</div>
		<div class="all-ensayos">
			<?php
			foreach((array) $ensayos as $ensayo){
				$parcelas = Entity::listMe("Parcela", "active AND ensayo = '$ensayo->id'");
				$variables = Entity::listMe("Variable", "active AND ensayo = '$ensayo->id'");
				?>
				<div class="contain-ensayo hide" data-id="<?= $ensayo->id?>">

					<?php

					$hideText = "";
					foreach((array) $parcelas as $parcela){

						?>
						<div class='item row <?= $hideText ?>' data-id='<?= $parcela->id ?>' data-numero="<?= $parcela->numero ?>" id="parcela_<?= $parcela->id ?>">
							<div class="ensayo-title"><b>Ensayo: </b> <?= $ensayo ?> | <b>Usuario: </b> <span class="username"></span></div>
							<div class="ensayo-desc"><?=  htmlentities($ensayo->descripcion, ENT_QUOTES,'UTF-8'); ?></div>
							<div>
								Saltar a <?= $ensayo->campo_numero ?>
								<?php 
								echo "<select name='naranja' class='jumperParcela'>";
								echo "<option value='0'>Sel.";
								echo "</option>";
								foreach((array) $parcelas as $parcela_2){
									echo "<option value='$parcela_2->id'>";
									echo $parcela_2->numero;
									echo "</option>";
								}
								echo "</select>";
								?>
							</div>
							<form action="#" method="POST" class="rowForm">
								<?php
								echo "<div class='row center'>";
								echo "<div class='field-info col-xs-12 center fixme'>";
								echo "<a class='btn btn-primary btn-lg prev' href='#'><i class='glyphicon glyphicon-chevron-left'></i></a> ";
								echo " <span class='field-input main-id'><b>$ensayo->campo_numero:</b> $parcela->numero</span> ";
								//echo " <span class='field-input main-id'><b>$ensayo->campo_numero:</b></span> ";

								echo " <a class='btn btn-primary btn-lg next' href='#'><i class='glyphicon glyphicon-chevron-right'></i></a>";
								echo "</div>";
								echo "</div>";
								echo "<div class='row'>";
								$infoEnsayos = Entity::listMe("InfoEnsayo", "active AND ensayo = '$ensayo->id'");
								foreach((array)$infoEnsayos as $infoEnsayo){
									$valorInfoEnsayo = Entity::search("ValorInfoEnsayo", "active AND infoEnsayo = '$infoEnsayo->id' AND parcela = '$parcela->id'");
									echo "<div class='field-info col-xs-6 col-md-4'>";
									echo "<label class='field-label'>$infoEnsayo</label>";
									echo "<div class='field-input'>$valorInfoEnsayo</div>";
									echo "</div>";
								}
								echo "<div class='clearfix clear'></div>";
								foreach((array) $variables as $variable){

									$reg = Entity::search("Registro", "parcela = '$parcela->id' AND variable = '$variable->id' ORDER BY localStamp DESC");
									if(!empty($reg)){
										$value = $reg->valor;
									}else{
										$value = null;
									}

									echo "<div class='field-info col-xs-6 col-md-4'>";
									echo "<label class='field-label'>$variable</label>";
									$variable->nombreCampo = $ensayo->id . "_" . $parcela->id . "_" . $variable->id;

									echo $variable->tipoCampo->toForm($variable, $value);
									echo "</div>";	
								}
								echo "</div>";
								?>
							</form>
						</div>

						<?php
						$hideText = "hide"; 
					}
					?>

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

<script>
	var current_parcela = -1;
	var current_ensayo = -1;
	var updating = false;
	var changed = false;
	var logged_in = false;

	$(".save").click(function(){
		$(this).addClass("hide");
		$(".exit").removeClass("hide");
		$(".contain-ensayos-list").removeClass("hide");
		$(".contain-ensayo").addClass("hide");
		if(current_parcela != -1 ){
			save(current_parcela)
		}
		current_parcela = -1
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
		$(".item").addClass("hide");
		$(".contain-ensayo[data-id='" + id + "']").removeClass("hide");
		var $current_parcela_div = $(".contain-ensayo[data-id='" + id + "']").find(".item").first();
		$current_parcela_div.removeClass("hide");
		current_parcela = $current_parcela_div.data("id");

	});


	$("input:not(.pass), select").change(function(){
		changed = true;
	});
	$("input, select").blur(function(){
		if(logged_in){
			saveOne($(this));
		}
	});

	function saveOne($element){
		if(changed){
			//console.log($element.attr("name")+": "+ $element.val());
			<?php
			if(!_get("test")){
				?>
				AndroidFunction.saveOne($element.attr("name"), $element.val());
				<?php
			}
			?>
			changed = false;
		}
	}

	function save(id){
		return;
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

	function saveOneReady(msg){
		if(msg == "OK"){
			$.growl({ title: "Registrado", message: "OK" });
		}else{
			$.growl.error({ title: "Error al registrar", message: msg });
		}
	}

	$(".takephoto").click(function(){
		$(this).removeClass("btn-default");
		$(this).addClass("btn-primary");
		$(this).next("input").val($(this).data("name"));
		<?php
		if(!_get("test")){
			?>
			AndroidFunction.photo($(this).data("name"), current_parcela, current_ensayo);
			<?php
		}
		?>

		<?php
		if(!_get("test")){
			?>
			AndroidFunction.saveCurrentStatus(current_ensayo, current_parcela, current_user_id);
			<?php
		}
		?>

		return false;
	});

	function sendPhotoName(name, newName){
		$("#" + name).val(newName);
		changed = true;
		saveOne($("#"+name));
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
		$.growl({ title: "Actualizando", message: "..." , static: true});
	});
	
	function moveTo(id_ensayo, id_parcela, id_user){
		$el = $(".item[data-id='" + id_parcela + "']");
		$(".contain-ensayo[data-id='" + id_ensayo + "']").removeClass("hide");
		if($el.length == 0){
			return;
		}
		current_ensayo = id_ensayo;
		$(".save").removeClass("hide");
		$(".users").addClass("hide");
		$(".exit").addClass("hide");
		$(".contain-ensayos-list").addClass("hide");
		$(".item").addClass("hide");
		$el.removeClass("hide");
		setUserId(id_user);
		var name = $(".user-login-link[data-id='" + id_user + "']").dat("name");
		$(".username").html(name);
		current_user_id = id_user;
		current_user_name = name;
	}
	function moveToParcela(id_parcela){
		$(".item").addClass("hide");
		$(".item[data-id='" + id_parcela + "']").removeClass("hide");
	}

	function setValue(id, value){
		if($("#" + id).length == 0){
			return
		}
		if($("#" + id).data("type") == "photo"){
			$("#" + id).prev(".takephoto").removeClass("btn-default");
			$("#" + id).prev(".takephoto").addClass("btn-primary");
			$("#" + id).val(value);
			return;
		}
		if($("#" + id).data("type") == "select"){
			$("#" + id +" option[value=" + value+ "]").attr('selected','selected');
			return;
		}
		$("#" + id).val(value);
	}

	function parseDataReady(){
		$("body").removeClass("hide");
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
			}, 500);
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
});


document.addEventListener("DOMContentLoaded", function(event) { 
	<?php
	if(!_get("test")){
		?>
		AndroidFunction.documentReady();
		<?php
	}
	?>
});

$(".next").click(function(){
	var $current = $(this).parents(".item");
	save($current.data("id"));
	var $next = $(this).parents(".item").next(".item");
	if($next.length != 0){
		$current.addClass("hide");
		$next.removeClass("hide");
		current_parcela = $next.data("id");
	}
});
$(".prev").click(function(){
	var $current = $(this).parents(".item");
	save($current.data("id"));
	var $prev = $(this).parents(".item").prev(".item");
	if($prev.length != 0){
		$(this).parents(".item").addClass("hide");
		$prev.removeClass("hide");
		current_parcela = $prev.data("id");
	}
});
<?php
if(_get("test")){
	?>
	$("body").removeClass("hide");
	<?php
}
?>
var real_pass = "";
var current_user_name = "";
var current_user_id = "";
//SELECT USER
$(".user-login-link").click(function(){
	$("#pass").val("");
	$("#pass").focus();
	current_user_name = $(this).data("name");
	current_user_id = $(this).data("id");
	$(".username").html(current_user_name);
	$(".users").addClass("hide");
	$(".enter-pass").removeClass("hide");
	$(".pass-warn").addClass("hide");
	real_pass = $(this).data("pass");
});
//ENTER PASSWORD
$(".enter-pass-btn").click(function(){
	var curr_pass = $("#pass").val();
	$(".pass-empty-warn").addClass("hide");
	if(curr_pass == ""){
		$(".pass-empty-warn").removeClass("hide");
		return;
	}
	if(real_pass == curr_pass){
		logged_in = true;
		setUserId(current_user_id);
		$(".username").html(current_user_name);
		$(".contain-ensayos-list").removeClass("hide");
		$(".enter-pass").addClass("hide");
	}else{
		$(".pass-warn").removeClass("hide");
		$("#pass").val("");

		$("#pass").attr("disabled", true);		
		$(".enter-pass-btn").attr('disabled', true);

		setTimeout(function(){
			$(".enter-pass-btn").attr('disabled', false);
			$("#pass").attr("disabled", false);
			$(".pass-warn").addClass("hide");
		}, 5000);


	}
});
$("#pass_back").click(function(){
	$(".users").removeClass("hide");
	$(".enter-pass").addClass("hide");
	$(".pass-warn").addClass("hide");
});
function setUserId(user_id){
	<?php
	if(!_get("test")){
		?>
		AndroidFunction.setUserId(user_id);
		<?php
	}
	?>
}
//CHANGE USER
$(".change_user").click(function(){
	real_pass = "";
	current_user_name = "";
	current_user_id = "";
	logged_in = false;
	$(".users").removeClass("hide");
	$(".enter-pass").addClass("hide");
	$(".pass-warn").addClass("hide");
	$(".contain-ensayos-list").addClass("hide");
});

$(".jumperParcela").change(function(){
	var id_parcela = $(this).val();
	if(id_parcela == "0"){
		return;
	}
	moveToParcela(id_parcela)
	$(".jumperParcela").val("0");
});
</script>

<script type="text/javascript" src="<?= $url ?>assets/js/defaults.js"></script>
<script type="text/javascript" src="<?= $url ?>assets/js/script.js"></script>

</html> 