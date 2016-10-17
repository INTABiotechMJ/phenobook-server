<?php

class Entity{

	function __construct(){
	}

	static function save($object){
		$className = get_class($object);
		$__pk = self::_getPk($className);

		//check if some var has a relation (@class)
		$vars = get_class_vars($className);

			$var_classes = array(); // Variables with @class annotation

			$attrs = array();
			$_attrs = array(); //with preceding :
			$values = array();

			$fakeObject = clone $object;
			foreach((array) $vars as $var => $empty){
				$ref_class = self::_getClass($className, $var);
				if($ref_class){
					$var_classes[$var] = $ref_class;
				}
			}

		//if there is some @class annotation, then search in all objects and replace the value
			if(!empty($var_classes)){
				foreach ($var_classes as $key => $value) {
					$varValue = $object->{$key};
					if(!empty($varValue)){
						$class = self::_getClass($className, $key);;
						$pk = self::_getPk($class);
						$pkValue = $varValue->{$pk};
						//$object->{$key} = $pkValue;
						$fakeObject->{$key} = $pkValue;
					}
				}
			}
			foreach((array) $vars as $var => $empty){
				if($__pk == $var){
					continue;
				}
				if(self::_hasIgnore($className, $var)){
					continue;
				}
				if(self::_hasSaveIgnore($className, $var)){
					continue;
				}
				if($object->{$var} === null){
					continue;
				}
				$attrs[] = $var;
				$_attrs[] = ":$var";
				$values[":$var"] = $fakeObject->{$var};
			}

			$fields = implode($attrs, ",");
			$_fields = implode($_attrs, ",");

			$SQL = "INSERT INTO $className ($fields) VALUES ($_fields)";
			$sth = $GLOBALS["conn"]->prepare($SQL);
			try{
				$sth->execute($values);
			}catch(Exception $e){
				echo $SQL;
				echo $e;
				die();
			}

			$object->{$__pk} = $GLOBALS["conn"]->lastInsertId();

			if (!$sth) {
				echo $SQL."<br/>";
				print_r($GLOBALS["conn"]->errorInfo());
			}

		}

		static function countMe($className, $filter = null){
			$SQL = "SELECT COUNT(*) as classCount FROM $className ";
			$SQL .= ($filter) ? "WHERE $filter" : "";
			$sth = $GLOBALS["conn"]->query($SQL);
			if (!$sth) {
				echo $SQL."<br/>";
				print_r($GLOBALS["conn"]->errorInfo());
			}
			$sth->setFetchMode(PDO::FETCH_ASSOC);
			$row =  $sth->fetch();
			return $row["classCount"];
		}


		static function update($object){
			$className = get_class ($object);
			$__pk = self::_getPk($className);

			$__pkVal = $object->{$__pk};

			//check if some var has a relation (@class)
			$vars = get_class_vars($className);

			$var_classes = array(); // Variables with @class annotation

			$attrs = array();
			$_attrs = array(); //with preceding :
			$values = array();
			$fakeObject = clone $object;

			foreach((array) $vars as $var => $empty){
				$ref_class = self::_getClass($className, $var);
				if($ref_class){
					$var_classes[$var] = $ref_class;
				}
			}

			//if there is some @class annotation, then search in all objects and replace the value
			if(!empty($var_classes)){
				foreach ($var_classes as $key => $value) {
					$varValue = $object->{$key};
					if(!empty($varValue)){
						$class = self::_getClass($className, $key);;
						$pk = self::_getPk($class);
						$pkValue = $varValue->{$pk};
						//$object->{$key} = $pkValue;
						$fakeObject->{$key} = $pkValue;
					}
				}
			}

			foreach((array) $vars as $var => $empty){
				if($__pk == $var){
					continue;
				}
				if(self::_hasIgnore($className, $var)){
					continue;
				}
				$attrs[] = "$var = :$var";
				$values[":$var"] = $fakeObject->{$var};
			}


			$fields = implode($attrs, ",");

			$SQL = "UPDATE $className SET $fields WHERE $__pk = '$__pkVal' LIMIT 1";

			$sth = $GLOBALS["conn"]->prepare($SQL);
			$sth->execute($values);

			if (!$sth) {
				echo $SQL."<br/>";
				print_r($GLOBALS["conn"]->errorInfo());
				exit;
			}

		}

		static function listMe($className, $filter = false){
			$SQL = "SELECT * FROM $className ";
			$SQL .= ($filter) ? "WHERE $filter" : "";
			$sth = $GLOBALS["conn"]->query($SQL);
			if (!$sth) {
				echo $SQL."<br/>";
				print_r($GLOBALS["conn"]->errorInfo());
			}
			$sth->setFetchMode(PDO::FETCH_CLASS, $className);
			$objects = $sth->fetchAll();


			//check if some var has a relation (@class)
			$vars = get_class_vars($className);

			$var_classes = array(); // Variables with @class annotation

			foreach((array) $vars as $var => $empty){
				$ref_class = self::_getClass($className, $var);
				if($ref_class){
					$var_classes[$var] = $ref_class;
				}
			}

		//if there is some @class annotation, then search in all objects and replace the value
			if(!empty($var_classes)){
				foreach ($objects as $object) {
					foreach ($var_classes as $key => $value) {
						$varValue = $object->{$key};
						$varClassName = $value;
						$varName = $key;
						$object->{$varName} = self::load("$value",$varValue);
					}
				}
			}

			return $objects;
		}

