<?php
$POST_ARRAY_COUNT[] = array(); //Counter to the current item of a post array. Just for this file use

function redirect($link) {
    echo '<script type=""text/javascript"">window.location.href="' . $link . '"</script>';
    exit;
}

/**
** $sth = assoc($SQL);
** $sth->bindParam(":query",$query_clean,PDO::PARAM_STR);
** $sth->execute();
** $results = $sth->rowCount;
** $sth->execute
** while($row = $sth->fetch()){}
*/
function assoc($SQL){
    try {

        $sth = $GLOBALS["conn"]->prepare($SQL);
    } catch (Exception $e) {
        echo $SQL."<br/>";
        print_r($GLOBALS["conn"]->errorInfo());
        exit;
    }
    if (!$sth) {
        echo $SQL."<br/>";
        print_r($GLOBALS["conn"]->errorInfo());
        exit;
    }
    $sth->setFetchMode(PDO::FETCH_ASSOC);
    return $sth;
}
function query($SQL){
    $sth = $GLOBALS["conn"]->query($SQL);
    if (!$sth) {
        echo $SQL."<br/>";
        print_r($GLOBALS["conn"]->errorInfo());
        exit;
    }
    return $GLOBALS["conn"]->lastInsertId();
}

function error($SQL, $msg){
    echo "<br/>SQL: ";
    echo $SQL;
    echo "<br/>Error: ";
    echo $msg;
    exit;
}

function conn(){
    return $GLOBALS["conn"];
}
/**
* Clean a variable with real_escape_string
**/
function _clean($conn, $string){
    return $GLOBALS["conn"]->real_escape_string($string);
}
function _request($name){
    if(isset($_REQUEST[$name])){
        return $_REQUEST[$name];
    }
    return false;
}

function _get($name){
    if(isset($_GET[$name])){
        return $_GET[$name];
    }
    return false;
}
function _post($name){
    if(isset($_POST[$name])){
        return $_POST[$name];
    }
    return false;
}
function _post_or_null($name){
    if(isset($_POST[$name])){
        return $_POST[$name];
    }
    return null;
}


function resetArrayPostCounter() {
    global $POST_ARRAY_COUNT;
    $POST_ARRAY_COUNT = null;
}

/**
 * returns true if the checkbox $name is checked
 */
function _post_check($name) {
    return isset($_POST[$name]) ? true : false;
}

/**
 * Returns the next element of an array send by post method (starting from $_POST[name][0])
 * like <input type='text' name='array[]' >
 * @param <type> $name
 * @return <type>
 */
function _post_array_next($name) {
    global $POST_ARRAY_COUNT;
    if (isset($_POST[$name])) {
        if (!isset($POST_ARRAY_COUNT[$name])) {
            $POST_ARRAY_COUNT[$name] = 0;
        }
        if (!isset($_POST[$name][$POST_ARRAY_COUNT[$name]])) {
            $_POST[$name][$POST_ARRAY_COUNT[$name]] = "";
        }
        return $_POST[$name][$POST_ARRAY_COUNT[$name] ++];
    } else {
        return false;
    }
}

function pageLimit($resultsPerPage,$totalResults, $currentPage){
    $countPages = ceil($totalResults / $resultsPerPage);
    if($currentPage == 1){
        $limit1 = 0;
    }else{
        $limit1 = ($currentPage - 1) * $resultsPerPage ;
    }
    return " $limit1, $resultsPerPage ";
}

