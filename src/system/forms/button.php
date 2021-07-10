<?php

namespace System\Forms;

class Button {
    public $name;
    private $value;
    private $text;
    
    public function __construct($value, $text) {
        $this->value = $value;
        $this->text = $text;
    }

    public function __toString() {
        return "<button type=\"submit\" name=\"$this->name\" value=\"$this->value\">$this->text</button>";
    }
}