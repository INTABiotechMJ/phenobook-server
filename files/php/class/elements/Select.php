<?php 

class Select{

    var $options;
    var $name;
    var $id;
    var $class;
    var $showName;
    var $postSelected;
    var $help;
    var $moreOptions;

    //$out = "<div class='form-group'>";
    //$out .= "<label class='col-md-4 control-label' for='name'>$showName</label> ";
    //$out .= "<div class='col-md-6'>";
    //$out .= "</div>";
    //$out .= "</div>";


    function __toString() {
        $out = "";
        if (isset($this->id)) {
            $id = "id='$this->id'";
        } else {
            $id = "id='$this->name'";
        }
        $out .= "<select name='$this->name' $id class='form-control $this->class' $this->moreOptions >";
        if (empty($this->postSelected)) {
            $selected = "selected";
        } else {
            $selected = "";
        }
        if ($this->showName != null) {
            $out .= "<option value='' $selected>$this->showName</option>";
        }
        foreach ((array) $this->options as $key => $value) {
            if (is_array($this->postSelected)) {
                if (array_key_exists($key, $this->postSelected)) {
                    $selected = "selected";
                } else {
                    $selected = "";
                }
            } else {
                if ($this->postSelected == "$key") {
                    $selected = "selected";
                } else {
                    $selected = "";
                }
            }
            $out .= "<option value='$key' $selected>$value</option>";
        }
        $out .= "</select>";
        $out .= "<span class='help-block'>$this->help</span>";
        return $out;
    }
}