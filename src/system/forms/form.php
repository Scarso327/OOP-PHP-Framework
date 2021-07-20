<?php

namespace System\Forms;

use System\Requests\Incoming;

class Form {

    const STATUS_UNSUBMITTED = 0;
    const STATUS_SUBMITTED = 1;
    const STATUS_INCOMPLETE_FIELDS = 2;

    public $status = self::STATUS_UNSUBMITTED;

    // General Info
    private $action;
    private $submitName;
    private $method;

    // Elements / Fields...
    private $data = array(); // Add as "hidden elements"...
    private $elements = array();
    private $buttons = array();

    public function __construct($action = "", $hidden = array(), $method = "POST") {
        $this->action = $action;
        $this->method = $method;

        // Add CRSF Token...
        if (!array_key_exists("token", $hidden)) {
            $hidden["token"] = \System\Session::I()->CRSF();
        }

        $this->data = $hidden;
    }

    public function Add($element) {
        if (array_key_exists($element->name, $this->elements)) return false;

        array_push($this->elements, $element);
        return true;
    }

    public function SetButtons($name, $buttons) {
        $this->submitName = $name;

        foreach ($buttons as $button) {
            $button->name = $this->submitName;
            array_push($this->buttons, $button);
        }
    }

    public function Validate() {
        $values = null;

        if (Incoming::I()->{$this->submitName}) {
            $submitType = Incoming::I()->{$this->submitName};

            if (count($this->elements) > 0) {
                // Validate the inputs...
                foreach ($this->elements as $element) {
                    if ($val = $element->Validate()) {
                        $values[$element->name] = $val;
                    } else {
                        $this->status = $this::STATUS_INCOMPLETE_FIELDS;
                        return null; // Force Null...
                    }
                }
            } else {
                $values = true;
            }

            $this->status = $this::STATUS_SUBMITTED;
        }

        return $values;
    }

    public function __toString() {
        $form = "<form accept-charset=\"utf8\" method=\"" . $this->method . "\" action=\"" . URL . $this->action ."\">";
        
        foreach ($this->data as $name => $value) {
            $form = $form . "<input type=\"hidden\" name=\"$name\" value=\"$value\"/>";
        }

        foreach ($this->elements as $element) {
            $form = $form . (string) $element;
        }

        foreach ($this->buttons as $button) {
            $form = $form . (string) $button;
        }

        $form = $form . "</form>";
        return $form;
    }
}