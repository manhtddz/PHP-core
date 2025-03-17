<?php
session_start();

require_once(dirname(__DIR__) . "/vendor/autoload.php");
// require_once 'path/to/Facebook/autoload.php'; // Nếu không dùng Composer

$fb = new Facebook\Facebook([
    'app_id' => '1366387374603190', // Thay bằng App ID của bạn
    'app_secret' => '92ccdcecdcde015c619155937e08bf89', // Thay bằng App Secret
    'default_graph_version' => 'v22.0',
]);

$helper = $fb->getRedirectLoginHelper();

$permissions = ['email', 'public_profile']; // Thêm public_profile
$loginUrl = $helper->getLoginUrl('http://localhost/CRUD_demo/config/callback.php', $permissions);

echo '<a href="' . htmlspecialchars($loginUrl) . '">Đăng nhập bằng Facebook</a>';
?>