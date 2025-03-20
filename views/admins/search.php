<?php require_once(dirname(__DIR__) . "/layouts/head.php") ?>


<body>
    <?php require_once(dirname(__DIR__) . "/layouts/header.php") ?>

    <div class="container mt-5">
        <h2 class="mb-4">Admin - Search</h2>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= $_SESSION['success'] ?></div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= $_SESSION['error'] ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
        <!-- Form tìm kiếm -->
        <form method="GET" class="mb-3">
            <input type="hidden" name="controller" value="admin">
            <input type="hidden" name="action" value="search">
            <div class="row">
                <div class="col-md-4">
                    <label for="name" class="form-label">Name:</label>
                    <input type="text" class="form-control" id="name" name="name"
                        value="<?= isset($_GET['name']) ? htmlspecialchars($_GET['name']) : '' ?>">
                </div>
                <div class="col-md-4">
                    <label for="email" class="form-label">Email:</label>
                    <input type="text" class="form-control" id="email" name="email"
                        value="<?= isset($_GET['email']) ? htmlspecialchars($_GET['email']) : '' ?>">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">Search</button>
                    <a href="?controller=admin&action=search" class="btn btn-secondary">Reset</a>
                </div>
            </div>
        </form>

        <!-- Hiển thị kết quả tìm kiếm -->
        <?php if (!empty($admins)): ?>
            <h3>Result:</h3>
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>
                            <a href="?controller=admin&action=search&name=<?= urlencode($name) ?>&email=<?= urlencode($email) ?>&page=<?= $page ?>&sort=<?= $newSort ?>"
                                class="text-white ">
                                ID ↕
                            </a>
                        </th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Avatar</th>
                        <th>Role</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($admins as $admin): ?>
                        <tr>
                            <td><?= $admin->getId() ?></td>
                            <td><?= htmlspecialchars($admin->getName()) ?></td>
                            <td><?= htmlspecialchars($admin->getEmail()) ?></td>
                            <td>
                                <?php if ($admin->getAvatar()): ?>

                                    <img src="uploads/images/avatar/<?= $admin->getAvatar() ?>" width="50" height="50"
                                        class="rounded-circle" title="<?= $admin->getAvatar() ?>">
                                <?php else: ?>
                                    <span class="text-muted">No avatar</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?= $admin->getRoleType() == 1 ? '<span class="text-success">Admin</span>' : '<span class="text-danger">SuperAdmin</span>'; ?>
                            </td>
                            <td>
                                <!-- Nút sửa -->
                                <a href="?controller=admin&action=edit&id=<?= $admin->getId() ?>"
                                    class="btn btn-warning btn-sm">Edit</a>

                                <!-- Nút xóa -->
                                <form method="POST" action="?controller=admin&action=deleteAdmin&id=<?= $admin->getId() ?>"
                                    style="display:inline-block;">
                                    <input type="hidden" name="id" value="<?= $admin->getId() ?>">
                                    <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Bạn có chắc chắn muốn xóa?');">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <?php if ($totalPages > 1): ?>
                    <nav>
                        <ul class="pagination">
                            <!-- Nút First -->
                            <li class="page-item <?= $page == 1 ? 'disabled' : '' ?>">
                                <a class="page-link"
                                    href="?controller=admin&action=search&name=<?= urlencode($name) ?>&email=<?= urlencode($email) ?>&page=1&sort=<?= $sort ?>">«
                                    First</a>
                            </li>

                            <!-- Nút Trước -->
                            <li class="page-item <?= $page == 1 ? 'disabled' : '' ?>">
                                <a class="page-link"
                                    href="?controller=admin&action=search&name=<?= urlencode($name) ?>&email=<?= urlencode($email) ?>&page=<?= $page - 1 ?>&sort=<?= $sort ?>">«
                                    Prev</a>
                            </li>

                            <!-- Số trang -->
                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                                    <a class="page-link"
                                        href="?controller=admin&action=search&name=<?= urlencode($name) ?>&email=<?= urlencode($email) ?>&page=<?= $i ?>&sort=<?= $sort ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>

                            <!-- Nút Sau -->
                            <li class="page-item <?= $page == $totalPages ? 'disabled' : '' ?>">
                                <a class="page-link"
                                    href="?controller=admin&action=search&name=<?= urlencode($name) ?>&email=<?= urlencode($email) ?>&page=<?= $page + 1 ?>&sort=<?= $sort ?>">Next
                                    »</a>
                            </li>

                            <!-- Nút Last -->
                            <li class="page-item <?= $page == $totalPages ? 'disabled' : '' ?>">
                                <a class="page-link"
                                    href="?controller=admin&action=search&name=<?= urlencode($name) ?>&email=<?= urlencode($email) ?>&page=<?= $totalPages ?>&sort=<?= $sort ?>">Last
                                    »</a>
                            </li>
                        </ul>
                    </nav>
                <?php endif; ?>
            </table>
        <?php else: ?>
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Avatar</th>
                        <th>Role</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="7" class="text-center">
                            <p class="m-0 p-3 text-muted">No results found</p>
                        </td>
                    </tr>

                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>