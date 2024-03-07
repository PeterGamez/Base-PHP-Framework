<?php

namespace App\Core;

class Alert
{
    public static function alert(string $title, string $icon, int $timer, string $willClose): void
    {
        echo "<body><script>
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

    public static function alerts(string $title, string $icon, int $timer, string $willClose): void
    {
        echo "<head>
            <script src='https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.10.5/sweetalert2.all.min.js'></script>
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

    public static function alerts2(string $title, string $html, string $icon, int $timer, string $willClose): void
    {
        echo "<head>
            <script src='https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.10.5/sweetalert2.all.min.js'></script>
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

    public static function error(): void
    {
        self::alert('เกิดข้อผิดพลาด', 'error', 1500, 'history.back()');
    }
}
