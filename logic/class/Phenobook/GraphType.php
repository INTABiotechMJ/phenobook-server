<?php
class GraphType extends Object{
	/**
	*@type VARCHAR(100)
	*/
	var $name;
	/**
	*@type VARCHAR(100)
	*/
	var $description;
	/**
	*@type VARCHAR(100)
	*/
	var $type;
	/**
	*@ignore
	*/
	static $TYPE_BAR = 1;
	/**
	*@ignore
	*/
	static $TYPE_CAKE = 2;
	/**
	*@ignore
	*/
	static $TYPE_TIMELINE = 3;
	function __toString(){
		return $this->nombre;
	}

}
