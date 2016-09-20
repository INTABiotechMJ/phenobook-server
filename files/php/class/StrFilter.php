<?php

class StrFilter {

    var $filters;

    function __construct() {
        $this->filters = array();
    }

    function add($msg) {
       $this->filters[] = $msg;
   }


   function __toString() {
    if(empty($this->filters)){
        return "Sin filtros";
    }
        return implode(" | ", $this->filters);
    }
}
