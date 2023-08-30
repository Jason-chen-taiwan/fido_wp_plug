<?php

// 取得 'fido_RP_server_API_endpoint' 設置的值
$fido_RP_server_API_endpoint = get_option('fido_RP_server_API_endpoint');
// 取得 'fido_RP_server_API_key' 設置的值
$fido_RP_server_API_key = get_option('fido_RP_server_API_key');
//設定API url
$webauthn_api_base = '/api/v1/webauthn';
$api_total_url = $fido_RP_server_API_endpoint . $webauthn_api_base;
//handel register option 1
function wp_webauthn_do_register_option(WP_REST_Request $request) {
    try {
         // Replace webauthn.doRegisterOption with your WP logic
        $result = do_register_option(
            $request->get_param('email'),
            $request->get_param('name'),
            $request->get_param('registerOption')
        );
        return $result;
    } catch (Exception $e) {
        // Handle errors
        return new WP_Error('register_step1_error', $e->getMessage(), array('status' => 400));
    }
}

//for fido register step 1
function do_register_option($user_account, $user_name, $options = null) {
    $params = array(
        'params' => array(
            'user' => array(
                'name' => $user_account,
                'displayName' => $user_name,
            ),
            'authenticatorSelection' => array(
                'authenticatorAttachment' => 'cross-platform',
                'requireResidentKey' => true,
                'userVerification' => 'required',
            ),
            'attestation' => 'direct'
        )
    );

    if (is_array($options)) {
        if (isset($options['authenticatorAttachment'])) {
            if (in_array($options['authenticatorAttachment'], array('cross-platform', 'platform'))) {
                $params['authenticatorSelection']['authenticatorAttachment'] = $options['authenticatorAttachment'];
            }
        }

        if (isset($options['requireResidentKey'])) {
            $params['authenticatorSelection']['requireResidentKey'] = $options['requireResidentKey'];
        }

        if (isset($options['userVerification'])) {
            if (in_array($options['userVerification'], array('preferred', 'required', 'discouraged'))) {
                $params['authenticatorSelection']['userVerification'] = $options['userVerification'];
            }
        }
    }
    // return $params;
    // set total url
    global $api_total_url, $fido_RP_server_API_key, $fido_RP_server_API_endpoint;
    $url = $api_total_url . '/registration';

    // return array($url, $fido_RP_server_API_key,$params);
    // return json_encode($params);

    // set post url destination
    $ch = curl_init($url);

    $data = json_encode($params);
    // echo $data;
    
    // 設置cURL選項
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Accept: application/json',
        'Accept-Charset: utf-8',
        'AT-X-Key:'.$fido_RP_server_API_key,
        'Content-Length: ' . strlen($data),
        'Content-Type: application/json'
    ));
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data); // 將數組編碼為JSON

    // 執行cURL session
    $response = curl_exec($ch);
    // 檢查是否有cURL錯誤
    if (curl_errno($ch)) {
        echo 'cURL error: ' . curl_error($ch);
    } else {
        // 將 PHP 陣列轉換成 JSON 格式
        // $jsonResponse = json_encode($response);
        // return $jsonResponse;  // 返回 JSON 格式的響應
        return $response;
    }
    // 關閉cURL session
    curl_close($ch);
    
}

//for fido register step 2
function wp_webauthn_put_register_option(WP_REST_Request $request){
    global $api_total_url, $fido_RP_server_API_key, $fido_RP_server_API_endpoint;
    $url = $api_total_url . '/registration';
    // 获取JSON主体内容
    $body = $request->get_json_params();
    $data = json_encode(array('fido_register_response' => $body));

    // set post url destination
    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Accept: application/json',
        'Accept-Charset: utf-8',
        'AT-X-Key:'.$fido_RP_server_API_key,
        'Content-Length: ' . strlen($data),
        'Content-Type: application/json'
    ));
     // 執行cURL session
     $response = curl_exec($ch);
     // 檢查是否有cURL錯誤
     if (curl_errno($ch)) {
         echo 'cURL error: ' . curl_error($ch);
     } else {
         return $response;
     }
     // 關閉cURL session
     curl_close($ch);
}

//for fido login step 1
function wp_webauthn_do_login_option(WP_REST_Request $request){
    $params = array(
        'params' => array(
        "userVerification" => "required"
        )
    );
    $data = json_encode($params);
    global $api_total_url, $fido_RP_server_API_key, $fido_RP_server_API_endpoint;
    $url = $api_total_url . '/login';

    $ch = curl_init($url);

    $data = json_encode($params);
    
    // 設置cURL選項
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Accept: application/json',
        'Accept-Charset: utf-8',
        'AT-X-Key:'.$fido_RP_server_API_key,
        'Content-Length: ' . strlen($data),
        'Content-Type: application/json'
    ));
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data); // 將數組編碼為JSON

    // 執行cURL session
    $response = curl_exec($ch);
    // 檢查是否有cURL錯誤
    if (curl_errno($ch)) {
        echo 'cURL error: ' . curl_error($ch);
    } else {
        // 將 PHP 陣列轉換成 JSON 格式
        // $jsonResponse = json_encode($response);
        // return $jsonResponse;  // 返回 JSON 格式的響應
        return $response;
    }
    // 關閉cURL session
    curl_close($ch);
}

//for fido login step 2
function wp_webauthn_put_login_option(WP_REST_Request $request){
    global $api_total_url, $fido_RP_server_API_key, $fido_RP_server_API_endpoint;
    $url = $api_total_url . '/login';
    // 获取JSON主体内容
    $body = $request->get_json_params();
    $data = json_encode(array('fido_login_response' => $body));

     // set post url destination
     $ch = curl_init($url);

     curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
     curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
     curl_setopt($ch, CURLOPT_HTTPHEADER, array(
         'Accept: application/json',
         'Accept-Charset: utf-8',
         'AT-X-Key:'.$fido_RP_server_API_key,
         'Content-Length: ' . strlen($data),
         'Content-Type: application/json'
     ));
      // 執行cURL session
      $response = curl_exec($ch);
      // 檢查是否有cURL錯誤
      if (curl_errno($ch)) {
          echo 'cURL error: ' . curl_error($ch);
      } else {
          return $response;
      }
      // 關閉cURL session
      curl_close($ch);
}
    