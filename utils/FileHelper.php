<?php
class FileHelper
{
    private static $instance = null;

    public function __construct()
    {
    }
    // public static function getInstance()
    // {
    //     if (self::$instance === null) {
    //         self::$instance = new FileHelper();
    //     }
    //     return self::$instance;
    // }

    // public function uploadFile($newFileName)
    // {
    //     if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    //         return false;
    //     }

    //     // Kiểm tra nếu không có file nào được tải lên
    //     if (!isset($_FILES["avatar"]) || $_FILES["avatar"]["size"] <= 0) {
    //         return false;
    //     }

    //     // Định nghĩa thư mục lưu file
    //     $target_dir = __DIR__ . "/../uploads/images/avatar/";
    //     $target_file = $target_dir . $newFileName;

    //     // Kiểm tra kích thước file (giới hạn 5MB)
    //     if ($_FILES["avatar"]["size"] > 5000000) {
    //         return false;
    //     }

    //     // Chỉ cho phép các loại file cụ thể
    //     $allowed_types = ["jpg", "png"];
    //     $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    //     if (!in_array($fileType, $allowed_types)) {
    //         return false;
    //     }

    //     // Nếu mọi thứ OK, tiến hành upload
    //     if (move_uploaded_file($_FILES["avatar"]["tmp_name"], $target_file)) {
    //         return true;
    //     }

    //     return false;
    // }

    public function uploadFile($newFileName, $file, $uploadDir)
    {
        return move_uploaded_file($file["tmp_name"], $uploadDir . $newFileName);
    }
    public function deleteFile($fileName, $uploadDir)
    {

        // if (!empty($_POST['current_avatar']) && file_exists($uploadDir . $fileName)) {
        return unlink($uploadDir . $fileName);
        // }
    }
    public function isImageFile(string $fileName)
    {
        $allowed_types = ["jpg", "png"];

        $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if (!in_array($fileType, $allowed_types)) {
            return false;
        }
        return true;
    }
}

// Tạo kết nối bằng PDO

?>