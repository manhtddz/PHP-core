<?php

$controllerName = ucfirst(strtolower($_REQUEST['controller'] ?? "Home")) . 'Controller';
$actionName = $_REQUEST["action"] ?? "index";
// require_once(dirname(__DIR__) . "/routes/web.php");

// require_once '../routes/web.php';

require_once "./controllers/{$controllerName}.php";
$controller = new $controllerName();
$controller->$actionName();
// $users = $userController->index();
// $user = $userController->getOne(1);

?>

<!-- <!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD User với PHP</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body class="container mt-4">



</body> -->