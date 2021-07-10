<?php

namespace System\Members;

class Device {
    protected static $instance;

    public static function I() {
        self::$instance = new Device((\System\Requests\Cookie::I()->device_token) ? \System\Requests\Cookie::I()->device_token : null);
        return self::$instance;
    }

    private $device_token;

    public function __construct($device_token) {
        $this->device_token = $device_token;  

        if (!$this->device_token) {
            $this->device_token = substr(bin2hex(random_bytes(32)), 0, 32);

            \System\Requests\Cookie::I()->SetCookie("device_token", $this->device_token, (time() + 3600 * 24 * 365));
        }
    }

    public function Authenticate() {
        if (\System\Requests\Cookie::I()->account_id && \System\Requests\Cookie::I()->login_token) {
            return $this->VerifySession(Member::GetMember("id", \System\Requests\Cookie::I()->account_id));
        }

        return false;
    }

    public function NewDevice($member, $login_token) {
        if ($member->id) {
            $id = \System\DB::I()->Insert("login_devices (account_id, device_token, login_token) VALUES (:member, :device, :login)", array(
                ":member" => $member->id,
                ":device" => $this->device_token,
                ":login" => $login_token
            ));

            return $id != null;
        }

        return false;
    }

    private function VerifySession($member) {
        if ($member->id) {
            $login = \System\Requests\Cookie::I()->login_token;

            $result = \System\DB::I()->Query("account_id FROM login_devices WHERE account_id = :member AND device_token = :device AND login_token = :login AND active = '1' LIMIT 1", array(
                ":member" => $member->id,
                ":device" => $this->device_token,
                ":login" => $login
            ), false);

            if ($member->CheckLoginToken($login) && $result != null) {
                return $result->account_id == $member->id;
            }
        }

        return false;
    }
};