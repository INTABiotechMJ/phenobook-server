<?php
class PhenobookInfoValue extends Object{
	/**
	*@type VARCHAR(200)
	*/
	var $value;
	/**
	*@class Phenobook
	*/
	var $phenobook;

	function __toString(){
		if(isset($this->value)){
			return $this->value;
		}
		return "";
	}

}
