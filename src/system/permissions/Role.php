<?php

namespace System\Permissions;

class Role extends \System\Structures\DBEntity {

    public static function HasPermission($ids, $app, $tag) {
        if (!$ids) return false;
        
        $ids = implode("','", $ids);

        return \System\DB::I()->Query("permissions_roles.* FROM permissions_roles INNER JOIN permissions ON permissions.id = permissions_roles.permission_id WHERE permissions.app = 'core' AND permissions.tag = 'access_admin' AND permissions_roles.role_id IN ('" . $ids . "')", array(
            ":app" => $app,
            ":tag" => $tag
        ));
    }

    public static function GetRoles() {
        return \System\DB::I()->Query("* FROM roles WHERE active = '1'");
    }

    public $association_id;
    
    public function __construct($id) {
        return $this->Load("* FROM roles WHERE id = :id LIMIT 1", array(
            ":id" => $id
        ));
    }
}