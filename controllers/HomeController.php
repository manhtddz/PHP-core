<?php

require_once(dirname(__DIR__) . "/services/UserService.php");
require_once(dirname(__DIR__) . "/services/AuthService.php");
require_once(dirname(__DIR__) . "/controllers/BaseController.php");
require_once(dirname(__DIR__) . "/dto/UserCreateRequest.php");
require_once(dirname(__DIR__) . "/dto/UserUpdateRequest.php");
require_once(dirname(__DIR__) . "/exceptions/ValidationException.php");
require_once(dirname(__DIR__) . "/dto/LoginRequest.php");
require_once(dirname(__DIR__) . "/vendor/autoload.php");
require_once(dirname(__DIR__) . "/dto/FacebookUserCreateRequest.php");


// require_once '../dto/UserRequest.php';

class HomeController extends BaseController
{
    private $authService;
    private $userService;

    public function __construct()
    {
        parent::__construct();
        $this->authService = new AuthService();
        $this->userService = new UserService();

    }
    public function index()
    {
        $fb = new Facebook\Facebook([
            'app_id' => '1366387374603190', // Thay bằng App ID của bạn
            'app_secret' => '92ccdcecdcde015c619155937e08bf89', // Thay bằng App Secret
            'default_graph_version' => 'v22.0',
        ]);

        $helper = $fb->getRedirectLoginHelper();

        $permissions = ['email', 'public_profile']; // Thêm public_profile
        $loginUrl = $helper->getLoginUrl('http://localhost/CRUD_demo/config/callback.php', $permissions);

        $loginUrl = htmlspecialchars($loginUrl);
        $this->view("logins.user", compact('loginUrl'));
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
    public function loginFacebook()
    {
        var_dump($_SESSION['facebook_data']);
        $data = $_SESSION['facebook_data'];
        unset($_SESSION['facebook_data']);

        $auth = $this->userService->findByFacebookId($data['facebook_id']);
        if ($auth) {
            $_SESSION['auth'] = [
                'id' => $auth['id'],
            ];
            header('Location: ?controller=user&action=info');
            exit;
        }
        $this->userService->createFacebookUser(new FacebookUserCreateRequest($data));
        $newAuth = $this->userService->findByFacebookId($data['facebook_id']);
        $_SESSION['auth'] = [
            'id' => $newAuth['id'],
        ];
        header('Location: ?controller=user&action=info');
        exit;

    }


    public function logout()
    {
        unset($_SESSION['auth']);
        header("Location: ?");
        exit;
    }
}
?>