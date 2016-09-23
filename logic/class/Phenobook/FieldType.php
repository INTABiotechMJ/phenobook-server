<?php
class FieldType extends Object{
	/**
	*@type VARCHAR(100)
	*/
	var $name;
	/**
	*@type INT NOT NULL
	*/
	var $type;

	/**
	*@ignore
	*/
	static $TYPE_TEXT = 1;
	/**
	*@ignore
	*/
	static $TYPE_OPTION = 2;
	/**
	*@ignore
	*/
	static $TYPE_CHECK = 3;
	/**
	*@ignore
	*/
	static $TYPE_NUMBER = 4;
	/**
	*@ignore
	*/
	static $TYPE_DATE = 5;
	/**
	*@ignore
	*/
	static $TYPE_PHOTO = 6;
	/**
	*@ignore
	*/
	static $TYPE_DATE_TIME = 7;
	/**
	*@ignore
	*/
	static $TYPE_TIME = 8;
	/**
	*@ignore
	*/
	static $TYPE_OPTION_MULTIPLE = 9;
	/**
	*@ignore
	*/
	static $TYPE_DECIMAL = 10;
	/**
	*@ignore
	*/
	static $TYPE_INFORMATIVE = 12;

	/**
	*@ignore
	*/
	static $TYPE_AUDIO = 11;
	function __toString(){
		return "$this->name";
	}

	function toForm($variable, $value){
		$valueDefecto = $variable->defaultValue;
		if($value == null){
			//	$value = $valueDefecto;
		}
		//$name = "campo_".$variable->cleanNombre();
		$name = "campo_".$variable->fieldName;
		$description = $variable->description;
		$required = $variable->required;
		$req = "";
		if($required){
			$req = "required";
		}
		switch ($this->type) {
			case FieldType::$TYPE_TEXT:
			return "<input  type='text' name='$name' id='$name' value='$value' class='form-control input-lg $req'>";
			break;
			case FieldType::$TYPE_OPTION:
			$opciones = Entity::listMe("FieldOption","active AND variable = '$variable->id' ORDER BY defaultOption DESC");
			$opciones = obj2arr($opciones, false, false, true);
			return html_select($name, $value, $opciones, "Sel.", "input-lg");
			break;
			case FieldType::$TYPE_OPTION_MULTIPLE:
			$opciones = Entity::listMe("FieldOption","active AND variable = '$variable->id' ORDER BY defaultOption DESC");
			$opciones = obj2arr($opciones);
			return html_select($name."[]", $value, $opciones, "Sel.", "multiple", "multiple");
			break;
			case FieldType::$TYPE_CHECK:
			$checked = $value == 1? "checked" : "";
			return "<input $checked type='checkbox' name='$name' id='$name' value='1' class='form-control  input-lg'>";
			break;
			case FieldType::$TYPE_NUMBER:
			return "<input  type='number' name='$name' id='$name' value='$value' class='form-control int input-lg $req'>";
			break;
			case FieldType::$TYPE_DECIMAL:
			return "<input  type='text' name='$name' id='$name' value='$value' class='form-control float input-lg $req'>";
			break;
			case FieldType::$TYPE_DATE:
			return "<input  type='text' name='$name' id='$name' value='$value' class='form-control input-lg pickadate $req date'>";
			break;
			case FieldType::$TYPE_PHOTO:
			if(!empty($value)){
				$btnStyle = "btn-primary";
			}else{
				$btnStyle = "btn-default";
			}
			$res =  "<br/> <a target='_blank' href='#' data-name='$name' class='btn btn-lg $btnStyle $req takephoto'><i class='glyphicon glyphicon-camera'></i></a>";
			$res .= "<input type='hidden' name='$name' id='$name' value='$value' data-type='photo'>";
			return $res;
			//return "<input  type='file' name='$name' id='$name' accept='image/*;capture=camera' class='form-control input-lg $req'>";
			break;
			case FieldType::$TYPE_DATE_TIME:
			return "<input  type='datetime-local' name='$name' id='$name' value='$value' class='form-control  input-lg $req'>";
			break;
			case FieldType::$TYPE_TIME:
			return "<input  type='time' name='$name' id='$name' value='$value' class='form-control  input-lg $req'>";
			break;
			default:
			return "No type";
			break;
		}
	}

	function isText(){
		return $this->type == FieldType::$TYPE_TEXTO;
	}
	function isPhoto(){
		return $this->type == FieldType::$TYPE_PHOTO;
	}
	function isAudio(){
		return $this->type == FieldType::$TYPE_AUDIO;
	}
	function isOption(){
		return $this->type == FieldType::$TYPE_OPTION;
	}
	function isInformative(){
		return $this->type == FieldType::$TYPE_INFORMATIVE;
	}
	function searchGraphType(){
		$tgtc = Entity::listMe("GrapTypeFieldType", "fieldType = '$this->id' AND active");
		$ret = array();
		foreach($tgtc as $t){
			$ret[] = $t->graphType;
		}
		return $ret;
	}

}
