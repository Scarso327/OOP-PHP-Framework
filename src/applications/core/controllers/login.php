<?php

namespace Applications\Controllers\Core;

use Exception;
use System\Auth\Login as AuthLogin;

class Login extends \System\Classes\Controller {
    public function Init()
    {
        \System\Page::SetTitle("Login", true);

        try {
            (new AuthLogin())->Auth();
        } catch (Exception $e) {
            new \System\Errors\Error("500", $e->getMessage());
            exit;
        }

        \System\Views\Output::I()->IncludeFile("login.php", "accounts", array(
            "token" => \System\Session::I()->CRSF()
        ));

        $this->Finish();
    }
}