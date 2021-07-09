<?php

namespace kirillemko\yci\models\request;

class RequestError
{
    public $field;

    public $error;

    public function __construct($field, $error)
    {
        $this->field = $field;
        $this->error = $error;
    }
}