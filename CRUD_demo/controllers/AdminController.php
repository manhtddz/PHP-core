<?php

require_once(dirname(__DIR__) . "/services/AdminService.php");
require_once(dirname(__DIR__) . "/controllers/BaseController.php");
require_once(dirname(__DIR__) . "/dto/AdminCreateRequest.php");
require_once(dirname(__DIR__) . "/dto/AdminUpdateRequest.php");
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
        $admins = $this->adminService->getAllAdmins();
        // return $users;
        $this->view("admins.index", [
            "admins" => $admins,
        ]);
    }
    public function login()
    {
        session_start();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $req = new LoginRequest([
                'password' => $_POST['password'],
                'email' => $_POST['email'],
            ]);
            try {
                $admin = $this->adminService->login($req);
                // echo $user->getName();
                setcookie("admin", $admin->getName());
                header('Location: ?controller=admin');
                exit;
            } catch (Exception $e) {
                $_SESSION['error'] = $e->getMessage();
                header('Location: ?');

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
        $oldData = $_SESSION['oldData'] ?? null;

        unset($_SESSION['errors']);
        unset($_SESSION['oldData']);


        $admin = $this->adminService->getAdminById($id);
        if (!$admin) {
            // Nếu admin không tồn tại, chuyển hướng về danh sách
            header("Location: ?controller=admin");
            exit;
        }
        if ($oldData) {
            $admin = (object) array_merge((array) $admin, $oldData);
        }
        $this->view("admins.edit", [
            "admin" => $admin,
            "errors" => $errors
        ]);

    }
    public function info()
    {
        if (isset($_GET["id"]) && $_GET['id'] != "" && $_GET['id'] != "0") {
            $id = $_GET["id"];
            $admin = $this->adminService->getAdminById($id);

            $this->view("admins.info", [
                "admin" => $admin
            ]);
        }
    }
    public function createAdmin()
    {
        session_start(); // Bật session

        try {
            if (isset($_POST['add'])) {
                $admin = new AdminCreateRequest([
                    'name' => $_POST['name'],
                    'password' => $_POST['password'],
                    'email' => $_POST['email'],
                    'avatar' => $_POST['avatar'],
                    'role_type' => 1, // Mặc định là 1 (admin)
                    'ins_id' => $_POST['ins_id'],
                    // 'upd_id' => null, // Người cập nhật (giả định)
                    'ins_datetime' => date('Y-m-d H:i:s'), // Ngày tạo
                    // 'upd_datetime' => null, // Ngày cập nhật (null ban đầu)
                    'del_flag' => 0 // Không bị xóa
                ]);

                $this->adminService->createAdmin($admin); // Gọi service
                // header("Location: /users");
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
                $id = $_POST['id'];
                $admin = new AdminUpdateRequest([
                    'name' => $_POST['name'],
                    'password' => $_POST['password'],
                    'email' => $_POST['email'],
                    'avatar' => $_POST['avatar'],
                    'role_type' => $_POST['role_type'],
                    // 'ins_id' => $_POST['ins_id'],
                    'upd_id' => $_POST['upd_id'],
                    // 'ins_datetime' => $_POST['ins_datetime'], // Ngày tạo
                    'upd_datetime' => date('Y-m-d H:i:s'), // Ngày cập nhật (null ban đầu)
                    'del_flag' => 0 // Không bị xóa
                ]);// Tạo object từ request
                $this->adminService->updateAdmin($id, $admin);

            }
        } catch (ValidationException $e) {
            $_SESSION['errors'] = $e->getErrors();
            $_SESSION['oldData'] = $_POST;

            header("Location: ?controller=admin&action=edit&id=" . $id);
            exit;
        }

    }
    public function deleteAdmin($id)
    {
        $this->adminService->deleteAdmin($id);
        header("Location: /admins");
        exit;
    }

}
?>