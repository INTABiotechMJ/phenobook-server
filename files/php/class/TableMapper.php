<?php

/**
* creates the database structure, returns the SQL statement
* if $drop then drop all tables in $entidades
*/
function initDB($entidades) {
    $manys = array();
    $alters = array();
    $creates = array();
    
    foreach ((array) $entidades as $class) {

        $o = new $class();
//$class = get_class($o); //Toma el nombre de la clase y lo guarda en $class
//Toma los campos de la clase y los guarda en $fields
        $fields = get_class_vars($class);
        $pk = _getPk($class);
unset($fields[$pk]); //unset the pk value from the fields
$dropsSQL[] = "DROP TABLE IF EXISTS $class;";
foreach ((array) $fields as $field => $value) {
    if (_hasIgnore($o, $field)) {
unset($fields[$field]); // quits ignore
}
if (_hasCreateIgnore($o, $field)) {
unset($fields[$field]); // quits ignore
}
if (_hasRef($o, $field)) {
unset($fields[$field]); // quits refs
}
if (_hasCol($o, $field)) {
    $fields[_getCol($o, $field)] = $value;
    unset($fields[$field]);
}
if (_hasMany($o, $field)) {
    $manyName = _getMany($o, $field);
    $otherClass = _getClass($o, $field);
    $SQLM = "CREATE TABLE IF NOT EXISTS $manyName (id_$manyName INT NOT NULL AUTO_INCREMENT PRIMARY KEY";
        $SQLM .= ", " . _getPk($class) . " INT ";
        $SQLM .= ", " . _getPk(_getClass($o, $field)) . " INT";
        $SQLM .= ", FOREIGN KEY (" . _getPk($class) . ") REFERENCES $class(" . _getPk($class) . ")";
        $SQLM .= ", FOREIGN KEY (" . _getPk(_getClass($o, $field)) . ") REFERENCES $otherClass(" . _getPk(_getClass($o, $field)) . ")";
        $SQLM .= ")engine=innoDB;";
$dropsSQL[] = "DROP TABLE IF EXISTS $manyName;";
$manys[] = $SQLM;
unset($fields[$field]);
//CHANGE - INT FOR @type
}

if (_hasClass($o, $field) && !_hasMany($o, $field)) { //veo si es un objeto
    $classRef = _getClass($o, $field);
    $pkRef = _getPk(_getClass($o, $field));
    if (!$pkRef) {
        $alerts[] = "$classRef has no @pk!";
    }
    if (_hasCol($o, $field)) {
        $pkMain = _getCol($o, $field);
    } else {
        $pkMain = $pkRef;
    }
    $SQL = "ALTER TABLE $class
    ADD CONSTRAINT fk_$class" . "_" . $classRef . "_" . "$field
    FOREIGN KEY ($field)
    REFERENCES $classRef($pkRef);
    ";
    $alters[] = $SQL;
}
}

//Checkea arrays
$fields_types = array();
foreach ((array) $entidades as $class_array) {
    $o_array = new $class_array();
    $fields_array = get_class_vars($class_array);
    $pkRef_array = _getPk($class_array);
    if (!$pkRef_array) {
        $alerts[] = "$classRef has no @pk!";
    }
    foreach ((array) $fields_array as $field_array => $value_array) {
if (_hasArray($o_array, $field_array)) {//Tiene @array
    $arrayName = _getArray($o_array, $field_array);
if ($arrayName == $class) {//Pertenece a la clase
$type = _getFullType($o_array, $field_array); //Tomo el tipo
if (!$type || $type == "") {
    $type = "INT";
}
if (_hasCol($o, $field_array)) {
    $pkRef_array = _getCol($o, $field_array);
}
$fields_types[] = "$pkRef_array  $type"; //Pongo la pk y el tipo
$SQL = "ALTER TABLE $class
ADD CONSTRAINT fk_$class_array" . "_" . $class_array . "_" . "$field_array
FOREIGN KEY ($pkRef_array)
REFERENCES $class_array($pkRef_array);";
$alters[] = $SQL;
}
}
}
}

//Fin check arrays

foreach ((array) $fields as $field => $value) {//Recorre todos los campos en busqueda de los tipos de datos
    if (_hasArray($o, $field)) {
unset($fields[$field]); //Si tiene array no va
continue;
}

$type = _getFullType($o, $field); //Tomo el tipo

if (!$type || $type == "") {
    $type = "INT";
}
$fields_types[] = "$field $type"; //La deja como esta con el tipo de dato
}

if (count($fields_types) > 0) {
    $SQL = sprintf("CREATE TABLE IF NOT EXISTS $class ($pk INT NOT NULL AUTO_INCREMENT PRIMARY KEY, %s)engine=innoDB;", implode(', ', $fields_types));
    $creates[] = $SQL;
} else {
    $SQL = sprintf("CREATE TABLE IF NOT EXISTS $class ($pk INT NOT NULL AUTO_INCREMENT PRIMARY KEY)engine=innoDB;");
    $creates[] = $SQL;
//sql_update($SQL);
}

}

//DROP BEGIN

//try to drop three times in different order
$out = "";
for ($index = 0; $index < 3; $index++) {
    foreach ((array) $dropsSQL as $dropTable) {
        $out .="$dropTable;<br/>";

    }
    $dropsSQL = array_reverse($dropsSQL);
}



//DROP END
//CREATE TABLEs BEGIN
$out = "";
foreach ((array) $creates as $create) {
    $out .="$create<hr/>";
}
$out .="<br/>";

foreach ((array) $manys as $many) {
    $out .="$many<hr/>";
}
$out .="<br/>";
foreach ((array) $alters as $alter) {
    $out .="$alter<hr/>";
}

$out .="<br/>";
return $out;

}

    /**
     * Devuelve el tipo de dato de una variable $v del objeto $o seteado por @type
     * si no esta seteado devuelve false
     */
    function _getFullType($o, $v) {
        if ($o == null || $v == null)
            return;
        $refClass = new ReflectionClass(get_class($o));
        foreach ($refClass->getProperties() as $refProp) {
            if ($refProp->name == $v) {
                if (preg_match('/@type\s+(.+?)\s?$/m', $refProp->getDocComment(), $matches)) {
                    return $matches[1];
                } else {
                    return false;
                }
            }
        }
    }
    /**
     * Devuelve true si tiene la anotacion @col
     */
    function _hasCol($o, $v) {
        $refClass = new ReflectionClass(get_class($o));
        foreach ($refClass->getProperties() as $refProp) {
            if ($refProp->name == $v) {
                if (strstr($refProp->getDocComment(), "@col")) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Devuelve true si tiene la anotacion array
     */
    function _hasArray($o, $v) {
        $refClass = new ReflectionClass(get_class($o));
        foreach ($refClass->getProperties() as $refProp) {
            if ($refProp->name == $v) {
                if (strstr($refProp->getDocComment(), "@array")) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Devuelve true si tiene la anotacion array
     */
    function _hasRef($o, $v) {
        $refClass = new ReflectionClass(get_class($o));
        foreach ($refClass->getProperties() as $refProp) {
            if ($refProp->name == $v) {
                if (strstr($refProp->getDocComment(), "@ref")) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Devuelve el nombre de la clase de un atributo con @array
     * si no esta seteado devuelve false
     */
    function _getArray($o, $v) {
        if ($o == null || $v == null)
            return;
        $refClass = new ReflectionClass(get_class($o));
        foreach ($refClass->getProperties() as $refProp) {
            if ($refProp->name == $v) {
                if (preg_match('/@array\s+([^\s]+)/', $refProp->getDocComment(), $matches)) {
                    return $matches[1];
                } else {
                    return false;
                }
            }
        }
    }

    /**
     * Devuelve el nombre de la clase de un atributo con @ref
     * si no esta seteado devuelve false
     */
    function _getRef($o, $v) {
        if ($o == null || $v == null)
            return;
        $refClass = new ReflectionClass(get_class($o));
        foreach ($refClass->getProperties() as $refProp) {
            if ($refProp->name == $v) {
                if (preg_match('/@ref\s+([^\s]+)/', $refProp->getDocComment(), $matches)) {
                    return $matches[1];
                } else {
                    return false;
                }
            }
        }
    }

    /**
     * Devuelve el nombre de la clase de un atributo con @refName
     * si no esta seteado devuelve false
     */
    function _getRefName($o, $v) {
        if ($o == null || $v == null)
            return;
        $refClass = new ReflectionClass(get_class($o));
        foreach ($refClass->getProperties() as $refProp) {
            if ($refProp->name == $v) {
                if (preg_match('/@refName\s+([^\s]+)/', $refProp->getDocComment(), $matches)) {
                    return $matches[1];
                } else {
                    return false;
                }
            }
        }
    }

    /**
     * Devuelve true si tiene la anotacion many
     */
    function _hasMany($o, $v) {
        $refClass = new ReflectionClass(get_class($o));
        foreach ($refClass->getProperties() as $refProp) {
            if ($refProp->name == $v) {
                if (strstr($refProp->getDocComment(), "@many")) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * return true if field $v of object $o has @ignore
     */
    function _hasIgnore($o, $v) {
        $refClass = new ReflectionClass(get_class($o));
        foreach ($refClass->getProperties() as $refProp) {
            if ($refProp->name == $v) {
                if (strstr($refProp->getDocComment(), "@ignore")) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * return true if field $v of object $o has @ignore
     */
    function _hasCreateIgnore($o, $v) {
        $refClass = new ReflectionClass(get_class($o));
        foreach ($refClass->getProperties() as $refProp) {
            if ($refProp->name == $v) {
                if (strstr($refProp->getDocComment(), "@createIgnore")) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * return true if field $v of object $o has @saveIgnore
     */
    function _hasSaveIgnore($o, $v) {
        $refClass = new ReflectionClass(get_class($o));
        foreach ($refClass->getProperties() as $refProp) {
            if ($refProp->name == $v) {
                if (strstr($refProp->getDocComment(), "@saveIgnore")) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Devuelve el nombre de la tabla intermedia de un atributo con @many
     * si no esta seteado devuelve false
     */
    function _getMany($o, $v) {
        if (empty($o) || empty($v))
            return false;
        $refClass = new ReflectionClass(get_class($o));
        foreach ($refClass->getProperties() as $refProp) {
            if ($refProp->name == $v) {
                if (preg_match('/@many\s+([^\s]+)/', $refProp->getDocComment(), $matches)) {
                    return $matches[1];
                } else {
                    return false;
                }
            }
        }
    }

    /**
     * Devuelve true si $v es un objeto seteado por @class
     * si no esta seteado devuelve false
     */
    function _hasClass($o, $v) {
        if ($o == null || $v == null)
            return;
        $refClass = new ReflectionClass(get_class($o));
        foreach ($refClass->getProperties() as $refProp) {
            if ($refProp->name == $v) {
                if (strstr($refProp->getDocComment(), "@class")) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Devuelve la clase de una variable $v del objeto $o seteado por @class
     * si no esta seteado devuelve false
     */
    function _getClass($o, $v) {
        if ($o == null || $v == null)
            return;
        $refClass = new ReflectionClass(get_class($o));
        foreach ($refClass->getProperties() as $refProp) {
            if ($refProp->name == $v) {
                if (preg_match('/@class\s+([^\s]+)/', $refProp->getDocComment(), $matches)) {
                    return $matches[1];
                } else {
                    return false;
                }
            }
        }
    }

    /**
     * Devuelve la pk de la clase como String
     */
    function _getPk($c) {
        if ($c == null)
            return;
        try {
            $refClass = new ReflectionClass($c);
        } catch (Exception $e) {
            die("Error" . $e->__toString());
        }
        foreach ($refClass->getProperties() as $refProp) {
            if (strstr($refProp->getDocComment(), "@pk")) {
                return $refProp->name;
            }
        }
        try {
            echo "The class $c hasn't primary key";
        } catch (Exception $e) {
            die("Error" . $e->__toString());
        }
    }

    /**
     * Devuelve la pk de la clase como String
     */
    function _isLazy($o, $v) {
        if ($o == null || $v == null)
            return;
        $refClass = new ReflectionClass(get_class($o));
        foreach ($refClass->getProperties() as $refProp) {
            if ($refProp->name == $v) {
                if (strstr($refProp->getDocComment(), "@lazy")) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Devuelve la pk de la clase como String
     */
    function _hasSave($o, $v) {
        $refClass = new ReflectionClass(get_class($o));
        foreach ($refClass->getProperties() as $refProp) {
            if ($refProp->name == $v) {
                if (strstr($refProp->getDocComment(), "@save")) {
                    return true;
                }
            }
        }
        return false;
    }
