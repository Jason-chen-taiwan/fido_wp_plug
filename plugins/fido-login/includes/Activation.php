<?php
// 在插件激活時運行此函數
class Activation {
    public static function activate() {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        $table_name = $wpdb->prefix . 'fido_register_challenge';
        

    }
}
