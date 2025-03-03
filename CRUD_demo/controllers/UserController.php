<?php

require_once(dirname(__DIR__) . "/services/UserService.php");
require_once(dirname(__DIR__) . "/controllers/BaseController.php");
require_once(dirname(__DIR__) . "/dto/UserCreateRequest.php");
require_once(dirname(__DIR__) . "/dto/UserUpdateRequest.php");
require_once(dirname(__DIR__) . "/dto/LoginRequest.php");
require_once(dirname(__DIR__) . "/dto/SearchRequest.php");
require_once(dirname(__DIR__) . "/exceptions/ValidationException.php");

class UserController extends BaseController
{
    private $userService;
    public function __construct()
    {
        parent::__construct();
        $this->userService = new UserService();
    }

    public function index()
    {
        $this->checkLogin("adminIndex", "admin_id", "adminIndex");
        $page = max(1, intval($_GET["page"] ?? 1));
        $sort = $_GET['sort'] ?? 'desc';
        $newSort = $sort === 'asc' ? 'desc' : 'asc';
        $orderBy = $sort === 'asc' ? true : false;
        list($users, $totalPages) = $this->userService->getAllUsers($page, $orderBy);
        $this->view("users.index", compact("users", "totalPages", "page", "sort", "newSort"));
    }

    public function search()
    {
        // Lấy giá trị từ GET
        $name = isset($_GET['name']) ? $this->cleanOneData($_GET['name']) : '';
        $email = isset($_GET['email']) ? $this->cleanOneData($_GET['email']) : '';
        $results = [];

        // Nếu có dữ liệu nhập vào, thực hiện tìm kiếm
        if (!empty($name) || !empty($email)) {
            $searchParams = [];
            if (!empty($name))
                $searchParams['name'] = $name;
            if (!empty($email))
                $searchParams['email'] = $email;
            $page = max(1, intval($_GET["page"] ?? 1));
            $sort = $_GET['sort'] ?? 'desc';
            $newSort = $sort === 'asc' ? 'desc' : 'asc';
            $orderBy = $sort === 'asc' ? true : false;
            list($users, $totalPages) = $this->userService->search(new SearchRequest($searchParams), $page, $orderBy);
            $this->view("users.search", compact("users", "totalPages", "page", "name", "email", "sort", "newSort"));
            exit();
        }
        $this->view("users.search");
        exit();
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = [];
            if (empty($_POST['email']))
                $errors['emailError'] = 'Email can not be blank';
            if (empty($_POST['password']))
                $errors['passwordError'] = 'Password can not be blank';
            if (!empty($errors)) {
                $this->redirectWithErrors('?', $errors);
                exit;
            }
            try {
                $_POST = $this->cleanInputData($_POST);
                $req = new LoginRequest($_POST);
                $user = $this->userService->login($req);
                if ($user) {
                    $_SESSION['user'] = $user->getName();
                    $_SESSION['user_id'] = $user->getId();
                    header('Location: ?controller=user&action=info');
                    exit;
                }
            } catch (Exception $e) {
                $this->redirectWithError('?', $e->getMessage());
            }
        }
    }

    public function logout()
    {
        session_destroy();
        header("Location: ?");
        exit;
    }

    public function create()
    {
        $this->view('users.create', [
            'errors' => $_SESSION['errors'] ?? [],
            'oldData' => $_SESSION['oldData'] ?? []
        ]);
        unset($_SESSION['errors'], $_SESSION['oldData']);
    }

    public function edit()
    {
        try {

            $id = $_GET["id"] ?? "0";
            if (empty($id) || $id == "0" || !filter_var($id, FILTER_VALIDATE_INT))
                $this->redirectWithError("?controller=admin", "Không có ID hợp lệ");

            $user = $this->userService->getUserById($id);

            $this->view("users.edit", [
                "user" => $user,
                "errors" => $_SESSION['errors'] ?? []
            ]);
            unset($_SESSION['errors']);
        } catch (Exception $e) {
            $this->redirectWithError("?controller=user", $e->getMessage());
        }
    }
    public function info()
    {
        $this->checkLogin("index", "user_id", "index" ?? "");

        $id = $_SESSION["user_id"];

        $user = $this->userService->getUserById($id);

        $this->view("users.info", [
            "user" => $user
        ]);

    }

    public function createUser()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $_POST = $this->cleanInputData($_POST);
                $_POST['avatar'] = isset($_FILES["new_avatar"]) && $_FILES["new_avatar"]["size"] > 0
                    ? time() . "_" . $_FILES["new_avatar"]["name"]
                    : '';
                $this->userService->createUser(new UserCreateRequest($_POST));
                $_SESSION['success'] = "Create successful!";

                header("Location: ?controller=user");
            } catch (ValidationException $e) {
                $_SESSION['errors'] = $e->getErrors();
                $_SESSION['oldData'] = $_POST;
                $this->redirectWithError("?controller=user&action=create");
            }
        }
    }

    public function updateUser()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $_POST = $this->cleanInputData($_POST);
                $_POST['avatar'] = isset($_FILES["new_avatar"]) && $_FILES["new_avatar"]["size"] > 0
                    ? time() . "_" . $_FILES["new_avatar"]["name"]
                    : $_POST['current_avatar'];
                $id = $_POST['id'];
                $this->userService->updateUser($id, new UserUpdateRequest($_POST));
                $_SESSION['success'] = "Update successful!";

                header("Location: ?controller=user");
            } catch (ValidationException $e) {
                $_SESSION['errors'] = $e->getErrors();
                $_SESSION['oldData'] = $_POST;
                $this->redirectWithError("?controller=user&action=edit&id=$id");
            }
        }
    }

    public function deleteUser()
    {
        $id = $_GET["id"] ?? "0";
        if (empty($id) || $id == "0" || !filter_var($id, FILTER_VALIDATE_INT))
            $this->redirectWithError("?controller=user", "Không có ID hợp lệ");

        try {
            $this->userService->deleteUser($id);
            $_SESSION['success'] = "Delete successful!";

            header("Location: ?controller=user");
            exit;
        } catch (Exception $e) {
            $this->redirectWithError("?controller=user", $e->getMessage());
        }
    }
}
?>