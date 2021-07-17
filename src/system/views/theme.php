<?php

namespace System\Views;

use System\DB;

class Theme {

    public static function GetDefault() {
        $result = DB::I()->Query("* FROM settings WHERE app = '' AND `name` = 'default-theme'", array(), false);

        if ($result) {
            $theme = self::GetTheme($result->value);

            if ($theme) return $theme;
            return self::GetThemes()[0];
        }
        
        return false;
    }

    public static function GetTheme($theme) {
        $result = DB::I()->Query("* FROM themes WHERE id = :theme_id", array(
            ":theme_id" => $theme
        ), false);

        if ($result) return $result;
        return false;
    }

    private static function GetThemes() {
        $result = DB::I()->Query("* FROM themes");

        if ($result) return $result;
        return false;
    }

    public $id;
    private $theme;

    public function __construct($theme = null)
    {
        // If we have been passed a theme id, attempt to get it...
        if ($theme) {
            $theme = $this::GetTheme($theme);
        }

        // If the theme is returning true, it"s fine. Otherwise we need to get default...
        $theme = ($theme) ? $theme : $this::GetDefault();

        $this->theme = $theme;
        $this->id = $theme->id;
    }

    public function GetView($app, $view)
    {
        return $this->GetContent($app, $view, "views");
    }

    public function GetCSS($app, $view)
    {
        return $this->GetContent($app, $view, "css");
    }

    // Really basic way of doing this but it beats using eval()...
    public function CompileView($content, $data) {

        // TODO : Add caching to the preg stuff, the only part we can't really cache is after line 90...

        $content = preg_replace_callback( "/{{(.+?)}}/", function($matches) {
			if( $matches[1] === "else" ) {
				$matches[1] .= ":";
			} elseif( \substr( $matches[1], 0, 3 ) === "end" ) {
				$matches[1] .= ";";
			} elseif( \in_array( \substr( $matches[1], 0, 4 ), array("for ", "for("))) {
				$matches[1] = "for (" . \substr( $matches[1], 3 ) . " ):";
			} else {
				foreach (array( "if", "elseif", "foreach") as $tag) {
					if(substr( $matches[1], 0, strlen( $tag )) === $tag) {
						$matches[1] = $tag ." (" . substr( $matches[1], strlen( $tag ) ) . " ):";
					}
				}
			}
	
			return "\nCONTENT;\n\n{$matches[1]}\n\$return .= <<<CONTENT\n";
		}, $content);

        $content = preg_replace("/\\\{\\\{(.+?)\\\}\\\}/", "{{\1}}", $content);

        foreach ($data as $key => $value) {
            ${$key} = $value;
        }

        return @eval(<<<PHP
        \$return = '';
        \$return .= <<<CONTENT\n
    {$content}
CONTENT;\n
        return \$return;
PHP);
    }

    private function GetContent($app, $view, $db) {
        $result = DB::I()->Query("content FROM themes_" . $db . " WHERE theme_id = :theme_id AND app = :app AND `view` = :view", array(
            ":theme_id" => $this->theme->id,
            ":app" => $app,
            ":view" => $view
        ), false);

        return ($result) ? $result->content : "";
    }
}