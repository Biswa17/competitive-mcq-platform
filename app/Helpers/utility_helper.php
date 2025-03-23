<?php

if (!function_exists('p')) {
    function p($data) {
        echo '<pre>';
        print_r($data);
        die;
    }
}

if (!function_exists('call_curl')) {
    function call_curl($url, $method = 'POST', $body = [], $headers = [])
    {
        $ch = curl_init();

        // Set default headers
        $defaultHeaders = [
            "Content-Type: application/json",
        ];

        // Merge user-defined headers
        $headers = array_merge($defaultHeaders, $headers);

        // Configure cURL
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));

        // Handle request body for applicable methods
        if (!empty($body) && in_array(strtoupper($method), ['POST', 'PUT', 'PATCH'])) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
        }

        // Execute request
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_errno($ch)) {
            $error = 'Curl error: ' . curl_error($ch);
            curl_close($ch);
            return ['error' => $error, 'status' => $httpCode];
        }

        curl_close($ch);

        return ['response' => json_decode($response, true), 'status' => $httpCode];
    }
}
