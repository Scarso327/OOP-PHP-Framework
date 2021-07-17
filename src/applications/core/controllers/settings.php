<?php

namespace Applications\Controllers\Core;

use Exception;
use System\Auth\Login;
use System\Forms\Button;
use System\Forms\Form;
use System\Forms\Text;
use System\Requests\Incoming;
use System\Session;

class Settings extends \System\Classes\Controller {
    private $messages = array();
    
    public function Init()
    {
        if (!\System\Session::I()->IsLoggedIn()) {
            new \System\Errors\Error("401", "This page requires you to be logged in.");
            exit;
        }

        \System\Page::SetTitle("Settings", true);

        $tab = (array_key_exists(1, $this->app->system->params)) ? $this->app->system->params[1] : "general";

        \System\Views\Output::I()->IncludeCSS("main", "settings");
        \System\Views\Output::I()->IncludeView("settings", "accounts", array(
            "token" => \System\Session::I()->CRSF(),
            "tab" => $tab,
            "page" => $this->GetTabContent(strtolower($tab)),
            "messages" => $this->messages
        ));

        $this->Finish();
    }

    private function GetTabContent($tab) {
        if (method_exists($this, "setting_".$tab)) {
            return $this->{"setting_".$tab}();
        }

        new \System\Errors\Error("404", "This setting tab doesn't exist...");
        exit;
    }

    private function setting_general() {
        // Overview...
        return "<b>Display Name: </b><p>" . Session::I()->member->name . "</p>";
    }

    private function setting_name() {
        if (Login::$type == Login::TYPE_NAME_TAKEN) {
            array_push($this->messages, "Your linked account's name is currently taken, please enter a new name...");
        } else {
            if (Incoming::I()->name_changed) {
                array_push($this->messages, "Your name has been successfully changed!");
            }
        }

        $form = new \System\Forms\Form("settings/name/");
        $form->Add(new Text("name", "New Name", ""));
        $form->SetButtons("_save", array(new Button(1, "Save")));

        if ($values = $form->Validate()) {
            $name = $values["name"];

            if (Login::IsNameTaken($name)) {
                array_push($this->messages, "The name: $name, is taken. Please try another...");
            } else {
                Session::I()->member->name = $name;
                Session::I()->member->Save();

                \System\Views\Output::I()->Redirect(URL . "settings/name", array(
                    "name_changed" => 1
                ));
            }
        } else {
            if ($form->status == Form::STATUS_INCOMPLETE_FIELDS) {
                array_push($this->messages, "You didn't complete all the require fields...");
            }
        }
        
        return (string) $form;
    }

    private function setting_intergrations() {
        $body = "<div class=\"linked-accounts\">";
        $handlers = \System\Auth\Login::GetHandlers();

        if ($handlers) {
            foreach ($handlers as $handler) {
                try {
                    $token = Session::I()->member->GetLoginHandlerToken($handler->id);

                    if ($token) {
                        $class = "System\Auth\Handlers\\" . $handler->handler;

                        $obj = new $class($handler->id);
                        $obj->Init();
                        $obj->SetToken($token->token);
                    }

                    $body = $body . "<div><div><h3>$handler->name</h3>" . (($token) ? "Linked as " . (($obj->URL()) ? "<a target=\"_blank\" href=\"" . $obj->URL() . "\">" . $obj->Name() . "</a>" : $obj->Name()) : "Unlinked") . "</div></div>";
                } catch (Exception $e) {
                    // TODO : Log this?
                }
            }
        }

        \System\Views\Output::I()->IncludeCSS("intergrations", "settings");

        return $body . "</div>";
    }
}