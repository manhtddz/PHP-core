<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="index.php">My Website</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                        data-bs-toggle="dropdown">
                        Quản lý người dùng
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="?controller=user">Tất cả</a></li>
                        <li><a class="dropdown-item" href="?controller=user&action=searchForm">Search</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                       
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                        data-bs-toggle="dropdown">
                        Quản lý Admin
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="?controller=admin">Tất cả</a></li>
                        <li><a class="dropdown-item" href="?controller=admin&action=searchForm">Search</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                       
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="?controller=user&action=logout">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>