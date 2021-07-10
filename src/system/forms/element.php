<?php

namespace System\Forms;

use System\Requests\Incoming;

abstract class Element {

    protected $type = "";
    public $name;
    private $title;
    private $default;

    public function __construct($name, $title, $default = null) {
        $this->name = $name;
        $this->title = $title;
        $this->default = $default;
    }

    private function SubmittedValue() {
        return Incoming::I()->{$this->name};
    }

    public function Validate() {
        return $this->SubmittedValue();
    }

    public function __toString() {
        return "<label for=\"$this->name\">$this->title:</label><input type=\"$this->type\" name=\"$this->name\" value=\"$this->default\"/>";
    }
}