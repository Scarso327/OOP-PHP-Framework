<?php

namespace System;

use System\Auth\Login;

class Session {
    protected static $session;

    public static function I() {
        if (self::$session == null) {
            self::$session = new Session();

            if (session_status() !== PHP_SESSION_ACTIVE) session_start();

            self::$session->device = Members\Device::I();
            
            if (self::$session->device->Authenticate()) {
                self::$session->member = Members\Member::GetMember("id", \System\Requests\Cookie::I()->account_id);
                \System\Requests\Cookie::I()->SetCookie("loggedIn", 1);
            } else {
                Login::Logout(false);
            }
        }

        return self::$session;
    }

    public static function HasPermission($app, $perm) {
        return \System\Permissions\Role::HasPermission(self::I()->member->GetRoles(), $app, $perm);
    }

    public $id;
    
    private $token;
    
    public $member;
    public $device;

    public function __construct() {
        $this->id = session_id();
        $this->SetToken();
    }

    public function CRSF() {
        return $this->token;
    }

    public function CheckCRSF($check) {
        return $this->CRSF() === $check;
    }

    public function IsLoggedIn() {
        return ((\System\Requests\Cookie::I()->loggedIn) ? \System\Requests\Cookie::I()->loggedIn == 1 : false);
    }

    public function SetMember($member) {
		session_regenerate_id();

		$this->member = $member;

        $this->SetToken();
	}

    private function SetToken() {
        $this->token = md5((($this->member) ? $this->member->join_date : 0) . '&' . $this->id);
    }
};