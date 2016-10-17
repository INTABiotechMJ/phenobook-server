<?php
class Phenobook extends Object{
	/**
	*@type VARCHAR(200)
	*/
	var $name;
	/**
	*@type INT
	*/
	var $experimental_units_number;
	/**
	*@type VARCHAR(300)
	*/
	var $experimental_unit_name;
	/**
	*@type TINYINT DEFAULT 1
	*/
	var $visible;
	/**
	*@type DATETIME
	*/
	var $stamp;
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

	function searchInformativeVariables(){
		global $__user;
		return Entity::listMe("Variable","active AND isInformative AND id IN (SELECT variable FROM PhenobookVariable WHERE active AND phenobook = '$this->id') AND userGroup = '".$__user->userGroup->id."'");
	}

	function searchNonInformativeVariables($fieldType = false){
		global $__user;
		if($fieldType){
			return Entity::listMe("Variable","active AND fieldType = '$fieldType' AND NOT isInformative AND id IN (SELECT variable FROM PhenobookVariable WHERE active AND phenobook = '$this->id') AND userGroup = '".$__user->userGroup->id."'");
		}
		return Entity::listMe("Variable","active AND NOT isInformative AND id IN (SELECT variable FROM PhenobookVariable WHERE active AND phenobook = '$this->id') AND userGroup = '".$__user->userGroup->id."'");
	}
	function searchVariables($fieldType = false){
		global $__user;
		if($fieldType){
			return Entity::listMe("Variable","active AND fieldType = '$fieldType' AND id IN (SELECT variable FROM PhenobookVariable WHERE active AND phenobook = '$this->id') AND userGroup = '".$__user->userGroup->id."' ORDER BY isInformative DESC");
		}
		return Entity::listMe("Variable","active AND id IN (SELECT variable FROM PhenobookVariable WHERE active AND phenobook = '$this->id') AND userGroup = '".$__user->userGroup->id."' ORDER BY isInformative DESC");
	}

}
