<?php

namespace System\Forms;

class Summernote extends Element {
    public function __toString() {
        return "<textarea data-summernote data-summernote-contents=\"$this->default\" name=\"$this->name\"></textarea>";
    }
}