<?php

namespace App\Class;

class Fetch
{
    final public static function get(string $url): array
    {
        $curl = curl_init();

        $header = array(
            'Content-Type: application/json',
        );
        if (isset($_SESSION['account']) and in_array($_SESSION['account']['role'], ['owner', 'admin'])) {
            $header[] = 'Authorization: Bearer ' . $_SESSION['account']['api_key'];
        }
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_CONNECTTIMEOUT => 3,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_USERAGENT => config('site.useragent'),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $header,
        ));

        $data = curl_exec($curl);

        if (curl_errno($curl) or empty($data) or !preg_match('/^{.*}$/m', $data)) {
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

        $header = array(
            'Content-Type: application/json',
        );
        if (isset($_SESSION['account']) and in_array($_SESSION['account']['role'], ['owner', 'admin'])) {
            $header[] = 'Authorization: Bearer ' . $_SESSION['account']['api_key'];
        }
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_CONNECTTIMEOUT => 3,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_USERAGENT => config('site.useragent'),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $header,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $body,
        ));

        $data = curl_exec($curl);

        if (curl_errno($curl) or empty($data) or !preg_match('/^{.*}$/m', $data)) {
            echo Alert::alerts('ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์ได้', 'error', 1500, 'history.back()');
            // curl_error($curl);
            exit;
        }

        curl_close($curl);

        return json_decode($data, true);
    }

    final public static function mget($datas)
    {
        $mh = curl_multi_init();

        $header = array(
            'Content-Type: application/json',
        );
        if (isset($_SESSION['account']) and in_array($_SESSION['account']['role'], ['owner', 'admin'])) {
            $header[] = 'Authorization: Bearer ' . $_SESSION['account']['api_key'];
        }

        $curl_base = curl_init();
        curl_setopt_array($curl_base, array(
            CURLOPT_CONNECTTIMEOUT => 3,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_USERAGENT => config('site.useragent'),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $header,
        ));

        foreach ($datas as $i => $data) {
            $curl[$i] = curl_copy_handle($curl_base);
            curl_setopt($curl[$i], CURLOPT_URL, $data['url']);
           
            curl_multi_add_handle($mh, $curl[$i]);
        }

        $running = null;
        do {
            curl_multi_exec($mh, $running);
        } while ($running);

        $respond = array();
        foreach ($datas as $i => $data) {
            $respond[$i] = json_decode(curl_multi_getcontent($curl[$i]), true);
            curl_multi_remove_handle($mh, $curl[$i]);
        }

        curl_multi_close($mh);

        return $respond;
    }

    final public static function mpost($datas)
    {
        $mh = curl_multi_init();

        $header = array(
            'Content-Type: application/json',
        );
        if (isset($_SESSION['account']) and in_array($_SESSION['account']['role'], ['owner', 'admin'])) {
            $header[] = 'Authorization: Bearer ' . $_SESSION['account']['api_key'];
        }

        $curl_base = curl_init();
        curl_setopt_array($curl_base, array(
            CURLOPT_CONNECTTIMEOUT => 3,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_USERAGENT => config('site.useragent'),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $header,
            CURLOPT_POST => true,
        ));

        foreach ($datas as $i => $data) {
            $curl[$i] = curl_copy_handle($curl_base);
            curl_setopt($curl[$i], CURLOPT_URL, $data['url']);
            curl_setopt($curl[$i], CURLOPT_POSTFIELDS, $data['body']);
           
            curl_multi_add_handle($mh, $curl[$i]);
        }

        $running = null;
        do {
            curl_multi_exec($mh, $running);
        } while ($running);

        $respond = array();
        foreach ($datas as $i => $data) {
            $respond[$i] = json_decode(curl_multi_getcontent($curl[$i]), true);
            curl_multi_remove_handle($mh, $curl[$i]);
        }

        curl_multi_close($mh);

        return $respond;
    }
}
