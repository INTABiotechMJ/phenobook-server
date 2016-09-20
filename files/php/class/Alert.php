<?php

class Alert {

    var $errors;
    var $hasError;
    var $alerts;

    function __construct() {
        $this->hasError = 0;
    }

    function addError($msgError) {
        $this->hasError = 1;
        $cantidad = count($this->errors);
        $this->errors[$cantidad] = $msgError;
    }

    function addAviso($msg) {
        $this->alerts[] = $msg;
    }

    function printAlert() {
        foreach ((array) $this->errors as $error) {
            echo $this->msgError("$error");
        }
        foreach ((array) $this->alerts as $aviso) {
            echo $this->msgSuccess("$aviso");
        }
    }

    function getErrors() {
        if ($this->hasError) {
            return $this->errors;
        } else {
            return array();
        }
    }
    private function msgError($msg){
        echo "<div class='alert alert-danger alert-dismissable'>$msg";
        echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
        echo "</div>";
    }
    private function msgSuccess($msg){
        echo "<div class='alert alert-success alert-dismissable'>$msg";
        echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
        echo "</div>";
    }
}
?>