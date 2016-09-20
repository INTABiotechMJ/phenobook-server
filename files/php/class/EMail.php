<?php

class EMail extends Object{
	/**
	*@type VARCHAR(200)
	*/
	var $email_from;
	/**
	*@type VARCHAR(200)
	*/
	var $email_to;
	/**
	*@type VARCHAR(200)
	*/
	var $subject;
	/**
	*@type VARCHAR(200)
	*/
	var $body;
	/**
	*@type INT
	*/
	var $priority;
	/**
	*@type DATETIME
	*/
	var $datetimeCreated;
	/**
	*@type DATETIME
	*/
	var $datetimeSent;
	/**
	* @type INT
	* 0 : pending
	* 1 : sending
	* 2 : sent
	* 3 : error
	*/
	var $status;

	/*
	*@ignore
	*/
	private static $STATUS_PENDING = 0;
	/*
	*@ignore
	*/
	private static $STATUS_SENDING = 1;
	/*
	*@ignore
	*/
	private static $STATUS_SENT = 2;
	/*
	*@ignore
	*/
	private static $STATUS_ERROR = 3;


	function getStatusName(){
		switch ($this->status) {
			case EMail::$STATUS_PENDING:
			return 'Pendiente';
			break;
			case EMail::$STATUS_SENDING:
			return 'Enviando';
			break;
			case EMail::$STATUS_SENT:
			return 'Enviado';
			break;
			case EMail::$STATUS_ERROR:
			return 'Error';
			break;
			default:
			return "Sin estado";
			break;
		}
	}
	function searchNext(){
		return Entity::search("Email","active AND status = '0' ORDER BY priority DESC, id_email");
	}

}