<?php

namespace App\Class;

class Fetch
{
    final public static function get(string $url): array
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_CONNECTTIMEOUT => 3,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_USERAGENT => config('site.useragent'),
            CURLOPT_RETURNTRANSFER => true,
        ));

        $data = curl_exec($curl);

        if (curl_errno($curl)) {
            echo Alert::alerts('ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์ได้', 'error', 1500, 'history.back()');
            // echo curl_error($curl);
            exit;
        }

        curl_close($curl);

        return json_decode($data, true);
    }
    final public static function post(string $url, array $body): array
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_CONNECTTIMEOUT => 3,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_USERAGENT => config('site.useragent'),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $body,
        ));

        $data = curl_exec($curl);

        if (curl_errno($curl)) {
            echo Alert::alerts('ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์ได้', 'error', 1500, 'history.back()');
            // curl_error($curl);
            exit;
        }

        curl_close($curl);

        return json_decode($data, true);
    }
}
