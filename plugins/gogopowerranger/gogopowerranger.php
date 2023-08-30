<?php

/**
 * Plugin Name:       GOGOPOWERRANGER
 * Plugin URI:        https://GOGOPOWERRANGER.com/plugins/the-basics/
 * Description:       Handle the basics with this plugin.
 * Version:           1.10.3
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Rach Chen
 * Author URI:        https://author.GOGOPOWERRANGER.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       GOGOPOWERRANGER
 * Domain Path:       /languages
 */
// 載入其他的PHP文件
require_once 'init_hook.php';
register_activation_hook(plugin_dir_path(__DIR__) . 'gogopowerranger/gogopowerranger.php', function() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/Activation.php';
    Activation::activate();
});



add_action('admin_menu', 'gogo_create_menu');

function gogo_create_menu() {
    // 建立主選單
    add_menu_page(
        'GoGopowerranger Settings Page',
        'GoGopowerranger Setting',
        'manage_options',
        'gogGopowerranger-options',
        'GoGopowerranger_setting_page',
        'dashicons-smiley',
        99
    );
    add_submenu_page(
        'gogGopowerranger-options',
        'overview',
        'overview',
        'manage_options',
        'gogGopowerranger-options',
        'GoGopowerranger_setting_page'
    );
    add_submenu_page(
        'gogGopowerranger-options',
        'About ranger',
        'About',
        'manage_options',
        'ranger-about',
        'ranger_about_page'

    );
    add_submenu_page(
        'gogGopowerranger-options',
        'Help ranger',
        'Help',
        'manage_options',
        'ranger-help',
        'ranger_help_page'
    );
}
function GoGopowerranger_setting_page() {
?>
    <h1> 走走力量遊俠阿斯批零</h1>
<?php
}
function ranger_about_page() {
    echo "About 遊俠";
}
function ranger_help_page() {
    echo "Help Ranger";
}

// 設定Setting頁面
function wporg_settings_init() {
    // register a new setting for "reading" page
    register_setting('reading', 'wporg_setting_name');
    register_setting('reading', 'gogopowerranger_setting');

    // register a new section in the "reading" page
    add_settings_section(
        'wporg_settings_section',
        'WPOrg Settings Section',
        'wporg_settings_section_callback',
        'reading'
    );

    // register a new field in the "wporg_settings_section" section, inside the "reading" page
    add_settings_field(
        'wporg_settings_field',
        'WPOrg Setting',
        'wporg_settings_field_callback',
        'reading',
        'wporg_settings_section',
        array('label_for' => 'wporg_setting_name')
    );


    // register a new field in the "wporg_settings_section" section, inside the "reading" page
    add_settings_field(
        'wporg_settings_field2',
        'gogopowerranger_setting',
        'wporg_settings_field_callback',
        'reading',
        'wporg_settings_section',
        array('label_for' => 'gogopowerranger_setting')
    );
}

function wporg_settings_section_callback2() {
    echo '<p>wporg_settings_section_callback2 Testing function for Introduction.</p>';
}


/**
 * Custom option and settings:
 *  - callback functions
 */


/**
 * Developers section callback function.
 *
 * @param array $args  The settings array, defining title, id, callback.
 */
function wporg_section_developers_callback($args) {
?>
    <p id="<?php echo esc_attr($args['id']); ?>"><?php esc_html_e('Follow the white rabbit.', 'wporg'); ?></p>
<?php
}

/**
 * Pill field callbakc function.
 *
 * WordPress has magic interaction with the following keys: label_for, class.
 * - the "label_for" key value is used for the "for" attribute of the <label>.
 * - the "class" key value is used for the "class" attribute of the <tr> containing the field.
 * Note: you can add custom key value pairs to be used inside your callbacks.
 *
 * @param array $args
 */
function wporg_field_pill_cb($args) {
    // Get the value of the setting we've registered with register_setting()
    $options = get_option('wporg_options');
?>
    <select id="<?php echo esc_attr($args['label_for']); ?>" data-custom="<?php echo esc_attr($args['wporg_custom_data']); ?>" name="wporg_options[<?php echo esc_attr($args['label_for']); ?>]">
        <option value="red" <?php echo isset($options[$args['label_for']]) ? (selected($options[$args['label_for']], 'red', false)) : (''); ?>>
            <?php esc_html_e('red pill', 'wporg'); ?>
        </option>
        <option value="blue" <?php echo isset($options[$args['label_for']]) ? (selected($options[$args['label_for']], 'blue', false)) : (''); ?>>
            <?php esc_html_e('blue pill', 'wporg'); ?>
        </option>
    </select>
    <p class="description">
        <?php esc_html_e('You take the blue pill and the story ends. You wake in your bed and you believe whatever you want to believe.', 'wporg'); ?>
    </p>
    <p class="description">
        <?php esc_html_e('You take the red pill and you stay in Wonderland and I show you how deep the rabbit-hole goes.', 'wporg'); ?>
    </p>
<?php
}

