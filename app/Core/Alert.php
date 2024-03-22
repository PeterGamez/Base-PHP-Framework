<?php

namespace App\Core;

class Alert
{
    /**
     * https://sweetalert2.github.io
     */
    public static string $cdn_js = 'https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.10.6/sweetalert2.all.min.js';

    private string $title = '';

    private string $html = '';

    private string $text = '';

    /**
     * @var string success, error, warning, info, question
     */
    private string $icon = '';

    private string $footer = '';

    private int $timer = 0;

    private string $willClose = '';

    public static function success(string $title = null)
    {
        $alert = new self();
        $alert->icon('success');
        if ($title) {
            $alert->title($title);
        }
        return $alert;
    }

    public static function error(string $title = null)
    {
        $alert = new self();
        $alert->icon('error');
        if ($title) {
            $alert->title($title);
        }
        return $alert;
    }

    public static function warning(string $title = null)
    {
        $alert = new self();
        $alert->icon('warning');
        if ($title) {
            $alert->title($title);
        }
        return $alert;
    }

    public static function info(string $title = null)
    {
        $alert = new self();
        $alert->icon('info');
        if ($title) {
            $alert->title($title);
        }
        return $alert;
    }

    public static function question(string $title = null)
    {
        $alert = new self();
        $alert->icon('question');
        if ($title) {
            $alert->title($title);
        }
        return $alert;
    }

    public function title(string $title)
    {
        $this->title = $title;
        return $this;
    }

    public function html(string $html)
    {
        $this->html = $html;
        return $this;
    }

    private function icon(string $icon)
    {
        $this->icon = $icon;
        return $this;
    }

    public function timer(int $milliseconds)
    {
        $this->timer = $milliseconds;
        return $this;
    }

    public function willClose($willClose)
    {
        $this->willClose = $willClose;
        return $this;
    }

    public function __destruct()
    {
        $this->show();
    }

    private function show()
    {
        $data = implode(',', array_filter([
            $this->title ? "title:'$this->title'" : null,
            $this->html ? "html:'$this->html'" : null,
            $this->icon ? "icon:'$this->icon'" : null,
            $this->timer ? "timer: $this->timer" : null,
            $this->timer ? "timerProgressBar: true" : null,
            $this->willClose ? "willClose: ()=>{ $this->willClose}" : null
        ]));
        $script = "<script>Swal.fire({" . $data . "})</script>";
        // check document has head tag
        if (empty (ob_get_contents())) {
            echo "<!DOCTYPE html><html><head><script src='" . self::$cdn_js . "'></script></head><body>$script</body></html>";
        } else {
            echo $script;
        }
    }
}
