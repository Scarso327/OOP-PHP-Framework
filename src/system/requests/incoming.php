<?php

namespace System\Requests;

class Incoming extends Request {
    protected static $instance;
    
    public function __construct()
    {
        $this->Incoming($_GET);
        $this->Incoming($_POST);
    }

    private function Incoming($data) {
        foreach ($data as $key => $value ) {
			$this->data[$key] = $this->Filter($value);
		}
    }
}