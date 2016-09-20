<?php
class PhenobookInfo extends Object{
	/**
	*@type VARCHAR(200)
	*/
	var $name;
	/**
	*@type VARCHAR(200)
	*/
	var $originalName;
	/**
	*@class Phenobook
	*/
	var $phenobook;

	function __toString(){
		return $this->name;
	}

}
