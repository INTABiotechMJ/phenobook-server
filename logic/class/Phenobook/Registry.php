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
	*@type VARCHAR(100)
	*/
	var $longitude;
	/**
	*@type DATETIME
	*/
	var $localStamp;


	function __toString(){
		return $this->value;
	}


	function calcValor(){
		switch ($this->variable->fieldType->type) {
			case FieldType::$TYPE_DATE:
			//return  date("d/m/Y", strtotime($this->value));
			return  $this->value;
			case FieldType::$TYPE_PHOTO:
			if(empty($this->value)){
				return null;
			}
			$root =  __ROOT."$this->value";
			$url =  __URL."$this->value";

			if(file_exists($root)){
				$link = thumb($this->value,30,30);
				return $link;
			}else{
				return "[X]";
			}
			break;

			case FieldType::$TYPE_CHECK:
			if($this->value == "1"){
				return "Si";
			}else{
				return "No";
			}
			break;

			case FieldType::$TYPE_OPTION:
			return Entity::search("Opcion","id = '$this->value'");
			break;
			default:
			return $this->value;
			break;
		}
	}

	function existePhoto(){
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
