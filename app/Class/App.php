<?php

namespace App\Class;

class App
{
    final public static function isGET(): bool
    {
        return $_SERVER['REQUEST_METHOD'] == 'GET';
    }

    final public static function isPOST(): bool
    {
        return $_SERVER['REQUEST_METHOD'] == 'POST';
    }

    /** 
     * @return array [ip, country, cdn]
     */
    final public static function getAgentIP(): array
    {
        $ip = 'Unknown';
        $cdn = null;
        $country = 'Unknown';
        if (isset($_SERVER['HTTP_CDN_LOOP']) and $_SERVER['HTTP_CDN_LOOP'] == 'cloudflare') {
            $ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
            $cdn = $_SERVER['HTTP_CDN_LOOP'];
            $country = $_SERVER['HTTP_CF_IPCOUNTRY'];
        } else if (isset($_SERVER['HTTP_X_REAL_IP'])) {
            $ip = $_SERVER['HTTP_X_REAL_IP'];
        } else if (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return array('ip' => $ip, 'country' => $country, 'cdn' => $cdn);
    }

    final public static function RandomText($length): string
    {
        $character = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $characterLength = strlen($character);

        $random = '';

        for ($i = 0; $i < $length; $i++) {
            $random .= $character[rand(0, $characterLength - 1)];
        }
        return $random;
    }

    final public static function RandomHex(int $length = 6): string
    {
        $character = '0123456789abcdef';
        $characterLength = strlen($character);

        $randomHex = '';

        for ($i = 0; $i < $length; $i++) {
            $randomHex .= $character[rand(0, $characterLength - 1)];
        }
        return $randomHex;
    }

    /**
     * @param string $datetime
     * @param int $format 0 = วันที่แบบเต็ม, 1 = วันที่แบบย่อ
     * @param bool $time แสดงเวลาด้วยหรือไม่
     * @param bool $second แสดงวินาทีด้วยหรือไม่
     */
    final public static function th_date(string $datetime, int $format = 0, bool $istime = false, bool $issecond = false): string
    {
        list($date, $time) = explode(' ', $datetime);
        list($H, $i, $s) = explode(':', $time);
        list($Y, $m, $d) = explode('-', $date);
        $Y = $Y + 543;

        $month = array(
            '0' => array('01' => 'มกราคม', '02' => 'กุมภาพันธ์', '03' => 'มีนาคม', '04' => 'เมษายน', '05' => 'พฤษภาคม', '06' => 'มิถุนายน', '07' => 'กรกฏาคม', '08' => 'สิงหาคม', '09' => 'กันยายน', '10' => 'ตุลาคม', '11' => 'พฤศจิกายน', '12' => 'ธันวาคม'),
            '1' => array('01' => 'ม.ค.', '02' => 'ก.พ.', '03' => 'มี.ค.', '04' => 'เม.ย.', '05' => 'พ.ค.', '06' => 'มิ.ย.', '07' => 'ก.ค.', '08' => 'ส.ค.', '09' => 'ก.ย.', '10' => 'ต.ค.', '11' => 'พ.ย.', '12' => 'ธ.ค.')
        );

        $date =  $d . ' ' . $month[$format][$m] . ' ' . $Y;
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
    final public static function en_date(string $datetime, int $format = 0, bool $istime = false, bool $issecond = false): string
    {
        list($date, $time) = explode(' ', $datetime);
        list($H, $i, $s) = explode(':', $time);
        list($Y, $m, $d) = explode('-', $date);

        $month = array(
            '0' => array('01' => 'January', '02' => 'February', '03' => 'March', '04' => 'April', '05' => 'May', '06' => 'June', '07' => 'July', '08' => 'August', '09' => 'September', '10' => 'October', '11' => 'November', '12' => 'December'),
            '1' => array('01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr', '05' => 'May', '06' => 'Jun', '07' => 'July', '08' => 'Aug', '09' => 'Sep', '10' => 'Oct', '11' => 'Nov', '12' => 'Dec')
        );

        $date =  $d . ' ' . $month[$format][$m] . ' ' . $Y;
        if ($istime == true) {
            $date .= ' ' . $H . ':' . $i;
            if ($issecond == true) {
                $date .= ':' . $s;
            }
        }
        return $date;
    }
}
