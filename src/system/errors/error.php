<?php

namespace System\Errors;

class Error {
    public function __construct($error, $info = "")
    {
        \System\Views\Output::I()->Error(array(
            "error" => $error,
            "info" => $info
        ));
    }
}