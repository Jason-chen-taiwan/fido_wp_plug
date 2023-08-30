<?php
// 1. 註冊新的REST路由
function add_register_rest_api () {
    register_rest_route('api/v1', '/register', array(
        'methods' => 'POST',
        'callback' => 'wp_webauthn_do_register_option',
        'permission_callback' => '__return_true' // 這裡可以進一步設定許可權
    ));
    register_rest_route('api/v1', '/register', array(
        'methods' => 'PUT',
        'callback' => 'wp_webauthn_put_register_option',
        'permission_callback' => '__return_true' // 這裡可以進一步設定許可權
    ));
    register_rest_route('api/v1', '/login', array(
        'methods' => 'POST',
        'callback' => 'wp_webauthn_do_login_option',
        'permission_callback' => '__return_true' // 這裡可以進一步設定許可權
    ));
    register_rest_route('api/v1', '/login', array(
        'methods' => 'PUT',
        'callback' => 'wp_webauthn_put_login_option',
        'permission_callback' => '__return_true' // 這裡可以進一步設定許可權
    ));
}
add_action( 'rest_api_init', 'add_register_rest_api' );

