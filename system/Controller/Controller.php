<?php

namespace System\Controller;

use Exception;

class Controller
{
    public static function __callStatic(string $method, array $arguments)
    {
        $class = get_called_class();

        throw new Exception("Method {$method} does not exist in {$class}");
    }
}