function pageTable($resultsPerPage, $totalResults, $currentPage){

    if(empty($currentPage)){
        $currentPage = 1;
    }

    $totalPages = ceil($totalResults / $resultsPerPage);




    $out = "<nav>";
    $out .= "<ul class='pagination'>";

    if($currentPage == 1){
        $out .= "<li class='disabled'><a ><span aria-hidden='true'>&laquo;</span><span class='sr-only'>Previous</span></a></li>";
    }else{
        $query = http_build_query(array_merge($_GET, array('page'=>$currentPage - 1)));
        $url = strtok($_SERVER["REQUEST_URI"],'?');
        $url .= "?".$query;
        $out .= "<li><a href='$url'><span aria-hidden='true'>&laquo;</span><span class='sr-only'>Previous</span></a></li>";
    }


    for ($i = 1; $i <= $totalPages ; $i++) {
        if($currentPage == $i){
            $currentPageDisabled = "class='disabled'";
        }else{
            $currentPageDisabled = "";
        }
        $query = http_build_query(array_merge($_GET, array('page'=>$i)));
        $url = strtok($_SERVER["REQUEST_URI"],'?');
        $url .= "?".$query;
        $out .= "<li $currentPageDisabled><a href='$url'>$i</a></li>";
    }

    if($currentPage == $totalPages){
        $out .= "<li class='disabled'><a><span aria-hidden='true'>&raquo;</span><span class='sr-only'>Next</span></a></li>";
    }else{
        $query = http_build_query(array_merge($_GET, array('page'=>$currentPage + 1)));
        $url = strtok($_SERVER["REQUEST_URI"],'?');
        $url .= "?".$query;
        $out .= "<li><a href='$url'><span aria-hidden='true'>&raquo;</span><span class='sr-only'>Next</span></a></li>";
    }

    $out .= "</ul>";
    $out .= "</nav>";
    return $out;
}

function genTable($data, $footer = true, $totalResults = null, $divTableClass = null){
    $printed = false;
    $cont = 0;
    $out = "";
    foreach((array) $data as $item){
        $cont++;
        if(!$printed){
            $printed = true;
            $out .= "<div class='table-responsive $divTableClass'>";
            $out .= "<table class='table table-hover'>";
            $out .= "<tr>";
            foreach((array)$item as $key => $value){
                $out .= "<th>$key</th>";
            }
            $out .= "</tr>";
        }
        $out .= "<tr>";
        $len = count($item);
        $i = 0;
        foreach((array)$item as $key => $value){
            if ($i++ == $len - 1) {
                //last
            }
            $out .= "<td>$value</td>";
        }
        $out .= "</tr>";
    }
    if($printed){
        if($footer){
            $out .= "<tr class='trfooter'>";

            $out .= "<td colspan='100%'>";
            if($totalResults != null){
                $out .= "<b>&Sigma; $cont / $totalResults</b> | ";
            }else{
                $out .= "<b>&Sigma; $cont</b> | ";
            }
            $out .= "<a href='#' class='export'><span class='badge'>csv</span></a>";
            $out .= "</td>";

            $out .= "</tr>";
        }
        $out .= "</table>";
        $out .= "</div>";
    }else{
        $out .= "No results";
    }

    $out .= '';
    return $out;
}

class Tr{
    var $color;
    var $bold;
    var $id;
    var $td;
    // is_a(obj, classname)
}

function pager($count, $range, $where){
    $countPages = ceil($count / $range);
    if($countPages == 1){
        return;
    }
    $current = _get("page")? _get("page"): 1;
    $out = "<ul class='pagination'>";
    if($current > 1){
        $prev = $current - 1;
        $out .= "<li><a href='$where&page=$prev'>&laquo;</a></li>";
    }
    for ($i=1; $i <= $countPages; $i++) {
        if($current != $i){
            $out .= "<li><a href='$where&page=$i'>$i</a></li>";
        }else{
            $out .= "<li class='active'><a href='$where&page=$i'>$i</a></li>";
        }
    }
    if($current < $countPages){
        $next = $current + 1;
        $out .= "<li><a href='$where&page=$next'>&raquo;</a></li>";
    }
    $out .= "</ul>";
    return $out;
}

function pagerLimit($count, $range){
    $current = _get("page")? _get("page"): 1;
    $countPages = ceil($count / $range);
    if($current == 1){
        $limit1 = 0;
    }else{
        $limit1 = ($current - 1) * $range - 1;
    }
    return " LIMIT $limit1, $range ";
}

function customDateFormat($dateString, $inFormat,$outFormat){
    $date = date_create_from_format($inFormat, $dateString);
    return $date->format($outFormat);
}

