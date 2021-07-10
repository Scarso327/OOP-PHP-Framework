<?php

namespace System;

class Config {
    // Returns the value from a setting within the database...
    public static function GetDynamic($name, $app = "core") {
        $result = DB::I()->Query("* FROM settings WHERE app = :app AND `name` = :setting", array(
            ":app" => $app,
            ":setting" => $name
        ), false);

        if ($result) return $result->value;
        return false;
    }
}