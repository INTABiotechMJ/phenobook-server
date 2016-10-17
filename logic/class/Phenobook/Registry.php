<?php

class Registry extends Object{
	/**
	*@class User
	*/
	var $user;
	/**
	*@type INT
	*/
	var $experimental_unit_number;
	/**
	*@type DATETIME
	*/
	var $stamp;
	/**
	*@class Variable
	*/
	var $variable;
	/**
	*@type TINYINT DEFAULT 1
	* 1: current
	* 0: old (just for the record)
	*/
	var $status;
	/**
	*@type TEXT
	*/
	var $value;
	/**
	*@type VARCHAR(100)
	*/
	var $latitude;
	/**
	*@class Phenobook
	*/
	var $phenobook;
	/**
	*@type VARCHAR(100)
	*/
	var $longitude;
	/**
	*@type DATETIME
	*/
	var $localStamp;
	/**
	*@type TINYINT DEFAULT 0
	*/
	var $mobile;
	/**
	*@type TINYINT DEFAULT 0
	*/
	var $fixed;


	function __toString(){
		switch ($this->variable->fieldType->type) {
			case FieldType::$TYPE_OPTION:
			$option = Entity::search("Category","variable = '".$this->variable->id."' AND id = '$this->value'");
			$value = $option->name;
			break;
			case FieldType::$TYPE_CHECK:
			$value = $this->value?"yes":"no";
			break;
			case FieldType::$TYPE_PHOTO:
			$value = $this->calcPhoto();
			break;
			default:
			$value = $this->value;
			break;
		}
		if(empty($value)){
			return "";
		}
		return $value;
	}


	function calcPhoto(){
		$root =  __ROOT."$this->value";
		$url =  __URL."$this->value";
		if(file_exists($root)){
			$link = thumb($this->value,30,30);
			return $link;
		}else{
			return "[X]";
		}
	}

	function existsPhoto(){
		if(empty($this->value)){
			return false;
		}
		$root =  __ROOT."$this->value";
		$url =  __URL."$this->value";
		if(file_exists($root)){
			return true;
		}else{
			return false;
		}
		return false;
	}
	function calPhotoLink(){
		switch ($this->variable->fieldType->type) {
			case FieldType::$TYPE_PHOTO:
			if(empty($this->value)){
				return null;
			}
			$root =  __ROOT."$this->value";
			$url =  __URL."$this->value";
			if(file_exists($root)){
				return $url;
			}else{
				return "#";
			}
			break;
			default:
			return $this->value;
			break;
		}
	}
}
