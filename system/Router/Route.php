<?php

namespace System\Router;

class Route
{
    public static function get(string $url, callable $callback)
    {
        self::route(['GET'], $url, $callback);
    }

    public static function head(string $url, callable $callback)
    {
        self::route(['HEAD'], $url, $callback);
    }

    public static function post(string $url, callable $callback)
    {
        self::route(['POST'], $url, $callback);
    }

    public static function put(string $url, callable $callback)
    {
        self::route(['PUT'], $url, $callback);
    }

    public static function delete(string $url, callable $callback)
    {
        self::route(['DELETE'], $url, $callback);
    }

    public static function connect(string $url, callable $callback)
    {
        self::route(['CONNECT'], $url, $callback);
    }

    public static function options(string $url, callable $callback)
    {
        self::route(['OPTIONS'], $url, $callback);
    }

    public static function trace(string $url, callable $callback)
    {
        self::route(['TRACE'], $url, $callback);
    }

    public static function patch(string $url, callable $callback)
    {
        self::route(['PATCH'], $url, $callback);
    }

    public static function match(array $methods, string $url, callable $callback)
    {
        self::route($methods, $url, $callback);
    }

    public static function any(string $url, callable $callback)
    {
        self::route(['GET', 'HEAD', 'POST', 'PUT', 'DELETE', 'CONNECT', 'OPTIONS', 'TRACE', 'PATCH'], $url, $callback);
    }

    private static function route(array $methods, string $url, callable $callback)
    {
        $method = $_SERVER['REQUEST_METHOD'];

        $request_uri = $_SERVER['REQUEST_URI'];
        $request_uri = preg_replace('/\?.*$/', '', $request_uri);
        if ($request_uri == '/') {
            $request_uri = '';
        }

        $url = preg_replace('/\/$/', '', $url);

        if (in_array($method, $methods)) {
            // if url are end with *
            if (str_ends_with($url, '*')) {
                $pattern = preg_replace('/\//', '\/', $url);
                // replace * with {patten}
                $pattern = preg_replace('/\*$/', '(?P<patten>.*)', $pattern);
                $pattern = '/^' . $pattern . '/';
                if (preg_match($pattern, $request_uri, $matches)) {
                    $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                    // add class request to first parameter
                    $params = [new Request(), ...$params];

                    call_user_func_array($callback, $params);
                    exit;
                }
            }
            $pattern = preg_replace('/\//', '\/', $url);
            // replace {id} with ([a-zA-Z0-9_]+) or {id?} with ([a-zA-Z0-9_]+)?
            $pattern = preg_replace('/\{([a-zA-Z0-9_]+)(\?)?\}/', '(?P<\1>[a-zA-Z0-9_]+)?', $pattern);
            $pattern = '/^' . $pattern . '$/';

            if (preg_match($pattern, $request_uri, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                // add class request to first parameter
                $params = [new Request(), ...$params];

                call_user_func_array($callback, $params);
                exit;
            }
        }
    }

    public static function group(string $prefix, callable $callback)
    {
        $request_uri = $_SERVER['REQUEST_URI'];

        // remove startwith / and endwith / from prefix
        $prefix = preg_replace('/^\/|\/$/', '', $prefix);
        // remove startwith / from url
        $request_uri = preg_replace('/^\/' . $prefix . '/', '', $request_uri);

        if ($request_uri != $_SERVER['REQUEST_URI']) {
            $_SERVER['REQUEST_URI'] = $request_uri;
            call_user_func($callback);
        }
    }

    public static function redirect(string $from, string $to, int $status = 301)
    {
        $request_uri = $_SERVER['REQUEST_URI'];
        if ($request_uri == $from) {
            header('Location: ' . $to, true, $status);
            exit;
        }
    }
}
