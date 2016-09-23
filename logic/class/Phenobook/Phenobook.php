<?php
class Phenobook extends Object{
	/**
	*@type VARCHAR(200)
	*/
	var $name;
	/**
	*@type INT
	*/
	var $experimentalUnitsNumber;
	/**
	*@type VARCHAR(200)
	* Experimental Unit name
	*/
	var $eu_field_name;
	/**
	*@type TINYINT DEFAULT 1
	*/
	var $visible;
	/**
	*@type TEXT
	*/
	var $description;
	/**
	*@class UserGroup
	*/
	var $userGroup;

	function __toString(){
		return $this->name;
	}

	function selectedUsers2String(){
		$rels = Entity::listMe("PhenobookUser", "active AND phenobook = '$this->id'");
		$selectedUsers = array();
		foreach((array)$rels as $rel){
			if(!empty($rel->user)){
				$selectedUsers[] =  $rel->user;
			}
		}
		return implode(",", $selectedUsers);
	}

	function findExperimentalUnitNumber($num){
		$eu = Entity::search("ExperimentalUnit", "phenobook = '$this->id' AND active AND number = '$num'");
		return $eu;
	}

}
