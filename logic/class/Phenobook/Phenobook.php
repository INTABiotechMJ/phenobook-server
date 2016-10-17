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
		$pv = Entity::listMe("PhenobookVariable","active AND phenobook = '$this->id'");
		$ret = array();
		foreach ((array)$vars as $pv) {
			if($pv->variable->isInformative){
				$ret[] = $pv->variable;
			}
		}
		return $ret;
	}

	function searchVariables($fieldType = false){
		$pv = Entity::listMe("PhenobookVariable","active AND phenobook = '$this->id'");
		$ret = array();
		foreach ((array)$vars as $pv) {
			if($fieldType){
				if($pv->variable->fieldType != $fieldType){
					continue;
				}
			}
			if($pv->variable->isInformative){
				continue;
			}
			$ret[] = $pv->variable;
		}
		return $ret;
	}

}
