<?php

namespace System\Views;

class Output {

    private static $instance;
    
    public static function I() {
        self::$instance = self::$instance ?? new Output();
        return self::$instance;
    }

    private $theme;

    private $views = array();

    // TODO : Compile this so we can have scss at runtime etc...
    private $css = array(
        array("app" => "core", "css" => "core")
    );

    public function __construct() {
        ob_start();

        $this->theme = new Theme(1);
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
        $this->Render();

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

    public function Render($headers = null, $useTemplate = true, $template = "template") {
        ob_end_clean();

        $output = "";

        foreach ($this->views as $view) {
            $output = $output . $this->theme->CompileView($this->theme->GetView($view["view"]["app"], $view["view"]["view"]), $view["data"]);
        }

        if ($useTemplate) {
            $output = $this->theme->CompileView($this->theme->GetView("core", $template), array(
                "base" => URL,
                "page" => $output,
                "title" => \System\Page::Title(),
                "name" => \System\Config::GetDynamic("site-name"),
                "css" => $this->GetCSS()
            ));
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
            $css = $css . "<link rel=\"stylesheet\" href=\"" . URL . "?theme=" . $this->theme->id . "&app=" . $style["app"] . "&style=" . $style["css"] ."\" type=\"text/css\"/>";
        }

        return $css;
    }
}