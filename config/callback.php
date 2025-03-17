<?php
session_start();
require_once(dirname(__DIR__) . "/vendor/autoload.php");
require_once(dirname(__DIR__) . "/dto/FacebookUserCreateRequest.php");

$fb = new Facebook\Facebook([
  'app_id' => '1366387374603190',
  'app_secret' => '92ccdcecdcde015c619155937e08bf89',
  'default_graph_version' => 'v22.0',
]);

$helper = $fb->getRedirectLoginHelper();

try {
  $accessToken = $helper->getAccessToken();
} catch (Facebook\Exceptions\FacebookResponseException $e) {
  echo 'Lỗi Graph: ' . $e->getMessage();
  exit;
} catch (Facebook\Exceptions\FacebookSDKException $e) {
  echo 'Lỗi SDK: ' . $e->getMessage();
  exit;
}
if (!isset($accessToken)) {
  if ($helper->getError()) {
    echo "Lỗi Facebook: " . $helper->getError();
    echo "Mô tả: " . $helper->getErrorDescription();
    echo "Mã lỗi: " . $helper->getErrorCode();
  } else {
    echo "Lỗi không xác định!";
  }
  exit;
}

// Lưu token vào session
$_SESSION['facebook_access_token'] = (string) $accessToken;

// Lấy thông tin người dùng
try {
  $response = $fb->get('/me?fields=id,name,email', $accessToken);
  $user = $response->getGraphUser(); // Dùng getDecodedBody() để lấy dữ liệu dạng mảng

  echo 'Xin chào, ' . $user['name'];
} catch (Facebook\Exceptions\FacebookResponseException $e) {
  echo 'Lỗi Graph: ' . $e->getMessage();
} catch (Facebook\Exceptions\FacebookSDKException $e) {
  echo 'Lỗi SDK: ' . $e->getMessage();
}
$_SESSION['facebook_data'] = [
  'facebook_id' => $user->getId(),
  'name' => $user->getName(),
  'email' => 'truongducmanh07092004@gmail.com',
  'status' => 1,
  'ins_id' => 1
];
var_dump($_SESSION['facebook_data']);
// exit;
header("Location: /CRUD_demo/?action=loginFacebook");
?>