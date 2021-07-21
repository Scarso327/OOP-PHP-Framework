<?php

namespace System\Forms;

use System\Requests\Incoming;

abstract class Element {

    protected $type = "";
    public $name;
    protected $title;
    protected $default;

    public function __construct($name, $title, $default = null) {
        $this->name = $name;
        $this->title = $title;
        $this->default = $default;
    }

    protected function SubmittedValue() {
        return Incoming::I()->{$this->name};
    }

    public function Validate() {
        return $this->SubmittedValue();
    }

    protected function Design($for, $innerHtml, $input) {
        return "<label for=\"$for\">$innerHtml:</label> $input";
    }

    public function __toString() {
        return $this->Design($this->name, $this->title, "<input type=\"$this->type\" name=\"$this->name\" value=\"$this->default\"/>");
    }
}