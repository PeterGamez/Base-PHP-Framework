<?php

namespace System\Router;

class Request
{
    public $protocol;
    public $scheme;
    public $contentType;
    public $host;
    public $method;
    public $url;
    public $query;
    public $fullUrl;

    public $userAgent;
    public $ip;
    public $ips;
    public $country;
    public $cdn;

    public $authorization;
    public $headers;
    public $cookies;

    public function __construct()
    {
        $this->protocol = $_SERVER['SERVER_PROTOCOL'];
        $this->scheme = $_SERVER['REQUEST_SCHEME'];
        $this->contentType = $_SERVER['CONTENT_TYPE'];
        $this->host = $_SERVER['HTTP_HOST'];
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->url = parse_url($_SERVER['USER_REQUEST_URI'], PHP_URL_PATH);
        $this->query = $_SERVER['USER_QUERY_STRING'];
        $this->fullUrl = $this->scheme . '://' . $this->host . ($this->url == '/' ? '' : $this->url) . ($this->query == '' ? '' : '?' . $this->query);
        if ($this->query) {
            $query = explode('&', $this->query);
            $this->query = (object) [];
            foreach ($query as $q) {
                $q = explode('=', $q);
                $key = $q[0];
                $value = null;
                if (isset($q[1])) {
                    $value = $q[1];
                    $value = str_replace('%20', ' ', $value);
                }
                $this->query->$key = $value;
            }
        } else {
            $this->query = (object) [];
        }

        $this->userAgent = $_SERVER['HTTP_USER_AGENT'];
        $getIP = $this->getIP();
        $this->ip = $getIP['ip'];
        $this->ips = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? null;
        $this->country = $getIP['country'];
        $this->cdn = $getIP['cdn'];

        $this->authorization = $_SERVER['HTTP_AUTHORIZATION'] ?? null;
        $this->headers = (object) getallheaders();
        unset($this->headers->Authorization, $this->headers->Cookie);
        $this->cookies = (object) $_COOKIE;
    }

    public function input(string $input): string|null
    {
        if ($this->contentType and strpos($this->contentType, 'application/json') !== false) {
            $data = json_decode(file_get_contents('php://input'), true);
            if (isset($data[$input])) {
                return $data[$input];
            }
        }
        if (self::isMethod('GET')) {
            if (isset($_GET[$input])) {
                return $_GET[$input];
            }
        } elseif (self::isMethod('POST')) {
            if (isset($_POST[$input])) {
                return $_POST[$input];
            }
        }
        if (isset($_REQUEST[$input])) {
            return $_REQUEST[$input];
        }
        return null;
    }

    public function inputs(): array
    {
        if (isset($_SERVER['CONTENT_TYPE']) and strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
            return json_decode(file_get_contents('php://input'), true);
        }
        if (self::isMethod('GET')) {
            return $_GET;
        } elseif (self::isMethod('POST')) {
            return $_POST;
        }
        return $_REQUEST;
    }

    public function isMethod(string $method): bool
    {
        $method = strtoupper($method);
        return $this->method == $method;
    }

    public function validate(array $rules): Validate
    {
        return new Validate($this->inputs(), $rules);
    }

    public function getIP(): array
    {
        $ip = 'Unknown';
        $cdn = null;
        $country = 'Unknown';
        if (isset($_SERVER['HTTP_CDN_LOOP']) and $_SERVER['HTTP_CDN_LOOP'] == 'cloudflare') {
            $ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
            $cdn = $_SERVER['HTTP_CDN_LOOP'];
            $country = $_SERVER['HTTP_CF_IPCOUNTRY'];
        } elseif (isset($_SERVER['HTTP_X_REAL_IP'])) {
            $ip = $_SERVER['HTTP_X_REAL_IP'];
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return ['ip' => $ip, 'country' => $country, 'cdn' => $cdn];
    }
}
