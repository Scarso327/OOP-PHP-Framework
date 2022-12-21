<?php

namespace System\Views;

class Output {

    private static $instance;
    
    public static function I() {
        self::$instance = self::$instance ?? new Output();
        return self::$instance;
    }

    private $theme;
    public $params;

    private $views = array();

    // TODO : Compile this so we can have scss at runtime etc...
    public $css = array(
        "main" => array("app" => "core", "css" => "core"),
        "nav" => array("app" => "core", "css" => "navigation")
    );

    public function __construct() {
        ob_start();

        $this->theme = new Theme(1);

        $this->params = array(
            "base" => URL,
            "name" => \System\Config::GetDynamic("site-name"),
            "admincpdir" => CONSTANTS["admin"]
        );
    }

    public function IncludeView($file, $app = "", $data = null) {
        array_push($this->views, array (
            "view" => array("app" => $app, "view" => $file),
            "data" => $data
        ));
    }

    public function IncludeCSS($file, $app = null) {
        array_push($this->css, array("app" => ($app) ? $app : "", "css" => $file));
    }

    public function Error($contents) {
        \System\Page::SetTitle($contents["error"]);

        $this->views = array(); // Wipe includes...

        $this->IncludeView("error", "core", $contents);
        $this->Render(null, true, (\System\Admin\Admin::$mode == \System\Admin\Admin::IN_ADMIN) ? array("admin", "template") : array("core", "template"));

        exit;
    }

    public function Redirect($url, $queryStrings = null) {

        if ($queryStrings) {
            $url = $url . "?";

            foreach ($queryStrings as $query => $value) {
                $url = $url . $query . "=" . $value."&";
            }
        }
        
        Header("Location: " . $url);
    }

    public function Render($headers = null, $useTemplate = true, $template = array("core", "template")) {
        ob_end_clean();

        $output = "";

        foreach ($this->views as $view) {
            $output .= $this->theme->CompileView($this->theme->GetView($view["view"]["app"], $view["view"]["view"]), $view["data"]);
        }

        if ($useTemplate && !\System\Main::$AJAX) {
            if (!array_key_exists("page", $this->params)) {
                $this->params["page"] = $output;
            }

            $this->params["page_data"] = (object) array(
                "app" => \System\Main::$system->application,
                "controller" => \System\Main::$system->controller,
                "params" => \System\Main::$system->params
            );

            $this->params["title"] = \System\Page::Title();
            $this->params["css"] = $this->GetCSS();
            $this->params["javascript"] = $this->GetJavaScript();

            $output = $this->theme->CompileView(call_user_func_array(array($this->theme, "GetView"), $template), $this->params);
        } else {
            // If no template but we've been passed a page param, append it...
            if (array_key_exists("page", $this->params)) {
                $output .= $this->params["page"];
            }
        }

        $output = ltrim($output);

        print $output;

        @ob_end_flush();
        @flush();

        exit;
    }

    // TODO : See $this->css, GetCSS -> CompileCSS...
    public function GetCSS() {
        $css = "";

        foreach ($this->css as $style) {
            $css .= "<link rel=\"stylesheet\" href=\"" . URL . "?theme=" . $this->theme->id . "&app=" . $style["app"] . "&style=" . $style["css"] ."\" type=\"text/css\"/>";
        }

        return $css;
    }

    public function GetJavaScript() {
        $java = "";
        
        foreach (Javascript::I()->javascript as $app_level) {
            foreach ($app_level as $javascript) {
                $src = ($javascript->link == 1) ? $javascript->script : URL . "?java&app=" . $javascript->app . "&java=" . $javascript->java;
                $java .= "<script type=\"text/javascript\" src=\"$src\"></script>";
            }
        }

        return $java;
    }
}