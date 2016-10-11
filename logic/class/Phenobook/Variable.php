<?php
class Variable extends Object{
	/**
	*@class VariableGroup
	*/
	var $variableGroup;
	/**
	*@type VARCHAR(200)
	*/
	var $name;
	/**
	*@type VARCHAR(300)
	*/
	var $defaultValue;
	/**
	*@type TINYINT DEFAULT 0
	*/
	var $required;
	/**
	*@type VARCHAR(300)
	*/
	var $description;
	/**
	*@class FieldType
	*/
	var $fieldType;

	function formatValue(){
		if($this->fieldType->isCheck()){
			if($this->value){
				return true;
			}else{
				return false;
			}
		}
		return $this->value;
	}
	function __toString(){
		if(!empty($this->originalName)){
			return "$this->originalName";
		}else{
			return "$this->name";
		}
	}
	function toForm($value = null){
		return $this->fieldType->toForm($this, $value);
	}
	function cleanName(){
		$name = $this->name;
		$name = str_replace(" ", "_", $name);
		$name = str_replace("á", "a", $name);
		$name = str_replace("é", "e", $name);
		$name = str_replace("í", "i", $name);
		$name = str_replace("ó", "o", $name);
		$name = str_replace("ú", "u", $name);
		return $name;
	}
	function getOptions(){
		return Entity::listMe("FieldOption","active AND Variable = '$this->id' ORDER BY id DESC");
	}

}
