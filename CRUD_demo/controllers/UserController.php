<?php

require_once(dirname(__DIR__) . "/services/UserService.php");
require_once(dirname(__DIR__) . "/controllers/BaseController.php");
require_once(dirname(__DIR__) . "/dto/UserCreateRequest.php");
require_once(dirname(__DIR__) . "/dto/UserUpdateRequest.php");
require_once(dirname(__DIR__) . "/dto/LoginRequest.php");

require_once(dirname(__DIR__) . "/exceptions/ValidationException.php");

// require_once '../dto/UserRequest.php';

class UserController extends BaseController
{
    private $userService;
    public function __construct()
    {
        $this->userService = new UserService();
    }
    public function index()
    {
        $users = $this->userService->getAllUsers();
        // return $users;
        $this->view("users.index", [
            "users" => $users,
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
                $user = $this->userService->login($req);
                // echo $user->getName();
                setcookie("user", $user->getName());
                header('Location: ?controller=user');
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

        $this->view('users.create', [
            'errors' => $errors,
            'oldData' => $oldData
        ]);
    }

    public function edit()
    {
        if (!isset($_GET["id"]) || empty($_GET['id']) || $_GET['id'] == "0") {
            $this->redirectError("ko có id");
            exit;
        }
        session_start();

        $id = $_GET["id"];
        $errors = $_SESSION['errors'] ?? [];
        // $oldData = $_SESSION['oldData'] ?? null;

        unset($_SESSION['errors']);
        // unset($_SESSION['oldData']);


        $user = $this->userService->getUserById($id);
        if (!$user) {
            // Nếu user không tồn tại, chuyển hướng về danh sách
            $this->redirectError("user ko tồn tại");
            exit;
        }
        // if ($oldData) {
        //     $user = (object) array_merge((array) $user, $oldData);
        //     // ép user thành mảng, sau đó dồn data từ ở oldData vào user, sau đó lại ép kiểu toàn bộ data vừa xử lí thành obj
        // }
        $this->view("users.edit", [
            "user" => $user,
            "errors" => $errors
        ]);

    }
    public function info()
    {
        if (isset($_GET["id"]) && $_GET['id'] != "" && $_GET['id'] != "0") {
            $id = $_GET["id"];
            $user = $this->userService->getUserById($id);

            $this->view("users.info", [
                "user" => $user
            ]);
        }
    }
    public function createUser()
    {
        session_start(); // Bật session

        try {
            if (isset($_POST['add'])) {
                $user = new UserCreateRequest([
                    'name' => $_POST['name'],
                    'facebook_id' => $_POST['facebook_id'],
                    'password' => $_POST['password'],
                    'email' => $_POST['email'],
                    'avatar' => $_POST['avatar'],
                    'status' => 1, // Mặc định là 1 (active)
                    'ins_id' => $_POST['ins_id'],
                    // 'upd_id' => null, // Người cập nhật (giả định)
                    'ins_datetime' => date('Y-m-d H:i:s'), // Ngày tạo
                    // 'upd_datetime' => null, // Ngày cập nhật (null ban đầu)
                    'del_flag' => 0 // Không bị xóa
                ]);

                $this->userService->createUser($user); // Gọi service
                // header("Location: /users");
                // exit;
            }
        } catch (ValidationException $e) {
            $_SESSION['errors'] = $e->getErrors();
            $_SESSION['oldData'] = $_POST;

            header("Location: ?controller=user&action=create");
            exit;

        }
    }
    public function updateUser()
    {
        session_start(); // Bật session

        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $id = $_POST['id'];
                $user = new UserUpdateRequest([
                    'name' => $_POST['name'],
                    'facebook_id' => $_POST['facebook_id'],
                    'password' => $_POST['password'],
                    'email' => $_POST['email'],
                    'avatar' => $_POST['avatar'],
                    'status' => $_POST['status'], // Mặc định là 1 (active)
                    // 'ins_id' => $_POST['ins_id'],
                    'upd_id' => $_POST['upd_id'], // Người cập nhật (giả định)
                    // 'ins_datetime' => $_POST['ins_datetime'], // Ngày tạo
                    'upd_datetime' => date('Y-m-d H:i:s'), // Ngày cập nhật (null ban đầu)
                    'del_flag' => 0 // Không bị xóa
                ]);// Tạo object từ request
                $this->userService->updateUser($id, $user);

            }
        } catch (ValidationException $e) {
            $_SESSION['errors'] = $e->getErrors();
            $_SESSION['oldData'] = $_POST;

            header("Location: ?controller=user&action=edit&id=" . $id);
            exit;
        }

    }
    public function deleteUser()
    {
        if (!isset($_GET["id"]) || empty($_GET['id']) || $_GET['id'] == "0") {
            $this->redirectError("ko có id");
            exit;
        }
        try {
            $id = $_GET["id"];
            $this->userService->deleteUser($id);
            header("Location: ?controller=user");
            exit;
        } catch (\Throwable $th) {
            $this->redirectError("user ko tồn tại");
        }


    }

}
?>