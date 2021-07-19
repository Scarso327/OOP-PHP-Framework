<?php

namespace Applications\Controllers\Core;

use System\Members\Member;

class Profile extends \System\Classes\Controller {
    public function Init()
    {
        \System\Page::SetTitle("Profile", true);

        if (array_key_exists(1, $this->app->system->params)) {
            $member = Member::GetMember("id", $this->app->system->params[1]);

            if ($member->id) {
                \System\Page::SetTitle($member->name);

                \System\Views\Output::I()->IncludeView("profile", "core", array(
                    "member" => $member
                ));

                \System\Views\Output::I()->css["profile"] = array("app" => "core", "css" => "profile");

                $this->Finish();
                exit;
            }
        }

        new \System\Errors\Error("404", "No member with this id exists...");
    }
}