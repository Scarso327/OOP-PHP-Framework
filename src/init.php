<?php

// Get URL...
define('URL_PROTOCOL', ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://"));
define('URL_DOMAIN', $_SERVER['HTTP_HOST']);
define('URL', URL_PROTOCOL . URL_DOMAIN . '/');

// Misc
$sys_apps = array("core");

// Autoloader!
spl_autoload_register(function ($class) {
    $dir = ROOT . DIRECTORY_SEPARATOR . $class;
    $paths = explode("\\", $class);

    // Is it a controller?
    if (array_key_exists(1, $paths)) {
        if ($paths[1] == "Controllers") {
            $dir = ROOT . DIRECTORY_SEPARATOR . "applications" . DIRECTORY_SEPARATOR . $paths[2] . DIRECTORY_SEPARATOR . "controllers" . DIRECTORY_SEPARATOR . $paths[3];
        }
    }

    $dir = strtolower(str_replace("\\", DIRECTORY_SEPARATOR, $dir)  . ".php"); // Always lower for case-sensitive file systems...

    if (file_exists($dir)) {
        include $dir;
    }
});

// Constants...

define("CONSTANTS", require (ROOT . DIRECTORY_SEPARATOR . 'constants.php'));

// Database Settings...
if (CONSTANTS["database"]) {

    foreach (CONSTANTS["database"] as $key => $setting) {
        define(strtoupper("DB_".$key), $setting);
    }
}

// Admin Dir...
if (array_key_exists("admin", CONSTANTS)) {
    array_push($sys_apps, CONSTANTS["admin"]);
}

DEFINE("SYS_APPS", $sys_apps);