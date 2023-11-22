<?php

namespace App\Class;

class Alert
{
    final public static function alert(string $title, string $icon, int $timer, string $willClose): string
    {
        return "<body><script>
            Swal.fire({
                title: '$title',
                icon: '$icon',
                timer: $timer,
                willClose: () => {
                    $willClose
                }
            })
        </script></body>";
    }

    final public static function alerts(string $title, string $icon, int $timer, string $willClose): string
    {
        return "<head>
            <script src='https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.10.1/sweetalert2.all.min.js'></script>
        </head>
        <body>
            <script>
                Swal.fire({
                    title: '$title',
                    icon: '$icon',
                    timer: $timer,
                    willClose: () => {
                        $willClose
                    }
                })
            </script>
        </body>";
    }

    final public static function alerts2(string $title, string $html, string $icon, int $timer, string $willClose): string
    {
        return "<head>
            <script src='https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.10.1/sweetalert2.all.min.js'></script>
        </head>
        <body>
            <script>
                Swal.fire({
                    title: '" . $title . "',
                    html: '" . $html . "',
                    icon: '" . $icon . "',
                    timer: " . $timer . ",
                    willClose: () => {
                        " . $willClose . "
                    }
                })
            </script>
        </body>";
    }

    final public static function error(): string
    {
        return self::alert('เกิดข้อผิดพลาด', 'error', 1500, 'history.back()');
    }
}
