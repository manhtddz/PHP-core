<?php

require_once(dirname(__DIR__) . "/services/AdminService.php");
require_once(dirname(__DIR__) . "/controllers/BaseController.php");
require_once(dirname(__DIR__) . "/dto/AdminCreateRequest.php");
require_once(dirname(__DIR__) . "/dto/AdminUpdateRequest.php");
require_once(dirname(__DIR__) . "/dto/LoginRequest.php");
require_once(dirname(__DIR__) . "/dto/SearchRequest.php");

require_once(dirname(__DIR__) . "/exceptions/ValidationException.php");

// require_once '../dto/UserRequest.php';

class AdminController extends BaseController
{
    private $adminService;
    public function __construct()
    {
        $this->adminService = new AdminService();

    }
    public function index()
    {
        $this->checkLogin("adminIndex", "admin_id");

        $page = max(1, intval($_GET["page"] ?? 1));

        list($admins, $totalPages) = $this->adminService->getAllAdmins($page);
        $this->view("admins.index", [
            "admins" => $admins,
            "totalPages" => $totalPages,
            "page" => $page
        ]);
    }

    public function searchForm()
    {
        session_start();
        $admins = $_SESSION["searched_admins"] ?? []; // Lấy danh sách user từ session
        // print_r($users);
        unset($_SESSION["searched_admins"]); // Xóa session sau khi lấy để tránh hiển thị dữ liệu cũ khi refresh

        $this->view("admins.search", ["admins" => $admins]);
    }

    public function search()
    {
        session_start();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $req = new SearchRequest($_POST);
            try {
                $admins = $this->adminService->search($req);
                $_SESSION['searched_admins'] = $admins; // Lưu kết quả vào session
                header('Location: ?controller=admin&action=searchForm'); // Redirect để đảm bảo hiển thị đúng UI
                exit;
            } catch (Exception $e) {
                $_SESSION['error'] = $e->getMessage();
                header('Location: ?controller=admin&action=searchForm');
                exit;
            }
        }
    }

    public function login()
    {
        session_start();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $req = new LoginRequest($_POST);
            try {
                $admin = $this->adminService->login($req);
                if ($admin) {
                    $_SESSION['admin'] = $admin->getName();
                    $_SESSION['admin_id'] = $admin->getId();
                    header('Location: ?controller=admin');
                }

                exit;
            } catch (Exception $e) {
                $_SESSION['error'] = $e->getMessage();
                header('Location: ?action=adminIndex');

            }
        }
    }

    public function create()
    {
        session_start();

        $errors = $_SESSION['errors'] ?? [];
        $oldData = $_SESSION['oldData'] ?? [];

        unset($_SESSION['errors']);
        unset($_SESSION['oldData']);

        $this->view('admins.create', [
            'errors' => $errors,
            'oldData' => $oldData
        ]);

    }

    public function edit()
    {
        if (!isset($_GET["id"]) || empty($_GET['id']) || $_GET['id'] == "0") {
            header("Location: ?controller=admin");
            exit;
        }
        session_start();

        $id = $_GET["id"];
        $errors = $_SESSION['errors'] ?? [];
        // $oldData = $_SESSION['oldData'] ?? null;

        unset($_SESSION['errors']);
        // unset($_SESSION['oldData']);


        $admin = $this->adminService->getAdminById($id);
        if (!$admin) {
            // Nếu admin không tồn tại, chuyển hướng về danh sách
            header("Location: ?controller=admin");
            exit;
        }
        // if ($oldData) {
        //     $admin = (object) array_merge((array) $admin, $oldData);
        // }
        $this->view("admins.edit", [
            "admin" => $admin,
            "errors" => $errors
        ]);

    }
    public function createAdmin()
    {
        session_start(); // Bật session

        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (isset($_FILES["new_avatar"]) && $_FILES["new_avatar"]["size"] > 0) {
                    $fileName = time() . "_" . $_FILES["new_avatar"]["name"];
                    $_POST['avatar'] = $fileName;
                } else {
                    $_POST['avatar'] = '';
                }
                $_POST = array_map(function ($value) {
                    return htmlspecialchars(stripslashes(trim($value)));
                }, $_POST);

                $admin = new AdminCreateRequest($_POST);

                $this->adminService->createAdmin($admin); // Gọi service
                header("Location: ?controller=admin");
                // exit;
            }
        } catch (ValidationException $e) {
            $_SESSION['errors'] = $e->getErrors();
            $_SESSION['oldData'] = $_POST;

            header("Location: ?controller=admin&action=create");
            exit;
        }
    }
    public function updateAdmin()
    {
        session_start(); // Bật session

        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (isset($_FILES["new_avatar"]) && $_FILES["new_avatar"]["size"] > 0) {
                    $newFile = time() . "_" . $_FILES["new_avatar"]["name"];
                    $_POST['avatar'] = $newFile;
                } else {
                    $_POST['avatar'] = $_POST['current_avatar'];
                }
                $_POST = array_map(function ($value) {
                    return htmlspecialchars(stripslashes(trim($value)));
                }, $_POST);
                $id = $_POST['id'];
                $admin = new AdminUpdateRequest($_POST);// Tạo object từ request
                $this->adminService->updateAdmin($id, $admin);
                header("Location: ?controller=admin");
            }
        } catch (ValidationException $e) {
            $_SESSION['errors'] = $e->getErrors();
            $_SESSION['oldData'] = $_POST;

            header("Location: ?controller=admin&action=edit&id=" . $id);
            exit;
        }

    }
    public function deleteAdmin()
    {
        if (!isset($_GET["id"]) || empty($_GET['id']) || $_GET['id'] == "0") {
            $this->redirectError("ko có id");
            exit;
        }
        try {
            $id = $_GET["id"];
            $this->adminService->deleteAdmin($id);
            header("Location: ?controller=admin");
            exit;
        } catch (\Throwable $th) {
            $this->redirectError("admin ko tồn tại");
        }
    }
}
?>