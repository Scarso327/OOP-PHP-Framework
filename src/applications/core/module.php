<?php

namespace Applications\Core;

class Module extends \System\Classes\Module {
    const Title = "Home";
    
    public function Home()
    {
        if (class_exists("\Applications\Articles\System\Article")) {
            $featured = \Applications\Articles\System\Article::GetFeatured();
        } else {
            // TODO : Maybe add a log warning for missing application?
        }

        \System\Views\Output::I()->IncludeView("index", "core", array(
            "featuredBanner" => $featured
        ));

        \System\Views\Output::I()->css["core_index"] = array("app" => "core", "css" => "index");

        \System\Views\Output::I()->Render();
    }
}