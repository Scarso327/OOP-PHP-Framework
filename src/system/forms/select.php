<?php

namespace System\Forms;

class Select extends Element {
    private $values;

    public function __construct($name, $title, $default = null, $values = array()) {
        $this->name = $name;
        $this->title = $title;
        $this->default = $default;
        $this->values = $values;
    }

    public function __toString() {
        $select = "<label for=\"$this->name\">$this->title:</label>\n<select name=\"$this->name\">\n";

        foreach ($this->values as $value) {
            $select .= "<option value=\"" . $value["value"] . "\"" . (($this->default == $value["value"]) ? " selected" : "") . ">" . $value["name"] . "</option>\n";
        }
        
        return $select . "</select>";
    }
}