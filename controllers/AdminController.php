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
        $this->checkLogin(['2']);
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

        list($admins, $totalPages) = $this->adminService->search(new SearchRequest($searchParams), $page, $orderBy);
        $this->view("admins.search", compact("admins", "totalPages", "page", "name", "email", "sort", "newSort"));
        exit();
    }

    public function search()
    {
        $this->checkLogin(['2']);
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

        list($admins, $totalPages) = $this->adminService->search(new SearchRequest($searchParams), $page, $orderBy);
        $this->view("admins.search", compact("admins", "totalPages", "page", "name", "email", "sort", "newSort"));
        exit();
        // }
        // $this->view("admins.search");
        // exit();
    }


    public function create()
    {
        $this->checkLogin(['2']);
        $this->view('admins.create', [
            'errors' => $_SESSION['errors'] ?? [],
            'oldData' => $_SESSION['oldData'] ?? []
        ]);
        unset($_SESSION['errors'], $_SESSION['oldData']);
    }

    public function edit()
    {
        $this->checkLogin(['2']);
        try {
            $id = $_GET["id"] ?? "0";
            if (empty($id) || $id == "0" || !filter_var($id, FILTER_VALIDATE_INT))
                $this->redirectWithError("?controller=admin", "Not valid ID");

            $admin = $this->adminService->getAdminById($id);

            $this->view("admins.edit", [
                "admin" => $admin,
                "errors" => $_SESSION['errors'] ?? [],
                "oldEditData" => $_SESSION['oldEditData'] ?? []
            ]);
            unset($_SESSION['errors'], $_SESSION['oldEditData']);
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
                exit;
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
                exit;
            } catch (ValidationException $e) {
                $_SESSION['errors'] = $e->getErrors();
                $_SESSION['oldEditData'] = $_POST;
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