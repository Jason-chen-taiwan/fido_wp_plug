<?php
// 在插件激活時運行此函數
class Activation {
    public static function activate() {
        // 首先檢查是否已存在這樣的頁面，以避免重複創建
        $existing_page = get_page_by_title('My Custom Page');
        if (!$existing_page) {
            // 插入一個新頁面
            $page_id = wp_insert_post(
                array(
                    'post_title'    => 'My Custom Page',
                    'post_content'  => 'This is the content of my custom page.',
                    'post_status'   => 'publish',
                    'post_author'   => 1, // 或其他用戶ID
                    'post_type'     => 'page',
                    'comment_status' => 'closed'
                )
            );
            // 將新創建的頁面ID保存為一個選項
            update_option('gogo_custom_page_id', $page_id);
        }
    }
}
