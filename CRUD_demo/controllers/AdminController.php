<?php

require_once(dirname(__DIR__) . "/services/AdminService.php");
require_once(dirname(__DIR__) . "/controllers/BaseController.php");
require_once(dirname(__DIR__) . "/dto/AdminCreateRequest.php");
require_once(dirname(__DIR__) . "/dto/AdminUpdateRequest.php");
require_once(dirname(__DIR__) . "/dto/LoginRequest.php");
require_once(dirname(__DIR__) . "/dto/SearchRequest.php");
require_once(dirname(__DIR__) . "/exceptions/ValidationException.php");

class AdminController extends BaseController
{
    private $adminService;
    public function __construct()
    {
        parent::__construct();
        $this->adminService = new AdminService();
    }

    public function index()
    {
        $this->checkLogin('admin');
        $page = max(1, intval($_GET["page"] ?? 1));
        $sort = $_GET['sort'] ?? 'desc';
        $newSort = $sort === 'asc' ? 'desc' : 'asc';
        $orderBy = $sort === 'asc' ? true : false;
        list($admins, $totalPages) = $this->adminService->getAllAdmins($page, $orderBy);
        $this->view("admins.index", compact("admins", "totalPages", "page", "sort", "newSort"));
    }

    public function search()
    {
        $this->checkLogin('admin');
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

            list($admins, $totalPages) = $this->adminService->search(new SearchRequest($searchParams), $page, $orderBy);
            $this->view("admins.search", compact("admins", "totalPages", "page", "name", "email", "sort", "newSort"));
            exit();
        }
        $this->view("admins.search");
        exit();
    }

    // public function login()
    // {
    //     if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //         $errors = [];
    //         if (empty($_POST['email']))
    //             $errors['emailError'] = 'Email can not be blank';
    //         if (empty($_POST['password']))
    //             $errors['passwordError'] = 'Password can not be blank';
    //         if (!empty($errors)) {
    //             $this->redirectWithErrors('?action=adminIndex', $errors);
    //             exit;
    //         }
    //         try {
    //             $_POST = $this->cleanInputData($_POST);
    //             $req = new LoginRequest($_POST);
    //             $admin = $this->adminService->login($req);
    //             if ($admin) {
    //                 $_SESSION['admin'] = $admin->getName();
    //                 $_SESSION['admin_id'] = $admin->getId();
    //                 header('Location: ?controller=admin');
    //                 exit;
    //             }
    //         } catch (Exception $e) {
    //             $this->redirectWithError('?action=adminIndex', $e->getMessage());
    //         }
    //     }
    // }

    public function create()
    {
        $this->checkLogin('admin');
        $this->view('admins.create', [
            'errors' => $_SESSION['errors'] ?? [],
            'oldData' => $_SESSION['oldData'] ?? []
        ]);
        unset($_SESSION['errors'], $_SESSION['oldData']);
    }

    public function edit()
    {
        $this->checkLogin('admin');
        try {
            $id = $_GET["id"] ?? "0";
            if (empty($id) || $id == "0" || !filter_var($id, FILTER_VALIDATE_INT))
                $this->redirectWithError("?controller=admin", "Not valid ID");

            $admin = $this->adminService->getAdminById($id);

            $this->view("admins.edit", [
                "admin" => $admin,
                "errors" => $_SESSION['errors'] ?? []
            ]);
            unset($_SESSION['errors']);
        } catch (Exception $e) {
            $this->redirectWithError("?controller=admin", $e->getMessage());
        }

    }

    public function createAdmin()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $_POST = $this->cleanInputData($_POST);
                $_POST['avatar'] = isset($_FILES["new_avatar"]) && $_FILES["new_avatar"]["size"] > 0
                    ? time() . "_" . $_FILES["new_avatar"]["name"]
                    : '';
                $this->adminService->createAdmin(new AdminCreateRequest($_POST));
                $_SESSION['success'] = "Create successful!";
                header("Location: ?controller=admin");
            } catch (ValidationException $e) {
                $_SESSION['errors'] = $e->getErrors();
                $_SESSION['oldData'] = $_POST;
                $this->redirectWithError("?controller=admin&action=create");
            }
        }
    }

    public function updateAdmin()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $_POST = $this->cleanInputData($_POST);
                $_POST['avatar'] = isset($_FILES["new_avatar"]) && $_FILES["new_avatar"]["size"] > 0
                    ? time() . "_" . $_FILES["new_avatar"]["name"]
                    : $_POST['current_avatar'];
                $id = $_POST['id'];
                $this->adminService->updateAdmin($id, new AdminUpdateRequest($_POST));
                $_SESSION['success'] = "Update successful!";
                header("Location: ?controller=admin");
            } catch (ValidationException $e) {
                $_SESSION['errors'] = $e->getErrors();
                $this->redirectWithError("?controller=admin&action=edit&id=$id");
            }
        }
    }

    public function deleteAdmin()
    {
        $id = $_GET["id"] ?? "0";
        if (empty($id) || $id == "0" || !filter_var($id, FILTER_VALIDATE_INT))
            $this->redirectWithError("?controller=admin", "Not valid ID");

        try {
            $this->adminService->deleteAdmin($id);
            $_SESSION['success'] = "Delete successful!";
            header("Location: ?controller=admin");
            exit;
        } catch (Exception $e) {
            $this->redirectWithError("?controller=admin", $e->getMessage());
        }
    }
}
?>