<?php

namespace System;

use System\Auth\Login;
use System\Views\Output;

class Main {
    public static $system;

    public $application;
    public $controller;
    public $params = array();
    public static $page;
    public static $AJAX = false;

    public function __construct()
    {
        $this::$system = $this;
        $this::$page = new Page();

        $this::$AJAX = (
            isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'
        );

        // Parse the URL for useful information...

        $this->application = null;
        $this->controller = null;

        if (isset($_GET['url'])) {
            $url = trim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);
            $this->application = isset($url[0]) ? $url[0] : $this->application;
            $this->controller = isset($url[1]) ? $url[1] : $this->controller;
            $this->params = array_values($url);
            unset($this->params[0]);
        }

        // If we're not submitting the login handler, start session for authentication now...
        if (!Requests\Incoming::I()->_loginHandler) {
            if (Session::I()->IsLoggedIn()) {
                // If our name is null then we need to set one...
                if (!Session::I()->member->name) {
                    if ($this->application != "settings" || $this->controller != "name") {
                        Output::I()->Redirect(URL."settings/name", array(
                            "name-taken" => 1
                        ));
                    } else {
                        Login::$type = Login::TYPE_NAME_TAKEN;
                    }
                }
            }
        }

        if (in_array(strtolower($this->application), SYS_APPS)) {
            // Admin directory, the constant allows it to be semi-hidden.
            if ($this->application == CONSTANTS["admin"]) {
                new Admin\Admin($this);
            } else {
                new Errors\Error("404");
            }
        } else {
            // Load default application if nothing provided...
            if ($this->application == null) {
                $this->application = Config::GetDynamic("default-app", "");;
            }

            // If the application var is a controller within the Core application, load that above all else...
            if (class_exists("Applications\Controllers\Core\\" . $this->application, true)) {
                $this->LoadApplication("Core")->LoadController($this->application, true);
            } else {
                unset($this->params[1]);

                if (file_exists(ROOT . DIRECTORY_SEPARATOR . "/applications/" . $this->application)) {
                    $this->LoadApplication($this->application)->LoadController($this->controller, true);
                } else {
                    new Errors\Error("404");
                }
            }
        }
    }

    private function LoadApplication($application) {
        $class = "Applications\\" . $application . "\\Module";
        return new $class($this);
    }
}