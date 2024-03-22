<?php

namespace App\Core;

class Notify
{
    /**
     * https://carlosroso.com/notyf/
     */
    public static string $cdn_css = 'https://cdnjs.cloudflare.com/ajax/libs/notyf/3.10.0/notyf.min.css';
    public static string $cdn_js = 'https://cdnjs.cloudflare.com/ajax/libs/notyf/3.10.0/notyf.min.js';

    public string $type = '';
    public string $message = '';
    public int $duration = 3000;

    public bool $ripple = true;
    public bool $dismissible = false;

    public static function success(string $message = null)
    {
        $notify = new self();
        $notify->type = 'success';
        if ($message) {
            $notify->message = $message;
        }
        return $notify;
    }

    public static function error(string $message = null)
    {
        $notify = new self();
        $notify->type = 'error';
        if ($message) {
            $notify->message = $message;
        }
        return $notify;
    }

    public function message(string $message)
    {
        $this->message = $message;
        return $this;
    }

    public function duration(int $duration)
    {
        $this->duration = $duration;
        return $this;
    }

    public function ripple(bool $ripple)
    {
        $this->ripple = $ripple;
        return $this;
    }

    public function dismissible(bool $dismissible)
    {
        $this->dismissible = $dismissible;
        return $this;
    }

    public function __destruct()
    {
        $this->show();
    }

    public function show()
    {
        $ripple = $this->ripple ? 'true' : 'false';
        $dismissible = $this->dismissible ? 'true' : 'false';
        $script = "<script>new Notyf({duration:$this->duration,position:{x:'right',y:'top'},ripple:$ripple,dismissible:$dismissible}).$this->type('$this->message')</script>";
        if (empty (ob_get_contents())) {
            echo "<!DOCTYPE html><html><head><style>body{font-family:'Nunito',sans-serif}</style><link rel='stylesheet' href='" . self::$cdn_css . "'><script src='" . self::$cdn_js . "'></script></head><body>$script</body></html>";
        } else {
            echo $script;
        }
    }
}
