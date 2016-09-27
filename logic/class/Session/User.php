<?php
class User extends Object{
	/**
	*@type VARCHAR(200)
	*/
	var $name;
	/**
	*@type VARCHAR(200)
	*/
	var $lastName;
	/**
	*@type VARCHAR(200)
	*/
	var $email;
	/**
	*@class UserGroup
	*/
	var $userGroup;
	/**
	*@type VARCHAR(200)
	*/
	var $pass;
	/**
	*@type TINYINT DEFAULT 0
	*/
	var $passChanged;
	/**
	* 1- Operator
	* 2- Admin
	*@type TINYINT DEFAULT 1
	*/
	var $type;
	/**
	*@type TINYINT DEFAULT 1
	*/
	var $active;
	/**
	*@ignore
	*/
	static $LANG_EN = 0;
	/**
	*@ignore
	*/
	static $LANG_ES = 1;
	/**
	*@ignore
	*/
	static $TYPE_ADMIN = 1;
	/**
	*@ignore
	*/
	static $TYPE_OPERADOR = 2;
	/**
	*@ignore
	*/
	static $TYPE_SUPER_ADMIN = 3;

	function isAdmin(){
		return $this->type == User::$TYPE_ADMIN;
	}
	function isSuperAdmin(){
		return $this->type == User::$TYPE_SUPER_ADMIN;
	}
	function isOperador(){
		return $this->type == User::$TYPE_OPERADOR;
	}
	function calcTypeName(){
		switch ($this->type) {
			case User::$TYPE_ADMIN;
			return "Administrator";
			break;
			case User::$TYPE_OPERADOR;
			return "Operator";
			break;
			case User::$TYPE_SUPER_ADMIN;
			return "SuperAdmin";
			break;
			default:
			return "-";
			break;
		}
	}
	function __toString(){
		return "$this->name $this->lastName";
	}
	static function searchByEmail($email, $idExclude = false){
		$exclude = "";
		if($idExclude){
			$exclude = " AND id != $idExclude ";
		}
		return Entity::search("User","email = '$email' AND active $exclude");
	}
}
