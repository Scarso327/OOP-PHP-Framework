<?php

namespace Applications\Core\Admin\Members;

class Controller extends \System\Classes\AdminController {
    public function Init()
    {
        \System\Page::SetTitle("Members");

        

        $this->Finish();
    }
}