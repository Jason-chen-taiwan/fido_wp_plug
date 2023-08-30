<?php

function postToAuthFi($accessPath, $postData) {
    $url = get_option('fido_RP_server_API_endpoint');  // 替換 YOUR_ACCESS_POINT 為實際的 API 接入點
    $apiKey = get_option('fido_RP_server_API_key');  // 替換 YOUR_API_KEY 為實際的 API key
    
    $ch = curl_init($url);
    
    $headers = array(
        'Accept: application/json',
        'Accept-Charset: utf-8',
        'AT-X-Key: ' . $apiKey
    );
    
    if ($postData) {
        $postDataJson = json_encode($postData);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postDataJson);
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Content-Length: ' . strlen($postDataJson);
    }
    
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    
    $response = curl_exec($ch);
    
    if (curl_errno($ch)) {
        // 如果有錯誤，你可以在這裡處理
        throw new Exception('Curl error: ' . curl_error($ch));
    }

    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    return array(
        'status' => $httpCode,
        'body' => json_decode($response, true)
    );
}