/**
 * Add the top level menu page.
 */
function wporg_options_page() {
    add_menu_page(
        'WPOrg',
        'WPOrg Options',
        'manage_options',
        'wporg',
        'wporg_options_page_html'
    );
}


/**
 * Register our wporg_options_page to the admin_menu action hook.
 */
add_action('admin_menu', 'wporg_options_page');


/**
 * Top level menu callback function
 */
function wporg_options_page_html() {
    // check user capabilities
    if (!current_user_can('manage_options')) {
        return;
    }

    // add error/update messages

    // check if the user have submitted the settings
    // WordPress will add the "settings-updated" $_GET parameter to the url
    if (isset($_GET['settings-updated'])) {
        // add settings saved message with the class of "updated"
        add_settings_error('wporg_messages', 'wporg_message', __('Settings Saved', 'wporg'), 'updated');
    }

    // show error/update messages
    settings_errors('wporg_messages');
?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <form action="options.php" method="post">
            <?php
            // output security fields for the registered setting "wporg"
            settings_fields('wporg');
            // output setting sections and their fields
            // (sections are registered for "wporg", each field is registered to a specific section)
            do_settings_sections('wporg');
            // output save settings button
            submit_button('Save Settings');
            ?>
        </form>
    </div>
<?php
}

//customer




//customer
/**
 * register wporg_settings_init to the admin_init action hook
 */
add_action('admin_init', 'wporg_settings_init');

/**
 * callback functions
 */

// section content cb
function wporg_settings_section_callback() {
    echo '<p>WPOrg Section Introduction.</p>';
}


function wporg_settings_field_callback($args) {
    // get the value of the setting we've registered with register_setting()
    $setting = get_option($args['label_for']); // 使用 $args['label_for'] 來獲取正確的設定名稱

    // output the field
    echo '<input type="text" name="' . esc_attr($args['label_for']) . '" value="' . esc_attr($setting) . '">';
}


add_action('admin_menu', 'GoGopowerranger_setting_page_init');

//add custom setting page and test by my self
function GoGopowerranger_setting_page_init() {
    add_submenu_page(
        'options-general.php',  // Parent slug
        'My Custom Submenu Page', // Page title
        'GoGopowerranger_setting_page', // Menu title
        'manage_options', // Capability
        'my_custom_submenu', // Menu slug
        'my_custom_submenu_page_callback' // Callback function
    );
    register_setting('my_custom_options_group', 'gogopowerranger_setting_customer');
    register_setting('my_custom_options_group', 'gogopowerranger_setting_customer_two', array('sanitize_callback' => 'gogopoweranger_field_santize_callback'));
    add_settings_section(
        'gogopowerranger_section',
        'gogopoweranger_section',
        'gogopoweranger_test_callback',
        'my_custom_submenu'
    );
    add_settings_field(
        'gogopowerranger_field',
        'My Custom Field Field',
        'my_custom_field_callback',
        'my_custom_submenu',
        'gogopowerranger_section',
        array('label_for' => 'gogopowerranger_setting_customer')
    );
    add_settings_field(
        'gogopowerranger_field2',
        'gogopweranger test2',
        'gogopoweranger_test_callback_two',
        'my_custom_submenu',
        'gogopowerranger_section',
        array('label_for' => 'gogopowerranger_setting_customer_two')
    );
}

function gogopoweranger_field_santize_callback($input) {
    // 檢查輸入值是否為數字
    if (is_numeric($input)) {
        return $input; // 如果是數字，則返回輸入值
    } else {
        // 如果不是數字，你可以返回一個預設值，或者添加一個錯誤訊息
        add_settings_error(
            'gogopowerranger_setting_customer_two',
            'gogopowerranger_number_error',
            'Error: The value entered was not a number. Please enter a numeric value.',
            'error'
        );
        return false; // 或返回預設值
    }
}

function my_custom_submenu_page_callback() {
    echo '<h2>My Custom Submenu Page</h2>';
    echo '<form method="post" action="options.php">';
    settings_fields('my_custom_options_group');
    do_settings_sections('my_custom_submenu');
    submit_button();
    echo '</form>';
}

