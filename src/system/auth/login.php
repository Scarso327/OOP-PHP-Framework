<?php

namespace System\Auth;

use Exception;
use System\Members\Device;
use System\Session;
use System\Requests\Incoming;

class Login {

    CONST TYPE_UNKNOWN = 0;
    CONST TYPE_NAME_TAKEN = 1;

    public static $type = self::TYPE_UNKNOWN;

    public static function COOKIE_EXPIRY() { return (time() + 3600 * 24 * 90); }

    public static function GetHandlers($active = true) {
        if ($result = \System\DB::I()->Query("* FROM login_handlers WHERE active = :active", array(
            ":active" => $active ? 1 : 0
        ))) return $result;

        return false;
    }

    private $id;
    private $handler;

    public function __construct() {
        if (Session::I()->IsLoggedIn()) {
            throw new Exception("Already Logged In");
        }

        if (Incoming::I()->_loginHandler) {
            $result = \System\DB::I()->Query("id, handler FROM login_handlers WHERE id = :id", array(
                ":id" => Incoming::I()->_loginHandler
            ), false);

            if ($result) {
                $this->id = $result->id;
                $this->handler = $result->handler;
            }
        }
    }

    public function Auth() {
        if ($this->handler) {
            $class = "\System\Auth\Handlers\\" . $this->handler;

            if (class_exists($class)) {
                try {
                    $member = (new $class($this->id))->Process();

                    if ($member) {
                        $this->Success($member);
                    }
                } catch (Exception $e) {
                    throw $e;
                }
            }
        }
    }

    public static function IsNameTaken($name) {
        return \System\Members\Member::GetMember("name", $name)->id;
    }

    public static function Logout($redirect = true) {
        \System\Requests\Cookie::I()->SetCookie("account_id", null);
        \System\Requests\Cookie::I()->SetCookie("login_token", null);
        \System\Requests\Cookie::I()->SetCookie("loggedIn", null);

        Session::I()->SetMember(null);

        if ($redirect) {
            \System\Views\Output::I()->Redirect(URL);
            exit;
        }
    }

    private function Success($member) {
        Session::I()->SetMember($member);

        $login_token = $member->LoginToken();

        if (Device::I()->NewDevice($member, $login_token)) {
            \System\Requests\Cookie::I()->SetCookie("account_id", $member->id, self::COOKIE_EXPIRY());
            \System\Requests\Cookie::I()->SetCookie("login_token", $login_token, self::COOKIE_EXPIRY());
            \System\Requests\Cookie::I()->SetCookie("loggedIn", 1);
        }

        \System\Views\Output::I()->Redirect(URL);
        exit;
    }
}