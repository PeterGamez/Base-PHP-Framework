<?php

namespace System\Router;

class Route
{
    public static function get(string $url, callable $callback): BaseRoute
    {
        return self::route(['GET'], $url, $callback);
    }

    public static function head(string $url, callable $callback): BaseRoute
    {
        return self::route(['HEAD'], $url, $callback);
    }

    public static function post(string $url, callable $callback): BaseRoute
    {
        return self::route(['POST'], $url, $callback);
    }

    public static function put(string $url, callable $callback): BaseRoute
    {
        return self::route(['PUT'], $url, $callback);
    }

    public static function delete(string $url, callable $callback): BaseRoute
    {
        return self::route(['DELETE'], $url, $callback);
    }

    public static function connect(string $url, callable $callback): BaseRoute
    {
        return self::route(['CONNECT'], $url, $callback);
    }

    public static function options(string $url, callable $callback): BaseRoute
    {
        return self::route(['OPTIONS'], $url, $callback);
    }

    public static function trace(string $url, callable $callback): BaseRoute
    {
        return self::route(['TRACE'], $url, $callback);
    }

    public static function patch(string $url, callable $callback): BaseRoute
    {
        return self::route(['PATCH'], $url, $callback);
    }

    public static function match(array $methods, string $url, callable $callback): BaseRoute
    {
        return self::route($methods, $url, $callback);
    }

    public static function any(string $url, callable $callback): BaseRoute
    {
        return self::route(['GET', 'HEAD', 'POST', 'PUT', 'DELETE', 'CONNECT', 'OPTIONS', 'TRACE', 'PATCH'], $url, $callback);
    }

    private static function route(array $methods, string $url, callable $callback): BaseRoute
    {
        $method = $_SERVER['REQUEST_METHOD'];

        $request_uri = $_SERVER['REQUEST_URI'];
        $request_uri = preg_replace('/\?.*$/', '', $request_uri);
        if ($request_uri == '/') {
            $request_uri = '';
        }

        $url = preg_replace('/\/$/', '', $url);

        if (in_array($method, $methods)) {
            /* if url are end with * */
            if (str_ends_with($url, '*')) {
                $pattern = preg_replace('/\//', '\/', $url);
                /* replace * with {patten} */
                $pattern = preg_replace('/\*$/', '(?P<patten>.*)', $pattern);
                $pattern = '/^' . $pattern . '/';
                if (preg_match($pattern, $request_uri, $matches)) {
                    $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

                    return self::createRoute($callback, $params);
                }
            }
            $pattern = preg_replace('/\//', '\/', $url);
            /* replace {id} with ([a-zA-Z0-9_]+) or {id?} with ([a-zA-Z0-9_]+)? */
            $pattern = preg_replace('/\{([a-zA-Z0-9_]+)(\?)?\}/', '(?P<\1>[a-zA-Z0-9_]+)?', $pattern);
            $pattern = '/^' . $pattern . '$/';

            if (preg_match($pattern, $request_uri, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

                return self::createRoute($callback, $params);
            }
        }
        return new BaseRoute();
    }

    public static function group(string $prefix, callable $callback): BaseGroup
    {
        $request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        /* remove startwith / and endwith / from prefix */
        $prefix = preg_replace('/^\/|\/$/', '', $prefix);
        /* remove startwith / from url */
        $request_uri = preg_replace('/^\/|\/$/', '', $request_uri);
        /* get only first path from url */
        $request_uri = preg_replace('/\/.*/', '', $request_uri);

        // echo $prefix . ' ' . $request_uri . '<br>';
        if ($prefix == $request_uri) {
            /* remove prefix from url */
            $_SERVER['REQUEST_URI'] = preg_replace('/^\/' . $prefix . '/', '', $_SERVER['REQUEST_URI']);
            $group = new BaseGroup();
            $group->request = new Request();
            $group->next = $callback;
            return $group;
        }
        return new BaseGroup();
    }

    public static function redirect(string $from, string $to, int $status = 301): void
    {
        $request_uri = $_SERVER['REQUEST_URI'];
        if ($request_uri == $from) {
            header('Location: ' . $to, true, $status);
            exit;
        }
    }

    private static function createRoute(callable $callback, array $params = []): BaseRoute
    {
        $request = new Request();

        $route = new BaseRoute();
        $route->request = $request;
        $route->params = [$request, ...$params];
        $route->next = $callback;
        return $route;
    }
}
