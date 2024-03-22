<?php

namespace System\Router;

class BaseRoute
{
    public $request;
    public $next;
    public $params;

    public function __destruct()
    {
        if (is_callable($this->next)) {
            call_user_func_array($this->next, $this->params);
            exit;
        }
    }
}
