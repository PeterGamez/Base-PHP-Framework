<?php

use App\Class\Api;

function config($key): ?string
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
        } else {
            $config = null;
        }
    }

    return $config;
}

function resources($key, $url = true)
{
    global $site, $request;

    $resourcePath = __ROOT__ . '/public/resources/' . $key;
    if (file_exists($resourcePath)) {
        if ($url == true) {
            $value = explode('.', $key);
            $key = $value[0];
            array_shift($value);
            $ext = implode('.', $value);

            return url('resources/' . $key, $ext);
        } else {
            include $resourcePath;
            return;
        }
    }
    return null;
}

function url($path = '', $ext = ''): string
{
    $protocol = $_SERVER['REQUEST_SCHEME'] . '://';
    if (!$path) $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
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
    if (empty($path)) return url(config('site.admin_panel'));
    return url(config('site.admin_panel') . '/' . $path, $ext);
}

function member_url($path = null, $ext = ''): string
{
    if (empty($path)) return url(config('site.member_panel'));
    return url(config('site.member_panel') . '/' . $path, $ext);
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
    $viewPath = __ROOT__ . '/views/' . $filename . '.php';
    if (file_exists($viewPath)) {
        include $viewPath;
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

function api($method, $filename, $data = null): void
{
    if ($data) {
        extract($data);
    }

    if (str_contains($method, '|')) {
        $methods = explode('|', $method);
        if (!in_array($_SERVER['REQUEST_METHOD'], $methods)) {
            echo Api::error_405();
            exit;
        }
    } else {
        if ($method != $_SERVER['REQUEST_METHOD']) {
            echo Api::error_405();
            exit;
        }
    }

    $filename = str_replace('.', '/', $filename);
    $viewPath = __ROOT__ . '/api/' . $filename . '.php';
    if (file_exists($viewPath)) {
        include $viewPath;
    }
}

function loaddir($path): void
{
    $folders = scandir($path); // ไฟล์ทั้งหมดในโฟลเดอร์
    foreach ($folders as $key => $value) {
        if ($value == '.' || $value == '..') continue;
        if (is_dir($path . '/' . $value)) {
            $files = scandir($path . '/' . $value); // ไฟล์ทั้งหมดในโฟลเดอร์
            foreach ($files as $key => $file) {
                if ($file == '.' || $file == '..') continue;
                if (substr($file, -4) == '.php') { // เฉพาะไฟล์ .php
                    require_once $path . '/' . $value . '/' . $file;
                }
            }
        } else {
            if (substr($value, -4) == '.php') { // เฉพาะไฟล์ .php
                require_once $path . '/' . $value;
            }
        }
    }
}
