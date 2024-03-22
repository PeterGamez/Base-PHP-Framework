<?php

namespace System\Router;

class BaseGroup
{
    public $request;
    public $next;

    public function __destruct()
    {
        if (is_callable($this->next)) {
            call_user_func($this->next);
        }
    }
}
