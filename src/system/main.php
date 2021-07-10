<?php

namespace System;

use System\Auth\Login;
use System\Views\Output;

class Main {

    public $params = array();
    public static $page;

    public function __construct()
    {
        $this::$page = new Page();

        // Parse the URL for useful information...

        $application = null;
        $controller = null;

        if (isset($_GET['url'])) {
            $url = trim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);
            $application = isset($url[0]) ? $url[0] : $application;
            $controller = isset($url[1]) ? $url[1] : $controller;
            $this->params = array_values($url);
            unset($this->params[0]);
        }

        // If we're not submitting the login handler, start session for authentication now...
        if (!Requests\Incoming::I()->_loginHandler) {
            if (Session::I()->IsLoggedIn()) {
                // If our name is null then we need to set one...
                if (!Session::I()->member->name) {
                    if ($application != "settings" || $controller != "name") {
                        Output::I()->Redirect(URL."settings/name", array(
                            "name-taken" => 1
                        ));
                    } else {
                        Login::$type = Login::TYPE_NAME_TAKEN;
                    }
                }
            }
        }

        if (in_array(strtolower($application), SYS_APPS)) {
            new Errors\Error("404");
        } else {
            // Load default application if nothing provided...
            if ($application == null) {
                $application = Config::GetDynamic("default-app", "");;
            }

            // If the application var is a controller within the Core application, load that above all else...
            if (class_exists("Applications\Controllers\Core\\" . $application, true)) {
                $this->LoadApplication("Core")->LoadController($application, true);
            } else {
                unset($this->params[1]);

                if (file_exists(ROOT . "/applications/" . $application)) {
                    $this->LoadApplication($application)->LoadController($controller, true);
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