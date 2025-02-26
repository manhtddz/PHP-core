require_once(dirname(__DIR__) . "/controllers/UserController.php");

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$userController = new UserController();

switch ($uri) {
    case '/users':
        $userController->index();
        break;

    case '/users/createForm':
        require_once __DIR__ . '/../views/users/create.php';
        break;

    case '/users/create':
        $userController->createUser();
        break;

    case '/users/editForm':
        if (!isset($_GET['id'])) {
            echo "Lỗi: Thiếu ID user.";
            exit;
        }
        $userController->edit(intval($_GET['id']));
        break;

    case '/users/update':
        if (!isset($_GET['id'])) {
            echo "Lỗi: Thiếu ID user.";
            exit;
        }
        $userController->updateUser(intval($_GET['id']));
        break;

    case '/users/delete':
        if (!isset($_GET['id'])) {
            echo "Lỗi: Thiếu ID user.";
            exit;
        }
        $userController->deleteUser(intval($_GET['id']));
        break;

    default:
        http_response_code(404);
        echo "404 Not Found";
        break;
}
?>