function timeStampSQL($dateString){
    return customDateFormat($dateString, "d/m/Y H:i", "Y-m-d H:i");
}

function timeStampHumano($timeStamp) {
    if (!empty($timeStamp)) {
        return date("d/m/Y H:i:s", strtotime($timeStamp));
    } else {
        return;
    }
}
function timeStampFecha($timeStamp) {
    if (!empty($timeStamp)) {
        return date("d/m/Y", strtotime($timeStamp));
    } else {
        return;
    }
}
function timeStampHora($timeStamp) {
    if (!empty($timeStamp)) {
        return date("G:i:s", strtotime($timeStamp));
    } else {
        return;
    }
}


function fechaHumano($fecha) {
    if (empty($fecha)) {
        return;
    }
    $splitArray = explode("-", $fecha);
    if (isset($splitArray[2]) && isset($splitArray[1]) && isset($splitArray[0])) {
        $newDate = $splitArray[2] . "/" . $splitArray[1] . "/" . $splitArray[0];
        return $newDate;
    }
    return false;
}


function horaHumano($hora) {
    if (empty($hora)) {
        return;
    }
    $splitArray = explode(":", $hora);
    if (isset($splitArray[1]) && isset($splitArray[0])) {
        $newTime = $splitArray[0] . ":" . $splitArray[1] ;
        return $newTime;
    }
    return false;
}

function fechaSQL($fecha) {
    if (empty($fecha)) {
        return;
    }
    $splitArray = explode("/", $fecha);
    if (isset($splitArray[2]) && isset($splitArray[1]) && isset($splitArray[0])) {
        $newDate = $splitArray[2] . "-" . $splitArray[1] . "-" . $splitArray[0];
        return $newDate;
    } else {
        return "";
    }
}


function d(&$var, $var_name = NULL, $indent = NULL, $reference = NULL) {
    $do_dump_indent = "<span style='color:#666666;'>|</span> &nbsp;&nbsp; ";
    $reference = $reference . $var_name;
    $keyvar = 'the_do_dump_recursion_protection_scheme';
    $keyname = 'referenced_object_name';

// So this is always visible and always left justified and readable
    echo "<div style='text-align:left; background-color:white; font: 100% monospace; color:black;'>";

    if (is_array($var) && isset($var[$keyvar])) {
        $real_var = &$var[$keyvar];
        $real_name = &$var[$keyname];
        $type = ucfirst(gettype($real_var));
        echo "$indent$var_name <span style='color:#666666'>$type</span> = <span style='color:#e87800;'>&amp;$real_name</span><br>";
    } else {
        $var = array($keyvar => $var, $keyname => $reference);
        $avar = &$var[$keyvar];

        $type = ucfirst(gettype($avar));
        if ($type == "String")
            $type_color = "<span style='color:green'>";
        elseif ($type == "Integer")
            $type_color = "<span style='color:red'>";
        elseif ($type == "Double") {
            $type_color = "<span style='color:#0099c5'>";
            $type = "Float";
        } elseif ($type == "Boolean")
        $type_color = "<span style='color:#92008d'>";
        elseif ($type == "NULL")
            $type_color = "<span style='color:black'>";

        if (is_array($avar)) {
            $count = count($avar);
            echo "$indent" . ($var_name ? "$var_name => " : "") . "<span style='color:#666666'>$type ($count)</span><br>$indent(<br>";
                $keys = array_keys($avar);
                foreach ($keys as $name) {
                    $value = &$avar[$name];
                    d($value, "['$name']", $indent . $do_dump_indent, $reference);
                }
                echo "$indent)<br>";
} elseif (is_object($avar)) {
    echo "$indent$var_name <span style='color:#666666'>$type</span><br>$indent(<br>";
        foreach ($avar as $name => $value)
            d($value, "$name", $indent . $do_dump_indent, $reference);
        echo "$indent)<br>";
} elseif (is_int($avar))
echo "$indent$var_name = <span style='color:#666666'>$type(" . strlen($avar) . ")</span> $type_color" . htmlentities($avar) . "</span><br>";
elseif (is_string($avar))
    echo "$indent$var_name = <span style='color:#666666'>$type(" . strlen($avar) . ")</span> $type_color\"" . htmlentities($avar) . "\"</span><br>";
elseif (is_float($avar))
    echo "$indent$var_name = <span style='color:#666666'>$type(" . strlen($avar) . ")</span> $type_color" . htmlentities($avar) . "</span><br>";
elseif (is_bool($avar))
    echo "$indent$var_name = <span style='color:#666666'>$type(" . strlen($avar) . ")</span> $type_color" . ($avar == 1 ? "TRUE" : "FALSE") . "</span><br>";
elseif (is_null($avar))
    echo "$indent$var_name = <span style='color:#666666'>$type(" . strlen($avar) . ")</span> {$type_color}NULL</span><br>";
else
    echo "$indent$var_name = <span style='color:#666666'>$type(" . strlen($avar) . ")</span> " . htmlentities($avar) . "<br>";

$var = $var[$keyvar];
}

echo "</div>";
}

