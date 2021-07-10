<?php

namespace System\Views;

class Output {

    private static $instance;
    
    public static function I() {
        self::$instance = self::$instance ?? new Output();
        return self::$instance;
    }

    public $themes = array();
    private $theme = "default"; // TODO : Add ability for variants on themes so you don't need 2 themes just for a dark mode...

    private $files = array();

    // TODO : Compile this so we can have scss at runtime etc...
    private $css = array(
        "core"
    );

    public function __construct() {
        ob_start();

        // Get all themes...
        if (!is_dir(ROOT . "themes")) {
            new \System\Errors\Error("500");
            exit;
        }

        // Loops through the directory /themes and if it has a theme.php file we add it to our array...
        // TODO : Link to database so we can semi-ignore files and maybe even allow themes to "share" html / css...
        foreach (scandir(ROOT . "themes") as $file) {
            if ($file === '.' || $file === '..') continue;

            $dir = ROOT . "themes" . DIRECTORY_SEPARATOR . $file . DIRECTORY_SEPARATOR;

            if (is_file($dir . "theme.php")){
                $theme = require_once $dir . "theme.php";
                $theme["dir"] = $dir;
                
                $this->themes[strtolower($theme["name"])] = $theme;
            }
        }
    }

    public function IncludeFile($file, $app = "", $data = null) {
        array_push($this->files, array (
            "file" => "html" . DIRECTORY_SEPARATOR . $app . DIRECTORY_SEPARATOR . $file,
            "data" => $data
        ));
    }

    public function IncludeCSS($file, $app = null) {
        array_push($this->css, (($app) ? $app . DIRECTORY_SEPARATOR : "") . $file);
    }

    public function Error($contents) {
        \System\Page::SetTitle($contents["error"]);

        ob_end_clean();

        $this->files = array(); // Wipe includes...

        $this->IncludeFile("error.php", "", $contents);
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

    public function Render($headers = null, $useTemplate = true, $template = "template.php") {
        $css = $this->GetCSS();

        foreach ($this->files as $file) {
            if ($file["data"]) {
                foreach ($file["data"] as $key => $value) {
                    $this->{$key} = $value;
                }
            }

            include $this->ThemeDirectory() . $file["file"];
        }

        if ($useTemplate) {
            $content = ob_get_clean(); // We're using a template so just yeet the content from the buffer and into the template...
            include_once $this->ThemeDirectory() . "html" . DIRECTORY_SEPARATOR . $template;
        }

        @ob_end_flush();
        @flush();

        exit;
    }

    // TODO : See $this->css, GetCSS -> CompileCSS...
    public function GetCSS() {
        $css = "";

        foreach ($this->css as $file) {
            $css = $css . "<link rel=\"stylesheet\" href=\"" . URL . "?theme=$this->theme&style=$file\" type=\"text/css\"/>";
        }

        return $css;
    }

    private function ThemeDirectory() {
        return $this->themes[$this->theme]["dir"];
    }
}