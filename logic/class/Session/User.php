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
	*@type VARCHAR(200)
	*/
	var $pass;
	/**
	*@type TINYINT DEFAULT 0
	*/
	var $passChanged;
	/**
	*@type TINYINT DEFAULT 1
	*/
	var $isAdmin;
	/**
	*@type TINYINT DEFAULT 1
	*/
	var $active;
	/**
	*@class UserGroup
	*/
	var $userGroup;
	/**
	*@ignore
	*/
	static $LANG_EN = 0;
	/**
	*@ignore
	*/
	static $LANG_ES = 1;

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
	function getUserGroups(){
		$ug =  Entity::listMe("UserUserGroup","user = '$this->id' AND active");
		$res = array();
		foreach ($ug as $u) {
			$res[] = $u->userGroup;
		}
		return $res;
	}
}
