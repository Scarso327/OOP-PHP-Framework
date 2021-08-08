<?php

// Directories...
define("ROOT", realpath(dirname(__DIR__)));
define("WEB", realpath(ROOT . DIRECTORY_SEPARATOR . 'public'));

require_once (ROOT . DIRECTORY_SEPARATOR . "init.php");

/*
 * Temp system for getting css and java from database
 * TODO : Convert to a system that compiles them into actual files. (File System should also have CDN support for at least AWS)
*/

// CSS
if (\System\Requests\Incoming::I()->style) {
    header("Content-type: text/css");

    $app = \System\Requests\Incoming::I()->app;

    $css = (new \System\Views\Theme(\System\Requests\Incoming::I()->theme))->GetCSS(($app) ? $app : "", \System\Requests\Incoming::I()->style);

    echo ($css) ? $css : "";
    exit;
}

// JAVASCRIPT
if (\System\Requests\Incoming::I()->java) {
    header("Content-type: text/javascript");

    $java = \System\Views\Javascript::GetJavascript(\System\Requests\Incoming::I()->app, \System\Requests\Incoming::I()->java);

    echo ($java && $java->link != 1) ? $java->script : "";
    exit;
}

new System\Main();