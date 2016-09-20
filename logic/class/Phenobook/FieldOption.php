<?php
class FieldOption extends Object{


	/**
	*@type VARCHAR(100)
	*/
	var $name;

	/**
	*@class Variable
	*/
	var $variable;

	/**
	*@TYPE TINYINT DEFAULT 0
	*/
	var $defaultValue;

	function __toString(){
		return "$this->name";
	}

}
