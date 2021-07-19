<?php

namespace System\Classes;

class AdminController extends Controller {
    protected $template = array("admin", "template");

    public function __construct($system, $autoload = true) {
        $this->system = $system;

        if ($autoload) {
            $this->Init();
        }
    }

    // Default Landing Page...
    public function Home() {
        new \System\Errors\Error("404", "This Admin Controller hasn't had a Home function written for it...");
    }
}