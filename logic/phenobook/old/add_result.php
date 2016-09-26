<?php
$admin = true;
require "../../../files/php/config/require.php";

$objects_tipoCampo = Entity::listMe("FieldType", "active");
$tiposCampo = array();
foreach((array)$objects_tipoCampo as $item){
  $tiposCampo[$item->tipo] = $item;
}
//$tiposCampo[-1] = __FIELD_TYPE_DESCRIPTIVE;

ini_set('auto_detect_line_endings', true);


$libroCampo = Entity::load("Phenobook", _request("id"));
$libroCampo->active = 0;
Entity::update($libroCampo);
$importacion = Entity::search("Importacion", "libroCampo = '$libroCampo->id'");
$fileName = $importacion->path;

$arr = csv_to_array(__ROOT.$fileName, ";", true);
if(!isset($arr[0])){
  $libroCampo->active = 0;
  Entity::update($libroCampo);
  Entity::commit();
  redirect("add.php?e=".__TRIAL_RESULT_NONE_FIELD_FOUND);
}else{
  $libroCampo->active = 1;
  Entity::update($libroCampo);
  Entity::commit();
}

if(false){
  $campana->active = 0;
  Entity::update($libroCampo);
  Entity::commit();
  redirect("add.php?e=".__TRIAL_RESULT_DUPLICATED_NAMES);
}

$campos = array_keys($arr[0]);
$campos = array_combine($campos,$campos);

$new_campos = array();
foreach((array)$campos as $c){
  $new_campos[] = utf8_encode($c);
}
$campos = $new_campos;

if($_POST){
  $arr = csv_to_array(__ROOT.$fileName, ";");
  $campos = array_keys($arr[0]);
  Entity::begin();
  $campo_numero = _post("campo_numero");
  $deleted_campo = $campos[$campo_numero];

  $libroCampo->campo_numero = $campos[$campo_numero];
  Entity::update($libroCampo);

  unset($campos[$campo_numero]);
  $infoEnsayoArr = array();
  foreach($campos as $campo){

    $id = cleanName($campo);
    if(_post($id) == FieldType::$TIPO_INFORMATIVO){ // informativo
      $infoEnsayo = new InfoEnsayo();
      $infoEnsayo->nombre = $id;
      $infoEnsayo->nombreOriginal = $campo;
      $infoEnsayo->libroCampo = $libroCampo;
      Entity::save($infoEnsayo);
      $infoEnsayoArr[$id] = $infoEnsayo;
    }else{ //variable
      $variable = new Variable();
      $variable->nombre = $id;
      $variable->nombreOriginal = $campo;
      $variable->libroCampo = $libroCampo;
      $tc = Entity::load("FieldType", _post($id));
      $variable->tipoCampo = $tc;
      Entity::save($variable);
    }
  }
  foreach($arr as $key => $value){

    $parcela = new Parcela();


    $parcela->numero = $value[$deleted_campo];
    $parcela->libroCampo = $libroCampo;
    Entity::save($parcela);

    foreach($value as $k => $v){
      $id = cleanName($k);
      if(_post($id) == FieldType::$TIPO_INFORMATIVO){
        $valorInfoEnsayo = new ValorInfoEnsayo();
        $valorInfoEnsayo->valor = $v;
        $valorInfoEnsayo->infoEnsayo = $infoEnsayoArr[$id];
        $valorInfoEnsayo->parcela = $parcela;
        Entity::save($valorInfoEnsayo);
      }
    }
  }

  if(!$alert->hasError){
    Entity::commit();
    redirect("index.php?m=".__TRIAL_ADDED);
  }

}



function cleanName($string) {
   $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
   return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
 }

 ?>

 <div class="row">
  <div class="col-sm-8 col-md-offset-1">
    <form autocomplete="off" enctype="multipart/form-data" class="form-horizontal valid"
    method="POST" action="<?= $_SERVER["PHP_SELF"]?>">
    <input type="hidden" name="id" value="<?= _request("id") ?>">
    <fieldset>
      <!-- Form Name -->
      <legend><?= __TRIAL_VARIABLES_SPECIFY?></legend>

      <div class="form-group">
        <label class="col-md-4 control-label" for="nombre"><?= __TRIAL_EXPERIMENTAL_UNIT_NAME ?> *</label>
        <div class="col-md-6">
          <?php
          printSelect("campo_numero", null, $campos, __TRIAL_EXPERIMENTAL_UNIT_SELECT, "required ");
          ?>
          <span class="help-block">
            <?= __TRIAL_VARIABLES_EXPLANATION ?>
          </span>
        </div>
      </div>

      <?php
      foreach ($campos as $key => $value) {
        $id = cleanName($value);
        ?>
        <div class="form-group campos" id="cont-<?= $id ?>">
          <div class="col-md-4">
            Columna <?= $value;  ?>
          </div>
          <div class="col-md-6">
            <?php
            printSelect(cleanName($id), $value,$tiposCampo, __TRIAL_RESULT_FIELD_TYPE_SELECT , "required requireme");
            ?>
          </div>
        </div>

        <?php
      }
      ?>


      <!-- Button -->
      <div class="form-group">
        <div class="col-md-4 col-md-offset-4">
          <input type="submit" name="save" value="<?= __TRIAL_RESULT_SAVE ?>" class="btn btn-shadow btn-primary">
        </div>
      </div>

    </fieldset>
  </form>

</div>
</div>
<?php
require __ROOT."files/php/template/footer.php";
?>
<script>

  function bloquearNumero(){
    $(".campos").removeClass("hide");
    $(".requireme").addClass("required");
    var nombre_campo_numero = $("#campo_numero option:selected").text();
    id = nombre_campo_numero.replace(" ", "-");
    $("#cont-"+id).addClass("hide");
    $("#"+id).removeClass("required");
    console.log("#"+id);
  }
  bloquearNumero();

  $("#campo_numero").change(function(){
    bloquearNumero();
  });
</script>