function gogopoweranger_test_callback() {
    echo 'Description and details about the section';
}

function my_custom_field_callback() {
    $option = get_option('gogopowerranger_setting_customer');
    echo "<input type='text' name='gogopowerranger_setting_customer' value='{$option}'>";
}

function gogopoweranger_test_callback_two() {
    $option = get_option('gogopowerranger_setting_customer_two');
    echo "<input type='text' name='gogopowerranger_setting_customer_two' value='{$option}'>";
}

//admin notice
add_action('admin_notices', 'show_current_user_notice');

function show_current_user_notice() {
    // Get the current user
    $current_user = wp_get_current_user();

    // Check if user is logged in
    if ($current_user->exists()) {
        // Display the notice
        echo '<div class="notice notice-info is-dismissible">';
        echo '<p>Logged in as: ' . esc_html($current_user->display_name) . '</p>';
        echo '</div>';
    }
}


//nonce practice 
add_action('admin_menu', 'gogo_power_ranger_nonce_example_menu');
add_action('admin_init', 'gogo_power_ranger_nonce_example_verify');

function gogo_power_ranger_nonce_example_menu() {
    add_menu_page(
        'Nonce Example',
        'Nonce Example',
        'manage_options',
        'gogo_power_ranger-nonce-example',
        'gogo_power_ranger_nonce_example_template' //callback
    );
}

function gogo_power_ranger_nonce_example_verify() {

    if (!isset($_POST['gogo_power_ranger_nonce_name'])) return;

    // 驗證失敗就掛點
    if (!wp_verify_nonce($_POST['gogo_power_ranger_nonce_name'], 'gogo_power_ranger_nonce_action')) wp_die('Your nonce could not be verified.');

    // 驗證通過，要來將我們的資料原凈化（sanitzed）了
    if (isset($_POST['gogo_power_ranger_nonce_example'])) {
        // 你這邊也可以用preg做正規表達式
        update_option(
            'gogo_power_ranger_nonce_example',
            sanitize_text_field($_POST['gogo_power_ranger_nonce_example'])
        );
    }
}

function gogo_power_ranger_nonce_example_template() { ?>
    <div class="wrap">
        <h1>Nonce </h1>
        <h2>Verified Under Nonce</h2>
        <?php $value = get_option('gogo_power_ranger_nonce_example'); ?>
        <form method="post" action="">
            <?php wp_nonce_field('gogo_power_ranger_nonce_action', 'gogo_power_ranger_nonce_name'); ?>
            <p>
                <label>
                    Enter your name:
                    <input type="text" name="gogo_power_ranger_nonce_example" value="<?php echo esc_attr($value); ?>" />
                </label>
            </p>
            <?php submit_button('Submit', 'primary'); ?>
        </form>
    </div>
<?php
}


//action hook wp_footer 
add_action('wp_footer', 'footer_bar', 10);

function footer_bar() {
    echo 'This Page is powered by GOGO POWER RANGER.';
}
function gogo_add_login_form() {
    if ( ! is_user_logged_in() ) {
        echo '<div class="login-section">';
        wp_login_form( array('echo' => true) );
        echo '</div>';
    } else {
        echo 'Hello, ' . wp_get_current_user()->display_name;
        echo '<a href="' . wp_logout_url(home_url()) . '">Logout</a>';
    }
}
add_action('wp_head', 'gogo_add_login_form');


function gogo_add_button_to_head() {
    if ( is_front_page() ) {  // 檢查是否是首頁
        $page_id = get_option('gogo_custom_page_id');
        $page_link = get_permalink($page_id);  
        echo $page_id;
        echo $page_link;
        echo '<div style="text-align:center; margin: 20px 0;">';  
        echo '<a href="' . esc_url($page_link) . '" class="my-custom-button">Click Me</a>';  
        echo '</div>';
    }
}
add_action('wp_body_open', 'gogo_add_button_to_head');

function gogo_enqueue_custom_styles() {
    if ( is_front_page() ) {
        echo '
        <style>
            .my-custom-button {
                padding: 10px 20px;
                background-color: #0073aa;
                color: #fff;
                text-decoration: none;
                border-radius: 4px;
                transition: background-color 0.3s;
            }
            .my-custom-button:hover {
                background-color: #005177;
            }
        </style>';
    }
}
add_action('wp_head', 'gogo_enqueue_custom_styles');

