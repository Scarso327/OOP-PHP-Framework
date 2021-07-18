<?php

namespace System\Admin;

class Admin extends \System\Classes\Controller {
    const NOT_IN_ADMIN = 0;
    const IN_ADMIN = 1;

    public static $mode = self::NOT_IN_ADMIN;

    // Returns if we have access to Admin CP...
    public static function HasAccess() {
        return \System\Permissions\Role::HasPermission(\System\Session::I()->member->GetRoles(), "core", "access_admin");
    }

    private $system;

    public function __construct($system) {
        $this->system = $system;
        
        $this::$mode = self::IN_ADMIN;

        if (\System\Session::I()->IsLoggedIn()) {
            \System\Page::SetTitle("Admin", true);

            \System\Views\Output::I()->params["base"] = URL . "admin";
            \System\Views\Output::I()->css["admin"] = array("app" => "admin", "css" => "core");

            if ($this::HasAccess()) {
                $this->GetApplets(); // Build Sidebar...

                $application = ($this->system->controller == null || $this->system->controller == "") ? "core" : $this->system->controller;
                $controller = ($this->system->params == null || !array_key_exists(2, $this->system->params)) ? "dashboard" : $this->system->params[2];
                $function = ($this->system->params == null || !array_key_exists(3, $this->system->params)) ? "" : $this->system->params[3];

                \System\Views\Output::I()->params["current_app"] = $application;
                \System\Views\Output::I()->params["current_controller"] = $controller;

                if (file_exists(ROOT . "/applications/" . $application . "/admin/" . $controller . "/controller.php")) {
                    $class = "Applications\\" . $application . "\\Admin\\" . $controller . "\\controller";
                    $controller = new $class($this);

                    // TODO : Call Function in controller if set...
                } else {
                    new \System\Errors\Error("404");
                }
            } else {
                new \System\Errors\Error("401", "You don't have access to the admin panel.</br>Contact the server owner if you believe this to be a mistake.");
            }
        } else {
            new \Applications\Controllers\Core\Login($this); // Create Login Page...
        }
    }

    private function GetApplets() {
        $apps = array();

        foreach (scandir(ROOT . "applications") as $file) {
            if ($file === '.' || $file === '..') continue;

            $dir = ROOT . "applications" . DIRECTORY_SEPARATOR . $file . DIRECTORY_SEPARATOR . "admin";

            if (file_exists($dir)){
                foreach (scandir($dir) as $file) {
                    if ($file === '.' || $file === '..') continue;

                    if (is_file ($dir . DIRECTORY_SEPARATOR . $file . DIRECTORY_SEPARATOR . "controller.php")) {
                        $file = explode(DIRECTORY_SEPARATOR, $dir . DIRECTORY_SEPARATOR . $file . DIRECTORY_SEPARATOR . "controller.php");
                        $controller = $file[count($file) - 2];

                        array_push($apps, array(
                            "title" => ucfirst($controller),
                            "app" => $file[count($file) - 4],
                            "controller" => $controller
                        ));
                    }
                }
            }
        }

        \System\Views\Output::I()->params["applets"] = $apps;
    }
};