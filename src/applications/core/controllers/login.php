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

        \System\Views\Output::I()->IncludeView("login", "accounts", array(
            "loginurl" => \System\Auth\Login::LoginURL(),
            "token" => \System\Session::I()->CRSF(),
            "handlers" => \System\Auth\Login::GetHandlers()
        ));

        $this->template = (\System\Admin\Admin::$mode == \System\Admin\Admin::IN_ADMIN) ? array("admin", "template") : $this->template;
        $this->Finish();
    }
}