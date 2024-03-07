<?php

function config($key): array|string|null
{
    static $loadedConfigs = [];

    $configKeys = explode('.', $key);
    $filename = array_shift($configKeys);

    // Load config file if not loaded
    if (empty($loadedConfigs[$filename])) {
        $loadedConfigs[$filename] = require_once(__ROOT__ . '/config/' . $filename . '.php');
    }

    // Get config value
    $config = $loadedConfigs[$filename];

    foreach ($configKeys as $nestedKey) {
        if (isset($config[$nestedKey])) {
            $config = $config[$nestedKey];
            continue;
        } else {
            $config = null;
        }
    }

    return $config;
}

function resources($key, $url = true): void
{
    $resourcePath = __ROOT__ . '/public/resources/' . $key;
    if (file_exists($resourcePath)) {
        if ($url == true) {
            $value = explode('.', $key);
            $key = $value[0];
            array_shift($value);
            $ext = implode('.', $value);

            echo url('resources/' . $key, $ext);
        } else {
            include $resourcePath;
        }
    }
}

function url($path = '', $ext = ''): string
{
    $protocol = $_SERVER['REQUEST_SCHEME'] . '://';
    if (!$path) $path = parse_url($_SERVER['USER_REQUEST_URI'], PHP_URL_PATH);
    else {
        $path = str_replace('.', '/', $path);
        if ($path[0] != '/') $path = '/' . $path;
    }
    $url = $protocol . $_SERVER['HTTP_HOST'] . $path;
    if ($ext) $url .= '.' . $ext;
    return $url;
}

function sub_url($sub, $path = '', $ext = ''): string
{
    return url($sub . '/' . $path, $ext);
}

function admin_url($path = null, $ext = ''): string
{
    if (empty($path)) return url('/admin');
    return url('/admin/' . $path, $ext);
}

function member_url($path = null, $ext = ''): string
{
    if (empty($path)) return url('/member');
    return url('/member/' . $path, $ext);
}

function url_back(): string
{
    if (isset($_SERVER['HTTP_REFERER'])) return $_SERVER['HTTP_REFERER'];
    else return url('/');
}

function redirect($path): void
{
    header('Location: ' . $path);
    exit;
}

function views($filename, $data = null): void
{
    global $site;

    if ($data) {
        extract($data);
    }

    $filename = str_replace('.', '/', $filename);
    $viewPath = __ROOT__ . '/resources/views/' . $filename . '.php';

    if (file_exists($viewPath)) {
        if (config('site.minify.html') == true) {
            ob_start(function ($buffer) {
                /**
                 * remove comments
                 * remove whitespaces 
                 */
                $search = [
                    '/<!--(.|\s)*?-->/',
                    '/\s{2,}/',
                ];

                $buffer = preg_replace($search, '', $buffer);
                return $buffer;
            });
        }

        include $viewPath;

        if (config('site.minify.html') == true) {
            ob_end_flush();
        }
    }
}

function admin_views($path = '', $data = null): void
{
    views('admin/' . $path, $data);
}

function member_views($path = '', $data = null): void
{
    views('member/' . $path, $data);
}

function visitor_views($path = '', $data = null): void
{
    views('visitor/' . $path, $data);
}

function api($filename, $data = null): void
{
    if ($data) {
        extract($data);
    }

    $filename = str_replace('.', '/', $filename);
    $viewPath = __ROOT__ . '/resources/api/' . $filename . '.php';
    if (file_exists($viewPath)) {
        include $viewPath;
    }
}

function autoload($className): void
{
    $className = str_replace('\\', '/', $className);
    $file = __ROOT__ . '/' . $className . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
}

function env($key, $default = null): ?string
{
    static $env = null;

    if ($env === null) {
        $env = parse_ini_file(__ROOT__ . '/.env');
    }

    if (isset($env[$key])) {
        return $env[$key];
    } else {
        return $default;
    }
}
