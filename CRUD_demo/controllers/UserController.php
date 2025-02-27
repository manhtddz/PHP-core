<?php

require_once(dirname(__DIR__) . "/services/UserService.php");
require_once(dirname(__DIR__) . "/controllers/BaseController.php");
require_once(dirname(__DIR__) . "/dto/UserCreateRequest.php");
require_once(dirname(__DIR__) . "/dto/UserUpdateRequest.php");
require_once(dirname(__DIR__) . "/dto/LoginRequest.php");
require_once(dirname(__DIR__) . "/dto/SearchRequest.php");

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
        $this->checkLogin("adminIndex", "admin_id");

        $users = $this->userService->getAllUsers();
        // return $users;
        $this->view("users.index", [
            "users" => $users,
        ]);
    }
    public function searchForm()
    {
        session_start();
        $users = $_SESSION["searched_users"] ?? []; // Lấy danh sách user từ session
        // print_r($users);
        unset($_SESSION["searched_users"]); // Xóa session sau khi lấy để tránh hiển thị dữ liệu cũ khi refresh

        $this->view("users.search", ["users" => $users]);
    }

    public function search()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $req = new SearchRequest($_POST);
            try {
                session_start();
                $users = $this->userService->search($req);
                $_SESSION['searched_users'] = $users; // Lưu kết quả vào session
                header('Location: ?controller=user&action=searchForm'); // Redirect để đảm bảo hiển thị đúng UI
                exit;
            } catch (Exception $e) {
                $_SESSION['error'] = $e->getMessage();
                header('Location: ?controller=user&action=searchForm');
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
                $user = $this->userService->login($req);
                // echo $user->getName();
                if ($user) {
                    $_SESSION['user'] = $user->getName();
                    $_SESSION['user_id'] = $user->getId();
                    header("Location: ?controller=user&action=info");
                }
                exit;
            } catch (Exception $e) {
                $_SESSION['error'] = $e->getMessage();
                header('Location: ?');
            }
        }
    }

    public function logout()
    {
        session_start();

        session_destroy();
        header("Location: ?");

        exit();
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
        $this->checkLogin("index", "user_id");

        $id = $_SESSION["user_id"];

        $user = $this->userService->getUserById($id);

        $this->view("users.info", [
            "user" => $user
        ]);

    }
    public function createUser()
    {
        session_start();

        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $user = new UserCreateRequest(
                    $_POST
                );

                $this->userService->createUser($user); // Gọi service
                header("Location: ?controller=user");
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
                $user = new UserUpdateRequest(
                    $_POST
                );// Tạo object từ request
                $this->userService->updateUser($id, $user);
                header("Location: ?controller=user");
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