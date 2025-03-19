<?php

require_once(dirname(__DIR__) . "/services/UserService.php");
require_once(dirname(__DIR__) . "/controllers/BaseController.php");
require_once(dirname(__DIR__) . "/dto/UserCreateRequest.php");
require_once(dirname(__DIR__) . "/dto/UserUpdateRequest.php");
require_once(dirname(__DIR__) . "/dto/LoginRequest.php");
require_once(dirname(__DIR__) . "/dto/SearchRequest.php");
require_once(dirname(__DIR__) . "/exceptions/ValidationException.php");
require_once(dirname(__DIR__) . "/vendor/autoload.php");
require_once(dirname(__DIR__) . "/utils/FileHelper.php");

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
        $this->checkLogin(['1', '2']);
        // Lấy giá trị từ GET
        $name = isset($_GET['name']) ? $this->cleanOneData($_GET['name']) : '';
        $email = isset($_GET['email']) ? $this->cleanOneData($_GET['email']) : '';
        $results = [];

        // if (!empty($name) || !empty($email)) {
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

    public function search()
    {
        $this->checkLogin(['1', '2']);
        // Lấy giá trị từ GET
        $name = isset($_GET['name']) ? $this->cleanOneData($_GET['name']) : '';
        $email = isset($_GET['email']) ? $this->cleanOneData($_GET['email']) : '';
        $results = [];

        // if (!empty($name) || !empty($email)) {
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
        // }
        // $this->view("users.search");
        // exit();
    }
    public function create()
    {
        $this->checkLogin(['1', '2']);
        $this->view('users.create', [
            'errors' => $_SESSION['errors'] ?? [],
            'oldData' => $_SESSION['oldData'] ?? []
        ]);
        unset($_SESSION['errors'], $_SESSION['oldData']);
    }

    public function edit()
    {
        $this->checkLogin(['1', '2']);
        try {
            $id = $_GET["id"] ?? "0";
            if (empty($id) || $id == "0" || !filter_var($id, FILTER_VALIDATE_INT))
                $this->redirectWithError("?controller=user", "Not valid ID");

            $user = $this->userService->getUserById($id);

            $this->view("users.edit", [
                "user" => $user,
                "errors" => $_SESSION['errors'] ?? [],
                "oldEditData" => $_SESSION['oldEditData'] ?? []
            ]);
            unset($_SESSION['errors'], $_SESSION['oldEditData']);
        } catch (Exception $e) {
            $this->redirectWithError("?controller=user", $e->getMessage());
        }
    }
    public function info()
    {
        $id = $_SESSION["auth"]['id'];

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

                $tempDir = __DIR__ . "/../uploads/images/temp/";
                $this->storeOldImage($_FILES["new_avatar"],$_POST['tempFileName'],$tempDir);

                $this->userService->createUser(new UserCreateRequest($_POST));
                $_SESSION['success'] = "Create successful!";
                unset($_SESSION['temp_avatar']);
                header("Location: ?controller=user");
                exit;
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
                exit;
            } catch (ValidationException $e) {
                $_SESSION['errors'] = $e->getErrors();
                $_SESSION['oldEditData'] = $_POST;
                $this->redirectWithError("?controller=user&action=edit&id=$id");
            }
        }
    }

    public function deleteUser()
    {
        $id = $_GET["id"] ?? "0";
        if (empty($id) || $id == "0" || !filter_var($id, FILTER_VALIDATE_INT))
            $this->redirectWithError("?controller=user", "Not valid ID");

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