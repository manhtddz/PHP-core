<?php
$controller = $_GET['controller'] ?? 'home';
$actionName = $_GET['action'] ?? 'index';
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="index.php">My Website</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?= $controller == 'user' ? 'active' : '' ?>" href="#"
                        id="userDropdown" role="button" data-bs-toggle="dropdown">
                        Quản lý người dùng
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item <?= ($controller == 'user' && $actionName == 'index') ? 'active' : '' ?>"
                                href="?controller=user">Search</a></li>
                        <li><a class="dropdown-item <?= ($controller == 'user' && $actionName == 'create') ? 'active' : '' ?>"
                                href="?controller=user&action=create">Create</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>

                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?= $controller == 'admin' ? 'active' : '' ?>" href="#"
                        id="adminDropdown" role="button" data-bs-toggle="dropdown">
                        Quản lý Admin
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item <?= ($controller == 'admin' && $actionName == 'index') ? 'active' : '' ?>"
                                href="?controller=admin">Search</a></li>
                        <li><a class="dropdown-item <?= ($controller == 'admin' && $actionName == 'create') ? 'active' : '' ?>"
                                href="?controller=admin&action=create">Create</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>

                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="?action=logout">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>