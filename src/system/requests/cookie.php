<?php

namespace System\Requests;

class Cookie extends Request {
    protected static $instance;

    public function __construct()
    {
        array_walk_recursive($_COOKIE, array($this, 'Filter'));
        $this->data = $_COOKIE;
    }

    public function SetCookie($name, $value, $expiry = null, $path = "/") {
        if (setcookie($name, $value, (($expiry) ? $expiry : 0), $path, URL_DOMAIN, URL_PROTOCOL == "https://", false) === true)
		{
			$this->data[$name] = $value;
			return true;
		}

		return false;
    }
}