<?php

namespace System\Structures;

class DBEntity {
    protected $data = array();

    protected function Load($query, $params) {
        if ($this->id) return false; // Already loaded...

        $info = \System\DB::I()->Query($query, $params, false);

        if (!$info) return false; // Fail to query...

        foreach ($info as $field => $value) {
            $this->data[$field] = $value;
        }

        return true;   
    }
    
    public function __get($key) {
        return (isset($this->data[$key])) ? $this->data[$key] : null;
    }

    public function __set($key, $value) {
        $this->data[$key] = $value;
    }
}