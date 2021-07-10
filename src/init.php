<?php

// Get URL...
define('URL_PROTOCOL', ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://"));
define('URL_DOMAIN', $_SERVER['HTTP_HOST']);
define('URL', URL_PROTOCOL . URL_DOMAIN . '/');

// Misc
DEFINE("SYS_APPS", array("core"));

// Autoloader!
spl_autoload_register(function ($class) {
    $dir = ROOT . $class;
    $paths = explode("\\", $class);

    // Is it a controller?
    if (array_key_exists(1, $paths)) {
        if ($paths[1] == "Controllers") {
            $dir = ROOT . "applications\\" . $paths[2] . "\\controllers\\" . $paths[3];
        }
    }

    $dir = $dir . ".php";

    if (file_exists($dir)) {
        include $dir;
    }
});

// Constants...

define("CONSTANTS", require ROOT . 'constants.php');

// Database Settings...
if (CONSTANTS["database"]) {

    foreach (CONSTANTS["database"] as $key => $setting) {
        define(strtoupper("DB_".$key), $setting);
    }
}