<?php

namespace System\Requests;

class Request extends \System\Structures\Instance {
    protected $data = array();

    protected function Filter(&$value) {
        if (is_string($value)) {
            $value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
        } else if (is_array($value) || is_object($value)) {
            array_walk_recursive($value, array($this, 'Filter'));
        }

        return $value;
    }

    public function __get($key)
	{
		return (isset($this->data[$key])) ? $this->data[$key] : null;
    }
}