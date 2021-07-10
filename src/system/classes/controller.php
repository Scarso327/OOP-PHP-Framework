<?php

namespace System\Classes;

class Controller {
    public $app = null;

    public function __construct($app) {
        $this->app = $app;
        $this->Init();
    }

    public function Init() {
        
    }

    public function Finish() {
        \System\Views\Output::I()->Render();
    }
}