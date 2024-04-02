<?php

namespace System\Router;

class Respond
{
    public static function status(int $code): self
    {
        http_response_code($code);
        return new self;
    }

    public static function json(array|object $data): void
    {
        header('Content-Type: application/json;');
        echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    public static function text(string $data): void
    {
        header('Content-Type: text/plain; charset=utf-8;');
        echo $data;
    }

    public static function redirect(string $url): void
    {
        header('Location: ' . $url);
    }
}