/**
 * Imprime un select con la clave $postSelected seleccionada (puede ser array)
 * y en $array tiene las claves y valores $array[valor] = clave
 * $nombre = nombre del select
 * $id = id del select. por omision es el nombre
 * $nombre a mostrar. En la opcion por defecto muestra Sel. $nombreAMostrar
 * options (ej: onchange="doSomething();")
 */
function printSelect($nombre, $postSelected, $array, $nombreAMostrar = null, $class=null, $options = null, $id = null) {

    if (isset($id)) {
        $id = "id='$id'";
    } else {
        $id = "id='$nombre'";
    }
    echo "<select name='$nombre' $id class='form-control $class' $options >";
    if (empty($postSelected)) {
        $selected = "selected";
    } else {
        $selected = "";
    }
    if ($nombreAMostrar != null) {
        echo "<option value='' $selected>$nombreAMostrar</option>";
    }
    foreach ((array) $array as $key => $value) {
        if (is_array($postSelected)) {
            if (array_key_exists($key, $postSelected)) {
                $selected = "selected";
            } else {
                $selected = "";
            }
        } else {
            if ($postSelected == "$key") {
                $selected = "selected";
            } else {
                $selected = "";
            }
        }
        echo "<option value='$key' $selected>$value</option>";
    }
    echo "</select>";
}

function check($nombre, $checked, $class = null, $id = null) {
    $out = "";
    if ($id == null) {
        $id = $nombre;
    }
    if ($checked) {
        $checked = "checked='checked'";
    } else {
        $checked = "";
    }
    $out .= "<input type='checkbox' name='$nombre' value='1' id='$nombre' $checked class='$class'> ";
    return $out;
}

function form_check($nombre, $nombreAMostrar, $checked, $class = null, $id = null, $value = "1") {
    if ($id == null) {
        $id = $nombre;
    }
    if ($checked) {
        $checked = "checked='checked'";
    } else {
        $checked = "";
    }
    $out = "<div class='checkbox'>";
    $out .= "<label>";
    $out .= "<input type='checkbox' name='$nombre' value='$value' $checked class='$class'> ";
    $out .= "$nombreAMostrar";
    $out .= "</label>";
    $out .= "</div>";
    return $out;
}

function form_option_grouped($showName, $name,$id, $value, $post, $default = false, $extras = null){
    $active = "";
    $checked = "";

    if($post == $value){
        $active = "active";
        $checked = "checked";
    }
    if($post == false && $default){
       $active = "active";
       $checked = "checked";
   }
   $out = "<label class='btn btn-default $active'>";
   $out .= "<input type='radio' $checked name='$name' id='$id' value='$value' $extras>";
   $out .= "$showName";
   $out .= "</label>";
   return $out;
}


