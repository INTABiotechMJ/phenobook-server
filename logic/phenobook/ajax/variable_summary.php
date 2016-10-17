<?php
$noMenu = true;
$noHeader = true;
require "../../../files/php/config/require.php";
$id_variable = _request("id_variable");
$variable = Entity::search("Variable","active AND id = '$id_variable'");
if(_request("id_phenobook")){
  $id_phenobook = _request("id_phenobook");
  $phenobook = Entity::search("Phenobook","active AND id = '$id_phenobook'");
  $regs = Entity::listMe("Registry","active AND value IS NOT NULL AND phenobook = '$phenobook->id' AND status AND variable = '$variable->id'");
}
if(_request("id_phenobooks")){
  $ids = _request("id_phenobooks");
  //$ids_str = "(".implode($ids,",").")";
  $phenos = Entity::listMe("Phenobook","active AND id IN ($ids) ORDER BY id");
  $regs = Entity::listMe("Registry","active AND value IS NOT NULL AND phenobook IN ($ids) AND status AND variable = '$variable->id'");
}
if(empty($regs)){
  die("<h5>No results for this variable yet</h5>");
}
$out = "";
$data = array();

$out .= "<h5>In phenobooks</h5>";
if(!empty($phenobook)){
  $out .= "$phenobook"."<br/>";
}
if(!empty($phenos)){
  foreach ((array)$phenos as $pheno) {
    $out .= "$pheno"."<br/>";
  }
}

if($variable->fieldType->isNumeric()){
  foreach ((array)$regs as $r) {
    if(!is_null($r)){
      $data[] = intval($r->value);
    }
  }
  $out .= "<h5>Numeric variable</h5>";
  $out .= "<b>Name: </b>$variable"."<br/>";
  $out .= "<b>Count: </b>".count($data)."<br/>";
  $out .= "<b>Min value: </b>".min($data)."<br/>";
  $out .= "<b>Max value: </b>".max($data)."<br/>";
  $out .= "<b>Sum: </b>".array_sum($data)."<br/>";
  $out .= "<b>Mean: </b>".number_format(array_sum($data)/count($data),2)."<br/>";
}

if($variable->fieldType->isDate()){
  foreach ((array)$regs as $r) {
    if(!is_null($r)){
      $data[] = $r->value;
    }
  }

  usort($data, function($a, $b) {
    $dateTimestamp1 = strtotime($a);
    $dateTimestamp2 = strtotime($b);
    return $dateTimestamp1 < $dateTimestamp2 ? -1: 1;
  });

  $out .= "<h5>Date variable</h5>";
  $out .= "<b>Name: </b>$variable"."<br/>";
  $out .= "<b>Count: </b>".count($data)."<br/>";
  $out .= "<b>Min date: </b>".$data[0]."<br/>";
  $out .= "<b>Max date: </b>".$data[count($data) - 1]."<br/>";
}

if($variable->fieldType->isCheck()){
  $count = 0;
  foreach ((array)$regs as $r) {
    if(!is_null($r) && $r->value == 1){
      $count++;
    }
  }
  $out .= "<h5>Check variable</h5>";
  $out .= "<b>Name: </b>$variable"."<br/>";
  $out .= "<b>Times checked: </b>".$count."<br/>";
}


if($variable->fieldType->isText()){
  foreach ((array)$regs as $r) {
    if(!is_null($r)){
      $data[] = $r->value;
    }
  }
  $out .= "<h5>Text variable</h5>";
  $out .= "<b>Name: </b>$variable"."<br/>";
  $out .= "<b>Registered times: </b>".count($data)."<br/>";
  $out .= "<b>Different values: </b>".count(array_unique($data))."<br/>";
}

if($variable->fieldType->isInformative()){
  foreach ((array)$regs as $r) {
    if(!is_null($r)){
      $data[] = $r->value;
    }
  }
  $out .= "<h5>Text variable</h5>";
  $out .= "<b>Name: </b>$variable"."<br/>";
  $out .= "<b>Non empty: </b>".count($data)."<br/>";
  $out .= "<b>Different values: </b>".count(array_unique($data))."<br/>";
}


if($variable->fieldType->isPhoto()){
  foreach ((array)$regs as $r) {
    if(!is_null($r)){
      $data[] = $r->value;
    }
  }
  $out .= "<h5>Photo variable</h5>";
  $out .= "<b>Name: </b>$variable"."<br/>";
  $out .= "<b>Registered times: </b>".count($data)."<br/>";
}


if($variable->fieldType->isOption()){
  $opts = Entity::listMe("Category","active AND variable = '$variable->id'");
  foreach ((array)$regs as $r) {
    if(!is_null($r)){
      $data[] = $r->value;
    }
  }
  $out .= "<h5>Option variable</h5>";
  $out .= "<b>Name: </b>$variable"."<br/>";
  $out .= "<b>Different options: </b>".count($opts)."<br/>";
  $out .= "<b>Registered times: </b>".count($data)."<br/>";
  foreach((array)$opts as $opt){
    $count = 0;
    foreach((array)$data as $d){
      if($d == $opt->id){
        $count++;
      }
    }
    $out .= "<b>Times selected option <u>$opt:</u> </b>".$count."<br/>";
  }
}

echo $out;
