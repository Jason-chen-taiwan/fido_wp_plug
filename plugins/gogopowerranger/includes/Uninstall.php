<?php

//這邊是非常重要，是不是因為點擊解除安裝而執行的，不然你等同於裝了一個自爆裝置
if (!defined('WP_UNINSTALL_PLUGIN')) {
    wp_die(sprintf(
        __('%s should only be called when uninstalling the plugin.', 'GOGOPOWERRANGER'),
        __FILE__
    ));
    exit;
}
// 在此執行解除安裝命令