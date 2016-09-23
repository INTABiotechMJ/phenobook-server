<?php
class VariableGroup extends Object{
	/**
	*@type VARCHAR(200)
	*/
	var $name;

	function __toString(){
		return "$this->name";
	}

	function listVariables(){
		return Entity::listMe("Variable","active AND variableGroup = '$this->id'");
	}
}
