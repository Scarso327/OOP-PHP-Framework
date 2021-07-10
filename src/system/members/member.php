<?php

namespace System\Members;

class Member {

    const VALID_FIELDS = array( "id", "name" );

    public static function GetMemberFromToken($handler_id, $token) {
        $result = \System\DB::I()->Query("login_accounts.id as `id` FROM login_accounts INNER JOIN login_account_links WHERE login_accounts.id = login_account_links.account_id AND login_account_links.handler_id = :handler AND login_account_links.token = :token AND login_account_links.active = '1' AND login_accounts.active = '1'", array(
            ":handler" => $handler_id,
            ":token" => $token
        ), false);

        return self::CreateMember(($result) ? $result->id : null);
    }

    public static function GetMember($field, $value) {
        if (!in_array($field, self::VALID_FIELDS)) return null;
        
        $result = \System\DB::I()->Query("id FROM login_accounts WHERE ".$field." = :value AND active = '1' LIMIT 1", array(
            ":value" => $value
        ), false);

        return self::CreateMember(($result) ? $result->id : null);
    }

    private static function CreateMember($id) {
        $member = new Member();

        if ($id) $member->Load($id);

        return $member;
    }

    protected $data = array();

    public function Load($id) {
        if ($this->id) return false; // Already loaded...

        $info = \System\DB::I()->Query("* FROM login_accounts WHERE id = :id AND active = '1' LIMIT 1", array(
            ":id" => $id
        ), false);

        if (!$info) return false; // Fail to query...

        foreach ($info as $field => $value) {
            $this->data[$field] = $value;
        }

        return true;
    }

    public function __get($key) {
        return (isset($this->data[$key])) ? $this->data[$key] : null;
    }

    public function __set($key, $value) {
        $this->data[$key] = $value;
    }

    public function CheckLoginToken($compare) {
        return $compare === $this->login_token;
    }

    public function LoginToken() {
        $this->login_token = substr(bin2hex(random_bytes(32)), 0, 32);

        \System\DB::I()->Update("login_accounts SET login_token = :token WHERE id = :id", array(
            ":id" => $this->id,
            ":token" => $this->login_token
        ));

        return $this->login_token;
    }

    public function GetLoginHandlerToken($handler_id) {
        return \System\DB::I()->Query("token FROM login_account_links WHERE handler_id = :handler_id AND account_id = :id AND active = '1' LIMIT 1", array(
            ":id" => $this->id,
            ":handler_id" => $handler_id
        ), false);
    }

    public function Save() {
        $total = count($this->data);

        if ($total > 0) {
            $fields = "";
            $params = array();

            $i = 0;

            foreach ($this->data as $field => $value) {
                $i++;
                $fields = $fields . " " . $field . " = :". $field . (($i == $total) ? "" : ",");
                $params[":".$field] = $value;
            }

            \System\DB::I()->Update("login_accounts SET" . $fields. " WHERE id = " . $this->id, $params);
        }
    }
}