<?php

namespace System\Views;

class Javascript extends \System\Structures\Instance {
    protected static $instance;

    public static function GetJavascript($app, $java) {
        return \System\DB::I()->Query("* FROM javascript WHERE app = :app AND java = :java", array(
            ":app" => $app,
            ":java" => $java
        ), false);
    }
    
    public $javascript = array();

    public function __construct()
    {
        $this->GetGlobalJavaScript();
    }

    private function GetGlobalJavaScript() {
        $result = \System\DB::I()->Query("* FROM javascript WHERE `global` = '1'");

        if ($result) {
            foreach ($result as $javascript) {
                $this->javascript[$javascript->app][$javascript->java] = $javascript;
            }
        }
    }
};