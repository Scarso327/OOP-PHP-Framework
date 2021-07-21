<?php

namespace System\Forms;

use System\Requests\Incoming;

class Checkbox extends Element {
    protected $type = "checkbox";

    private $checked;

    public function __construct($name, $title, $default = null, $checked = false) {
        $this->name = $name;
        $this->title = $title;
        $this->default = $default;
        $this->checked = $checked;
    }

    protected function SubmittedValue() {
        return Incoming::I()->{str_replace("[]", "", $this->name)};
    }

    protected function Design($for, $innerHtml, $input) {
        if ($this->checked) {
            $input = str_replace("\"/>", "\" checked/>", $input);
        }

        return "$input <label for=\"$for\" class=\"inline\">$innerHtml</label></br>";
    }
}