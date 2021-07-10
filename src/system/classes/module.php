<?php

namespace System\Classes;

class Module {

    CONST Title = null;

    public $system = null;
    private $controller = null;

    public function __construct($system) {
        $this->system = $system;
        $this->Init();
    }

    public function Init() {
        \System\Page::SetTitle(($this::Title) ? $this::Title : explode("\\", get_class($this))[1]);
    }

    public function Home() {
        new \System\Errors\Error("404");
    }

    public function LoadController($controller, $error = false) {
        if ($controller == null) {
            $this->Home();
            return;
        }

        $class = "Applications\\Controllers\\" . explode("\\", get_class($this))[1] . "\\" . $controller;

        if (class_exists($class , true)) {
            $this->controller = new $class($this);

            return true;
        } else {
            if ($error) {
                new \System\Errors\Error("404");
            }

            return false;
        }
    }
}