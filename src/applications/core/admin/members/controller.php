<?php

namespace Applications\Core\Admin\Members;

use System\Requests\Incoming;
use System\Forms\Button;
use System\Forms\Checkbox;
use System\Forms\Form;
use System\Forms\Select;
use System\Forms\Label;

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
        if (array_key_exists(4, $this->system->system->params)) {
            $member = \System\Members\Member::GetMember("id", $this->system->system->params[4]);

            if ($member->id) {
                \System\Page::SetTitle($member->name);

                $action = $this->system->system->params[5];

                if ($action) {
                    switch (strtolower($action)) {
                        case "roles":
                            $primaryRoles = array(
                                array("value" => "-1", "name" => "None")
                            );

                            $member_roles = $member->GetRoles(true);

                            foreach ($member_roles as $role) {
                                if ($role) {
                                    array_push($primaryRoles, array(
                                        "value" => $role->association_id,
                                        "name" => $role->name
                                    ));
                                }
                            }

                            $primaryRoleId = $member->PrimaryRole()->association_id;

                            $form = new Form("admin/core/members/home/$member->id/roles?action=" . Incoming::I()->action);
                            
                            switch (Incoming::I()->action) {
                                case "primary":
                                    $form->Add(new Select("primary_role", "Primary Role", $primaryRoleId, $primaryRoles));
                                    break;
                                case "roles":
                                    $roles = \System\Permissions\Role::GetRoles();

                                    $form->Add(new Label("secondary_roles[]", "Roles"));

                                    foreach ($roles as $role) {
                                        $form->Add(new Checkbox("secondary_roles[]", $role->name, $role->id, isset($member_roles[$role->id])));
                                    }
                                    break;
                                default:
                                    new \System\Errors\Error("404", "This form requires an action to be provided...");
                                    break;
                            }

                            $form->SetButtons("_save", array(new Button(1, "Save")));

                            if ($values = $form->Validate()) {
                                switch (Incoming::I()->action) {
                                    case "primary":
                                        $pRole = $values["primary_role"];

                                        if ($pRole != $primaryRoleId) {
                                            $member->primary_role = ($pRole == "-1" || !isset($member_roles[$role->id])) ? null : $pRole;
                                            $member->Save();
                                        }
                                        break;
                                    case "roles":
                                        $secondary_roles = $values["secondary_roles[]"];

                                        foreach ($roles as $role) {
                                            if (in_array($role->id, $secondary_roles)) {
                                                // The role has been "checked", if we don't have the role we'll add it now...
                                                if (!isset($member_roles[$role->id])) {
                                                    $role->association_id = $member->GiveRole($role->id);
                                                    $member_roles[$role->id] = $role;
                                                }
                                            } else {
                                                // This role has been "unchecked", if we currently have it. Remove it.
                                                if (isset($member_roles[$role->id])) {
                                                    $member->RemoveRole($role->id);
                                                    unset($member_roles[$role->id]);
                                                }
                                            }
                                        }
                                        break;
                                }

                                \System\Views\Output::I()->Redirect(URL . "/admin/core/members/home/$member->id");
                            }

                            \System\Views\Output::I()->params["page"] = (string) $form;
                            break;
                        default:
                            new \System\Errors\Error("404", "This action doesn't exist for this user... <a href=\"/admin/core/members/home/$member->id\">Return</a>");
                            break;
                    }
                } else {
                    $identifiers = array();

                    foreach (\System\Auth\Login::GetHandlers() as $handler) {
                        $token = $member->GetLoginHandlerToken($handler->id);

                        if ($token) {
                            array_push($identifiers, $token->token);
                        }
                    }

                    \System\Views\Output::I()->IncludeView("profile", "admin", array(
                        "member" => $member,
                        "identifiers" => $identifiers
                    ));

                    \System\Views\Output::I()->css["columns"] = array("app" => "core", "css" => "columns");
                }

                \System\Views\Output::I()->css["members"] = array("app" => "core", "css" => "member");
                \System\Views\Output::I()->css["lists"] = array("app" => "core", "css" => "lists");
                \System\Views\Output::I()->css["forms"] = array("app" => "core", "css" => "forms");
            }
        } else {
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

            $members = \System\DB::I()->Query("`id` FROM accounts WHERE active = '1' ORDER BY `join_date` LIMIT " . $start . ", " . ($start + $perPage));

            foreach ($members as $key => $member) {
                $members[$key] = \System\Members\Member::GetMember("id", $member->id);
            }

            \System\Views\Output::I()->IncludeView("members_listview", "admin", array(
                "members" => $members
            ));

            \System\Views\Output::I()->css["table"] = array("app" => "core", "css" => "table");
        }
    }

    public function Roles() {
        \System\Page::SetTitle("Roles");

        \System\Views\Output::I()->IncludeView("members_roles", "admin");
    }

    public function Registration() {
        \System\Page::SetTitle("Registration");
    }
}