function form_option($showName, $name,$id, $value, $post, $default = false, $extras = null){
    $active = "";
    $checked = "";

    if($post == $value){
        $active = "active";
        $checked = "checked";
    }
    if($post == false && $default){
       $active = "active";
       $checked = "checked";
   }

   $out = "<input type='radio' $checked name='$name' id='$id' value='$value' $extras>";
   $out .= "<label for='$id'>$showName";
   $out .= "</label>";
   return $out;
}


function stamp(){
    return date('Y-m-d H:i:s');
}

function bgScript($command){
    shell_exec("php ".__ROOT."files/php/scripts/$command > /dev/null 2>/dev/null &");
}

function sendMail($to, $subject, $message, $from = false, $cc = false){
    if($from){
        $headers = "From: " . $from . "\r\n";
        $headers .= "Reply-To: ". $from . "\r\n";
    }
    if($cc){
        $headers .= "CC: $cc\r\n";
    }
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
    $message = '<html><body>$message</body></html>';
    mail($to, $subject, $message, $headers);
}
function paramError($msg = null){
    redirect(__URL."?e=Error de parámetros&m=$msg");
}




function ext($fileName){
    $path_info = pathinfo($fileName);
    return $path_info['extension'];
}

function subir($file,$extensiones_permitidas){
    if(empty($file["name"])){
        return null;
    }
    $ret = array();


    $dir_rel = "files/uploads/" . date("Y") . "/". date("m") ."/";
    $dir = __ROOT.$dir_rel;

    if (!file_exists($dir)) {
        if(!@mkdir($dir, 0777, true)){
            $ret["error"] = 1;
            $ret["msg"] = "No se puede crear el directorio";
            return $ret;
        }
    }

    $fileName = time() . rand(0, 10000) .".". ext($file["name"]);

    if ($file["size"] > 7000 * 1024) {
        $ret["error"] = 1;
        $ret["msg"] = "El archivo es más grande que 7 Mb";
        return $ret;
    }

    $name = $file["name"];
    $extension_obtenida = pathinfo($name, PATHINFO_EXTENSION);
    if(!in_array($extension_obtenida, $extensiones_permitidas)){
        $ret["error"] = 1;
        $ret["msg"] =  "Extensión no permitida";
        return $ret;
    }
    $result = move_uploaded_file($file["tmp_name"], $dir.$fileName);

    $ret["name"] = $dir_rel.$fileName;
    $ret["filename"] = $fileName;
    if($file["error"] == 0){
        $ret["error"] = 0;
    }else{
        $ret["error"] = 1;
        switch($file["error"]){
            case 1:
            $ret["msg"] =  "El archivo es más grande que lo permitido";
            break;
            case 2:
            $ret["msg"] =  "El archivo es más grande que lo permitido (2)";
            break;
            case 3:
            $ret["msg"] =  "El archivo no ha sido subido totalmente";
            break;
            case 4:
            $ret["msg"] =  "El archivo no ha sido subido";
            break;
            default:
            $ret["msg"] =  "Error genérico";
        }
    }
    return $ret;
}


function utf8_converter($array)
{
    array_walk_recursive($array, function(&$item, $key){
        if(!mb_detect_encoding($item, 'utf-8', true)){
            $item = utf8_encode($item);
        }
    });

    return $array;
}
/**
* @link http://gist.github.com/385876
*/
function csv_to_array($filename='', $delimiter=';', $onlyHeader = false)
{
    setlocale(LC_ALL, 'es_ES.UTF8');

    if(!file_exists($filename) || !is_readable($filename)){
        return FALSE;
    }
    $header = NULL;
    $data = array();
    if (($handle = fopen($filename, 'r')) !== FALSE){
        while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE){
            if(!$header){
                $header = utf8_converter($row);
            }else{
                $data[] = array_combine($header, $row);
            }
            if($onlyHeader){
                continue;
            }
        }
        fclose($handle);
    }
    return $data;
}

