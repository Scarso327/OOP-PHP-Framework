<?php

namespace System\Structures;

abstract class Instance {
    protected static $instance;

    public static function I() {
        $class = get_called_class();
        static::$instance = static::$instance ?? new $class;
        return static::$instance;
    }
}