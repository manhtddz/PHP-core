class Route {
    public static function start() {
        $controllerName = "Home";
        $actionName = "index";

        if (!empty($_GET['controller'])) {
            $controllerName = ucfirst($_GET['controller']);
        }
        if (!empty($_GET['action'])) {
            $actionName = $_GET['action'];
        }

        $controllerClass = $controllerName . 'Controller';
        $controllerFile = "app/controllers/" . $controllerClass . ".php";
        
        if (file_exists($controllerFile)) {
            include_once $controllerFile;
            $controller = new $controllerClass();
            if (method_exists($controller, $actionName)) {
                $controller->$actionName();
            } else {
                echo "Action not found!";
            }
        } else {
            echo "Controller not found!";
        }
    }
}