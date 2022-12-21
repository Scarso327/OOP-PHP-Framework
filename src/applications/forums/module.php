<?php

namespace Applications\Forums;

class Module extends \System\Classes\Module {
    public function Home() {
        \System\Views\Output::I()->IncludeView("index", "forums", array(

        ));

        \System\Views\Output::I()->css["forums_core"] = array("app" => "forums", "css" => "core");

        \System\Views\Output::I()->Render();
    }
}