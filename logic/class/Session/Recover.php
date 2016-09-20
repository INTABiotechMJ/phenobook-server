<?php
class Recover{
	/**
	*@pk
	*/
	var $id_recover;
	/**
	*@class User
	*/
	var $user;

	/**
	*@type DATETIME
	*/
	var $datetime;
	/**
	*
	*/
	var $hash;
	/**
	*0: pendind
	*1: used
	*/
	var $status;
	/**
	*
	*/
	var $active;

	static function loadValid($hash){
		$SQL = " hash = '$hash' AND active AND status != '1' ";
		$SQL .= " AND DATE_SUB(NOW(), Interval 1 Day) <= DATE(datetime)";
		return Entity::search("Recover",$SQL);
	}


}
?>