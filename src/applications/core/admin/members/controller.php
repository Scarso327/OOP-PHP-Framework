<?php

namespace Applications\Core\Admin\Members;

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
        \System\Views\Output::I()->IncludeView("members_listview", "admin");
    }

    public function Roles() {
        \System\Page::SetTitle("Roles");

        \System\Views\Output::I()->IncludeView("members_roles", "admin");
    }

    public function Registration() {
        \System\Page::SetTitle("Registration");
    }
}