function obj2arr($objects, $toStringField = false, $idField = false, $htmlentities = false){
    $res = array();
    if(!$idField){
        $idField = "id";
    }
    if(empty($objects)){
      return array();
    }
    foreach((array)$objects as $o){
        if($toStringField){
            if($htmlentities){
                $res[$o->{$idField}] = htmlentities($o->{$toStringField}, ENT_QUOTES,'UTF-8');
            }else{
                $res[$o->{$idField}] = $o->{$toStringField};
            }
        }else{
            if($htmlentities){
                $res[$o->{$idField}] = htmlentities($o->__toString(), ENT_QUOTES,'UTF-8');
            }else{
                $res[$o->{$idField}] = $o->__toString();
            }
        }
    }
    return $res;
}

function valueOr($variable, $default){
    return !empty($variable)?$variable:$default;
}

function array2parameter($array){
    $out = "";
    foreach ($array as $key => $value) {
        $out .= "-$key $value ";
    }
    return $out;
}

/**
 * Imprime un select con la clave $postSelected seleccionada (puede ser array)
 * y en $array tiene las claves y valores $array[valor] = clave
 * $nombre = nombre del select
 * $id = id del select. por omision es el nombre
 * $nombre a mostrar. En la opcion por defecto muestra Sel. $nombreAMostrar
 * options (ej: onchange="doSomething();")
 */
function html_select($nombre, $postSelected, $array, $nombreAMostrar = null, $class=null, $options = null, $id = null) {

    if (isset($id)) {
        $id = "id='$id'";
    } else {
        $id = "id='$nombre'";
    }
    $out = "<select data-type='select' name='$nombre' $id class='form-control $class' $options >";
    if (empty($postSelected)) {
        $selected = "selected";
    } else {
        $selected = "";
    }
    if ($nombreAMostrar != null) {
        $out .= "<option value='' $selected>$nombreAMostrar</option>";
    }
    foreach ((array) $array as $key => $value) {
        if (is_array($postSelected)) {
            if (array_key_exists($key, $postSelected)) {
                $selected = "selected";
            } else {
                $selected = "";
            }
        } else {
            if ($postSelected == "$key") {
                $selected = "selected";
            } else {
                $selected = "";
            }
        }
        $out .= "<option value='$key' $selected>$value</option>";
    }
    $out .= "</select>";
    return $out;
}

function thumb($url, $height = 50, $width = 50){
    $url = __URL_FULL.$url;
    return "<img src='".__URL."files/php/scripts/thumb/thumb.php?src=$url&w=$width&h=$height&zc=1'>";
}
function thumbURL($url, $height = 50, $width = 50){
    $url = __URL_FULL.$url;
    return __URL."files/php/scripts/thumb/thumb.php?src=$url&w=$width&h=$height&zc=1'";

}



function i($str){
    return "<span class='object-name'>$str</span>";
}


define("ICON_ADD", "glyphicon glyphicon-plus");
define("ICON_BACK", "glyphicon glyphicon-arrow-left");
define("TYPE_PRIMARY", "btn-primary");
define("TYPE_DEFAULT", "btn-default");

function btn($text, $link, $icon = "", $type = TYPE_PRIMARY, $size = "btn-md"){
    return "<a href='$link' class='btn $type $size'><i class='$icon'></i> $text</a> ";
}

/**
 * Class casting
 *
 * @param string|object $destination
 * @param object $sourceObject
 * @return object
 */
function cast($destination, $sourceObject)
{
    if (is_string($destination)) {
        $destination = new $destination();
    }
    $sourceReflection = new ReflectionObject($sourceObject);
    $destinationReflection = new ReflectionObject($destination);
    $sourceProperties = $sourceReflection->getProperties();
    foreach ($sourceProperties as $sourceProperty) {
        $sourceProperty->setAccessible(true);
        $name = $sourceProperty->getName();
        $value = $sourceProperty->getValue($sourceObject);
        if ($destinationReflection->hasProperty($name)) {
            $propDest = $destinationReflection->getProperty($name);
            $propDest->setAccessible(true);
            $propDest->setValue($destination,$value);
        } else {
            $destination->$name = $value;
        }
    }
    return $destination;
}

function bool2word($bool,$yes="Si",$no="No"){
  return ($bool)?$yes:$no;
}
