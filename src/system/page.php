<?php

namespace System;

class Page {
    private static $title;
    
    public static function SetTitle($title, $override = false) {
        self::$title = ($override) ? $title : $title . ((self::$title) ? " - " . self::$title : "");
    }

    public static function Title() {
        return self::$title;
    }
}