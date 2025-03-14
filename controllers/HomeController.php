<?php

require_once(dirname(__DIR__) . "/services/UserService.php");
require_once(dirname(__DIR__) . "/services/AuthService.php");
require_once(dirname(__DIR__) . "/controllers/BaseController.php");
require_once(dirname(__DIR__) . "/dto/UserCreateRequest.php");
require_once(dirname(__DIR__) . "/dto/UserUpdateRequest.php");
require_once(dirname(__DIR__) . "/exceptions/ValidationException.php");
require_once(dirname(__DIR__) . "/dto/LoginRequest.php");


// require_once '../dto/UserRequest.php';

class HomeController extends BaseController
{
    private $authService;
    public function __construct()
    {
        parent::__construct();
        $this->authService = new AuthService();
    }
    public function index()
    {
        $this->view("logins.user");
    }
    public function adminIndex()
    {
        $this->view("logins.admin");
    }

    public function login()
    {
        // if ($_SESSION["auth"]) {
        //     $_SESSION["isOldLogin"] = true;
        // }
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
                $auth = $this->authService->login($req, $_POST['typeOfUser']);
                if ($auth) {
                    $_SESSION['auth'] = [
                        'id' => $auth['id'],
                        'role_type' => $auth['role_type'] ?? '',
                    ];
                    if ($_SESSION['auth']['role_type'] === '') {
                        header('Location: ?controller=user&action=info');
                        exit;
                    }
                    if ($_SESSION['auth']['role_type'] === '1') {
                        header('Location: ?controller=user&action=index');
                        exit;
                    } else if ($_SESSION['auth']['role_type'] === '2') {
                        header('Location: ?controller=admin&action=index');
                        exit;
                    } else {
                        $this->redirectWithError('?', "Role is not accepted");
                    }
                    exit;
                }
            } catch (Exception $e) {
                $this->redirectWithError('?', $e->getMessage());
            }
        }
    }


    public function logout()
    {
        unset($_SESSION['auth']);
        header("Location: ?");
        exit;
    }
}
?>