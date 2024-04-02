<?php

namespace App\Core;

class App
{
    public static function RandomText(int $length): string
    {
        $character = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $characterLength = strlen($character);

        $random = '';

        for ($i = 0; $i < $length; $i++) {
            $random .= $character[rand(0, $characterLength - 1)];
        }
        return $random;
    }

    public static function RandomHex(int $length = 6): string
    {
        $character = '0123456789ABCDEF';
        $characterLength = strlen($character);

        $randomHex = '';

        for ($i = 0; $i < $length; $i++) {
            $randomHex .= $character[rand(0, $characterLength - 1)];
        }
        return $randomHex;
    }

    public static function apiRequest(string $api_url, array $post = null): array
    {
        $ch = curl_init($api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if ($post) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
        }

        $headers = [];
        $headers[] = 'Accept: application/json';
        $headers[] = 'User-Agent: PHP/1.0';

        if (isset($_SESSION['access_token'])) {
            $headers[] = 'Authorization: Bearer ' . $_SESSION['access_token'];
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);
        curl_close($ch);
        return json_decode($response, true);
    }

    /**
     * @param string $datetime
     * @param int $format 0 = วันที่แบบเต็ม, 1 = วันที่แบบย่อ
     * @param bool $time แสดงเวลาด้วยหรือไม่
     * @param bool $second แสดงวินาทีด้วยหรือไม่
     */
    public static function th_date(string $datetime, int $format = 0, bool $istime = false, bool $issecond = false): string
    {
        [$date, $time] = explode(' ', $datetime);
        [$H, $i, $s] = explode(':', $time);
        [$Y, $m, $d] = explode('-', $date);
        $Y = $Y + 543;

        $month = [
            '0' => ['01' => 'มกราคม', '02' => 'กุมภาพันธ์', '03' => 'มีนาคม', '04' => 'เมษายน', '05' => 'พฤษภาคม', '06' => 'มิถุนายน', '07' => 'กรกฏาคม', '08' => 'สิงหาคม', '09' => 'กันยายน', '10' => 'ตุลาคม', '11' => 'พฤศจิกายน', '12' => 'ธันวาคม'],
            '1' => ['01' => 'ม.ค.', '02' => 'ก.พ.', '03' => 'มี.ค.', '04' => 'เม.ย.', '05' => 'พ.ค.', '06' => 'มิ.ย.', '07' => 'ก.ค.', '08' => 'ส.ค.', '09' => 'ก.ย.', '10' => 'ต.ค.', '11' => 'พ.ย.', '12' => 'ธ.ค.']
        ];

        $date = $d . ' ' . $month[$format][$m] . ' ' . $Y;
        if ($istime == true) {
            $date .= ' ' . $H . ':' . $i;
            if ($issecond == true) {
                $date .= ':' . $s;
            }
        }
        return $date;
    }

    /**
     * @param string $datetime
     * @param int $format 0 = วันที่แบบเต็ม, 1 = วันที่แบบย่อ
     * @param bool $time แสดงเวลาด้วยหรือไม่
     * @param bool $second แสดงวินาทีด้วยหรือไม่
     */
    public static function en_date(string $datetime, int $format = 0, bool $istime = false, bool $issecond = false): string
    {
        [$date, $time] = explode(' ', $datetime);
        [$H, $i, $s] = explode(':', $time);
        [$Y, $m, $d] = explode('-', $date);

        $month = [
            '0' => ['01' => 'January', '02' => 'February', '03' => 'March', '04' => 'April', '05' => 'May', '06' => 'June', '07' => 'July', '08' => 'August', '09' => 'September', '10' => 'October', '11' => 'November', '12' => 'December'],
            '1' => ['01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr', '05' => 'May', '06' => 'Jun', '07' => 'July', '08' => 'Aug', '09' => 'Sep', '10' => 'Oct', '11' => 'Nov', '12' => 'Dec']
        ];

        $date = $d . ' ' . $month[$format][$m] . ' ' . $Y;
        if ($istime == true) {
            $date .= ' ' . $H . ':' . $i;
            if ($issecond == true) {
                $date .= ':' . $s;
            }
        }
        return $date;
    }
}
