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
	function searchNonInformativeVariables($fieldType = false){
		$variables = $this->searchVariables();
		$ret = array();
		foreach((array)$variables as $v){
			if($v->isInformative){
				continue;
			}
			if($fieldType){
				if($fieldType != $v->fieldType->type){
					continue;
				}
			}
			$ret[] = $v;
		}
		return $ret;
	}
	function searchInformativeVariables(){
		$variables = $this->searchVariables();
		$ret = array();
		foreach((array)$variables as $v){
			if(!$v->isInformative){
				continue;
			}
			$res[] = $v;
		}
		return $ret;
	}

	function searchVariables($fieldType = false){
		global $__user;
		$variables = array();
		$pvs = Entity::listMe("PhenobookVariable","active AND phenobook = '$this->id'");
		foreach((array)$pvs as $pv){
			if($fieldType){
				if($pv->variable->fieldType->id != $fieldType->id){
					continue;
				}
			}
			$variables[] = $pv->variable;
		}
		return $variables;
	}
}
