<?php

namespace System\Classes;

class Controller {
    public $app = null;
    protected $template = array("core", "template");

    public function __construct($app) {
        $this->app = $app;
        $this->Init();
    }

    public function Init() {
        
    }

    public function Finish() {
        call_user_func_array(array(\System\Views\Output::I(), "Render"), array(null, true, $this->template));
    }
}