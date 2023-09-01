<?php
/*
Plugin Name: FIDO Login
*/
require_once 'init_hook.php';
require_once plugin_dir_path(__FILE__) . 'includes/rest_rout.php';
require_once plugin_dir_path(__FILE__) . 'includes/rest_api_control.php';


function gogo_enqueue_scripts() {
    wp_enqueue_script('fido-login-script', plugins_url('assets/js/fido_login.js', __FILE__));
    wp_enqueue_script('fido-login-base64', plugins_url('assets/js/base64.js', __FILE__));
    wp_enqueue_script('fido-login-tools', plugins_url('assets/js/tools.js', __FILE__));
}
add_action('wp_enqueue_scripts', 'gogo_enqueue_scripts');


//添加 fido 設定頁面
add_action('admin_menu', 'fido_add_setting');
function fido_add_setting(){
    add_menu_page(
        'Fido Settings Page', //page_title
        'FIDO Setting', //menu_title
        'manage_options', //capability
        'fido-options', //memu-slug
        'fido_setting_page_callback', //callback
        'dashicons-smiley' //icon
    );
}

//添加設定頁面內的欄位
add_action('admin_init', 'fido_setting_init');
function fido_setting_init(){
    register_setting('fido_setting_group', 'fido_RP_server_API_endpoint');
    register_setting('fido_setting_group','fido_RP_server_API_key');

    //添加section區段來包含endppoint跟api_key兩個設定
    add_settings_section(
        'fido_RP_server_API_section', //id
        'fido_RP_server_API', //title
        'fido_RP_server_API_callback', //callback
        'fido-options' //page
    );
    add_settings_field(
        'fido_RP_server_API_endpoint_field', //id
        'fido_RP_server_API_endpoint_field', //title
        'fido_RP_server_API_endpoint_field_callback', //callback
        'fido-options', //page
        'fido_RP_server_API_section', //section
        array('label_for' => 'fido_RP_server_API_endpoint')
    );
    add_settings_field(
        'fido_RP_server_API_key_field', //id
        'fido_RP_server_API_key_field', //title
        'fido_RP_server_API_key_field_callback', //callback
        'fido-options', //page
        'fido_RP_server_API_section', //section
        array('label_for' => 'fido_RP_server_API_key')
    );
}

//fido setting page show
function fido_setting_page_callback() {
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <form action="options.php" method="post">
            <?php
            // 輸出設置字段
            settings_fields('fido_setting_group');
            // 輸出設置部分
            do_settings_sections('fido-options');
            // 輸出保存設置的按鈕
            submit_button('Save Settings');
            ?>
        </form>
    </div>
    <?php
}

function fido_RP_server_API_callback(){
    echo 'Configure your FIDO settings below:';
}
function fido_RP_server_API_endpoint_field_callback(){
    $fido_RP_server_API_endpoint = get_option('fido_RP_server_API_endpoint');
    echo "<input id='fido_RP_server_API_endpoint' type='text' name='fido_RP_server_API_endpoint' value='" . esc_attr($fido_RP_server_API_endpoint) . "' />";
}
function fido_RP_server_API_key_field_callback(){
    $fido_RP_server_API_key = get_option('fido_RP_server_API_key');
    echo "<input id='fido_RP_server_API_key' type='text' name='fido_RP_server_API_key' value='" . esc_attr($fido_RP_server_API_key) . "' />";
}

//導入boostrap
function enqueue_bootstrap_in_plugin() {
    // 引入 Bootstrap CSS
    wp_enqueue_style('bootstrap-css', 'https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css');

    // 引入 Bootstrap JS 和 Popper.js (Bootstrap 的依賴)
    wp_enqueue_script('popper-js', 'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js', [], null, true);
    wp_enqueue_script('bootstrap-js', 'https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js', ['jquery', 'popper-js'], null, true);
}
add_action('wp_enqueue_scripts', 'enqueue_bootstrap_in_plugin');

//添加fido登入區塊
function gogo_add_button_to_head() {
    if ( is_front_page() ) {  // 檢查是否是首頁
        $page_id = get_option('gogo_custom_page_id');
        $page_link = get_permalink($page_id);  
        if ( is_user_logged_in() ) {
            // 用戶已經登錄，顯示用戶資訊
            $current_user = wp_get_current_user();
            echo '<div class="user-info">';
            echo '<p>歡迎，' . esc_html( $current_user->display_name ) . '</p>';
            echo '</div>';
        }else{
            echo '<div class="text-center my-4">';  // 使用 Bootstrap 的 text-center 和 my-4 類
            
            echo '<form id="gogo-email-form" action="' . esc_url($page_link) . '" method="post">'; // 添加 form 標籤

            // 增加一個 email 輸入框，使用 Bootstrap 的 form-control 和 mr-2 類
            echo '<input id="email" type="email" name="user_email" class="form-control d-inline-block mr-2" style="width: auto;" placeholder="Enter your email">';

            echo '<button type="button" onclick="doRegister()" class="btn btn-primary mr-2">Register</button>';
            echo '<button type="button" onclick="doLogin()"  class="btn btn-secondary">Login</button>';  // 修改為 button 元素並添加 name 和 value 屬性

            echo '</form>';
            
            echo '</div>';
        }
    }
}
add_action('theme_header', 'gogo_add_button_to_head');


