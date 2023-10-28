<?php

namespace App\Class;

class Api
{
    final public static function error_400(): string
    {
        $error = [
            'code' => 400,
            'message' => 'Bad Request'
        ];
        http_response_code(400);
        return json_encode($error);
    }

    final public static function error_404($message = null): string
    {
        $error = [
            'code' => 404,
            'message' => $message ?? 'Not Found'
        ];
        http_response_code(404);
        return json_encode($error);
    }

    final public static function error_405(): string
    {
        $error = [
            'code' => 405,
            'message' => 'Method Not Allowed'
        ];
        http_response_code(405);
        return json_encode($error);
    }
    
    final public static function apiRequest(string $api_url, array $post = null): array
    {
        $ch = curl_init($api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if ($post) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
        }

        $headers = array();
        $headers[] = 'Accept: application/json';
        $headers[] = 'User-Agent: /1.0';

        if (isset($_SESSION['access_token'])) {
            $headers[] = 'Authorization: Bearer ' . $_SESSION['access_token'];
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);
        curl_close($ch);
        return json_decode($response, true);
    }
}
