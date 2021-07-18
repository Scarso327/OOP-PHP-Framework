<?php

namespace System\Auth;

use System\Members\Member;

class Handler {
    public $id;
    protected $token;

    public function __construct($id) {
        $this->id = $id;
    }

    public function Init() {}
    public function Process() { return null; }

    protected function CreateAccount($name, $email) {
        $id = \System\DB::I()->Insert("accounts () VALUES ()", array(
            ":name" => $name
        ));
        
        $member = new Member();

        if ($id) {
            $member->Query($id);

            if ($name) {
                if (!Member::GetMember("name", $name)->id) {
                    $member->name = $name;
                }
            }

            $default_role = \System\Config::GetDynamic("default-role");

            if ($default_role) {
                $member->GiveRole($default_role);
            }

            $member->Save();
        }

        return $member;
    }

    public function SetToken($token) { $this->token = $token; }

    protected function GetInformation($cache = true) {
        $this->Fail();
    }

    public function Name() { return null; }
    public function URL() { return null; }

    private function Fail() {
        new \System\Errors\Error("500", "Incomplete Login Handler: " . get_class($this));
        exit;
    }
};