<?php

namespace System\Forms;

class Label extends Element {

    public $name;
    protected $title;

    public function __construct($name, $title) {
        $this->name = $name;
        $this->title = $title;
    }
    
    public function Validate() {
        return true;
    }

    public function __toString() {
        return "<label for=\"$this->name\">$this->title:</label>";
    }
}