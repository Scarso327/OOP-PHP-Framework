<?php

namespace Applications\Core;

class Module extends \System\Classes\Module {
    const Title = "Home";
    
    public function Home()
    {
        \System\Views\Output::I()->Render();
    }
}