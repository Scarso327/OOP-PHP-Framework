<?php

namespace System\Members;

use System\Permissions\Role;
use System\Session;

class Member extends \System\Structures\DBEntity {

    const VALID_FIELDS = array( "id", "name" );

    public static function GetMemberFromToken($handler_id, $token) {
        $result = \System\DB::I()->Query("accounts.id as `id` FROM accounts INNER JOIN login_account_links WHERE accounts.id = login_account_links.account_id AND login_account_links.handler_id = :handler AND login_account_links.token = :token AND login_account_links.active = '1' AND accounts.active = '1'", array(
            ":handler" => $handler_id,
            ":token" => $token
        ), false);

        return self::CreateMember(($result) ? $result->id : null);
    }

    public static function GetMember($field, $value) {
        if (!in_array($field, self::VALID_FIELDS)) return null;
        
        $result = \System\DB::I()->Query("id FROM accounts WHERE ".$field." = :value AND active = '1' LIMIT 1", array(
            ":value" => $value
        ), false);

        return self::CreateMember(($result) ? $result->id : null);
    }

    private static function CreateMember($id) {
        $member = new Member();

        if ($id) $member->Query($id);

        return $member;
    }

    public function Query($id) {
        return $this->Load("* FROM accounts WHERE id = :id AND active = '1' LIMIT 1", array(
            ":id" => $id
        ));
    }

    public function CheckLoginToken($compare) {
        return $compare === $this->login_token;
    }

    public function LoginToken() {
        $this->login_token = substr(bin2hex(random_bytes(32)), 0, 32);

        \System\DB::I()->Update("accounts SET login_token = :token WHERE id = :id", array(
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

    public function GetRoles($as_object = false) {
        $roles = \System\DB::I()->Query("accounts_roles.id as `account_role_id`, roles.id FROM roles INNER JOIN accounts_roles WHERE accounts_roles.account_id = :id AND accounts_roles.role_id = roles.id AND accounts_roles.active = '1'", array(
            ":id" => $this->id
        ));

        if ($roles) {
            $objects = array();

            if ($as_object) {
                foreach ($roles as $role) {
                    $object = new Role($role->id);
                    $object->association_id = $role->account_role_id;

                    if ($role) {
                        $objects[$object->id] = $object;
                    }
                }
            } else {
                foreach ($roles as $role) {
                    array_push($objects, $role->id);
                }
            }

            $roles = $objects;
        }

        return $roles;
    }

    public function GiveRole($role_id) {
        return \System\DB::I()->Insert("accounts_roles (account_id, role_id) VALUES (:id, :role_id)", array(
            ":id" => $this->id,
            ":role_id" => $role_id
        ));
    }

    public function RemoveRole($role_id) {
        return \System\DB::I()->Delete("accounts_roles", "account_id = :account AND role_id = :role", array (
            ":account" => $this->id,
            ":role" => $role_id
        ));
    }

    public function PrimaryRole() {
        $role = \System\DB::I()->Query("* FROM accounts_roles WHERE id = :role_id AND account_id = :id AND active = '1'", array(
            ":role_id" => $this->primary_role,
            ":id" => $this->id
        ), false);

        if ($role) {
            $id = $role->id;

            $role = new Role($role->role_id);
            $role->association_id = $id;
        }

        return $role;
    }

    // If this is our account or is the original account, don't let certain actions take place...
    public function IsProtected() {
        return ($this->id == Session::I()->member->id) || $this->id == 1;
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

            \System\DB::I()->Update("accounts SET" . $fields. " WHERE id = " . $this->id, $params);
        }
    }
}