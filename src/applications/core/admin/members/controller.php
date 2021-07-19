<?php

namespace Applications\Core\Admin\Members;

use System\Requests\Incoming;

class Controller extends \System\Classes\AdminController {
    public function Init()
    {
        \System\Page::SetTitle("Members");

        \System\Views\Output::I()->params["sidebar"] = array(
            "Members" => array(
                array("title" => "Members", "link" => ""), array("title" => "Roles", "link" => "roles")
            ),
            "Settings" => array(
                array("title" => "Registration", "link" => "registration")
            )
        );
    }

    public function Home() {
        $page = 1;
        $perPage = 5;

        // Attempted to use ?? and ? : but it didn't work...
        if (Incoming::I()->page && is_numeric(Incoming::I()->page)) {
            $page = Incoming::I()->page;
        }

        if (Incoming::I()->perPage && is_numeric(Incoming::I()->perPage)) {
            $perPage = Incoming::I()->perPage;
        }

        $start = ($page - 1) * $perPage;

        $members = \System\DB::I()->Query("`id`, `name`, `join_date` FROM accounts WHERE active = '1' ORDER BY `join_date` LIMIT " . $start . ", " . ($start + $perPage));

        \System\Views\Output::I()->IncludeView("members_listview", "admin", array(
            "members" => $members
        ));

        \System\Views\Output::I()->css["table"] = array("app" => "core", "css" => "table");
    }

    public function Roles() {
        \System\Page::SetTitle("Roles");

        \System\Views\Output::I()->IncludeView("members_roles", "admin");
    }

    public function Registration() {
        \System\Page::SetTitle("Registration");
    }
}