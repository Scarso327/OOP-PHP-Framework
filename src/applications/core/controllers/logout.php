<?php

namespace Applications\Controllers\Core;

use Exception;
use System\Auth\Login as AuthLogin;

class Logout extends \System\Classes\Controller {
    public function Init()
    {
        AuthLogin::Logout();
    }
}