<?php

// Directories...
define("ROOT", dirname(__DIR__) . DIRECTORY_SEPARATOR);
define("WEB", ROOT . 'public' . DIRECTORY_SEPARATOR);

require_once "../Init.php";

// Really simple method to allow "dynamic" css...
if (\System\Requests\Incoming::I()->style) {
    header("Content-type: text/css");

    echo file_get_contents(ROOT . "themes" . DIRECTORY_SEPARATOR . \System\Requests\Incoming::I()->theme . DIRECTORY_SEPARATOR . "css" . DIRECTORY_SEPARATOR . \System\Requests\Incoming::I()->style . ".css");
    exit;
}

new System\Main();