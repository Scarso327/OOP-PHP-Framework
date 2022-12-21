<?php

namespace Applications\Articles;

class Module extends \System\Classes\Module {
    public function Home() {
        \System\Views\Output::I()->Render();
    }
}