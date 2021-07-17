<?php

// Directories...
define("ROOT", dirname(__DIR__) . DIRECTORY_SEPARATOR);
define("WEB", ROOT . 'public' . DIRECTORY_SEPARATOR);

require_once "../Init.php";

// Really simple method to allow "dynamic" css...
if (\System\Requests\Incoming::I()->style) {
    header("Content-type: text/css");

    $app = \System\Requests\Incoming::I()->app;

    $css = (new \System\Views\Theme(\System\Requests\Incoming::I()->theme))->GetCSS(($app) ? $app : "", \System\Requests\Incoming::I()->style);

    echo ($css) ? $css : "";
    exit;
}

new System\Main();