		static function load($className, $id){
			$pk = self::_getPk($className);
			$SQL = "SELECT * FROM $className ";
			$SQL .= "WHERE $pk = '$id' ";
			$sth = $GLOBALS["conn"]->query($SQL);
			if (!$sth) {
				echo $SQL."<br/>";
				print_r($GLOBALS["conn"]->errorInfo());
			}
			$sth->setFetchMode(PDO::FETCH_CLASS, $className);
			$object = $sth->fetch();
			if(!$object){
				return null;
			}

			//check if some var has a relation (@class)
			$vars = get_class_vars($className);

			$var_classes = array(); // Variables with @class annotation

			foreach((array) $vars as $var => $empty){
				$ref_class = self::_getClass($className, $var);
				if($ref_class){
					$var_classes[$var] = $ref_class;
				}
			}

			//if there is some @class annotation, then search in all objects and replace the value
			if(!empty($var_classes)){
				foreach ($var_classes as $key => $value) {
					$varValue = $object->{$key};
					$varClassName = $value;
					$varName = $key;
					$object->{$varName} = Entity::load($varClassName,$varValue);
				}
			}
			return $object;
		}


		static function search($className, $filter = false, $limit = false){
			$SQL = "SELECT * FROM $className ";
			if($limit){
				$SQL .= ($filter) ? "WHERE $filter $limit " : " $limit ";
			}else{
				$SQL .= ($filter) ? "WHERE $filter LIMIT 1" : " LIMIT 1 ";
			}
			$sth = $GLOBALS["conn"]->query($SQL);
			if (!$sth) {
				echo $SQL."<br/>";
				print_r($GLOBALS["conn"]->errorInfo());
			}
			$sth->setFetchMode(PDO::FETCH_CLASS, $className);
			$object = $sth->fetch();
			if(!$object){
				return false;
			}

			//check if some var has a relation (@class)
			$vars = get_class_vars($className);

			$var_classes = array(); // Variables with @class annotation

			foreach((array) $vars as $var => $empty){
				$ref_class = self::_getClass($className, $var);
				if($ref_class){
					$var_classes[$var] = $ref_class;
				}
			}
			//if there is some @class annotation, then search in all objects and replace the value
			if(!empty($var_classes)){
				foreach ($var_classes as $key => $value) {
					$varValue = $object->{$key};
					$varClassName = $value;
					$varName = $key;

					$object->{$varName} = self::load($value,$varValue);
				}
			}

			return $object;
		}

		static function query($SQL){
			$sth = $GLOBALS["conn"]->query($SQL);
			if (!$sth) {
				echo $SQL."<br/>";
				print_r($GLOBALS["conn"]->errorInfo());
			}
			return $sth;
		}
		static function begin(){
			query("BEGIN");
		}
		static function commit(){
			query("COMMIT");
		}
		static function rollback(){
			query("ROLLBACK");
		}

	/**
	*HELPER FUNCTIONS
	*/

	/**
	* returns the var marked as @pk
	*/
	private static function _getPk($className) {
		if ($className == null)
			return;
		try {
			$refClass = new ReflectionClass($className);
		} catch (Exception $e) {
			die("Error" . $e->__toString());
		}
		foreach ($refClass->getProperties() as $refProp) {
			if (strstr($refProp->getDocComment(), "@pk")) {
				return $refProp->name;
			}
		}
        //__error("The class $className hasn't primary key");
	}

	/**
	* returns the @class attribute of a var of object o, false if it is has not
	*/
	private static function _getClass($class, $var) {
		if ($class == null || $var == null)
			return;
		$refClass = new ReflectionClass($class);
		foreach ($refClass->getProperties() as $refProp) {
			if ($refProp->name == $var) {
				if (preg_match('/@class\s+([^\s]+)/', $refProp->getDocComment(), $matches)) {
					return $matches[1];
				} else {
					return false;
				}
			}
		}
	}
	/**
     * return true if field $v of object $o has @ignore
     */
	private static function _hasIgnore($className, $v) {
		$refClass = new ReflectionClass($className);
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
	private static function _hasSaveIgnore($className, $v) {
		$refClass = new ReflectionClass($className);
		foreach ($refClass->getProperties() as $refProp) {
			if ($refProp->name == $v) {
				if (strstr($refProp->getDocComment(), "@saveIgnore")) {
					return true;
				}
			}
		}
		return false;
	}
}
