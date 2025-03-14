<?php require_once(dirname(__DIR__) . "/layouts/head.php") ?>


<body>
    <?php require_once(dirname(__DIR__) . "/layouts/header.php") ?>

    <div class="container mt-5">
        <h2 class="mb-4">User - Search</h2>

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
            <input type="hidden" name="controller" value="user">
            <input type="hidden" name="action" value="search">
            <div class="row">
                <div class="col-md-4">
                    <label for="name" class="form-label">Name:</label>
                    <input type="text" class="form-control" id="name" name="name">
                </div>
                <div class="col-md-4">
                    <label for="email" class="form-label">Email:</label>
                    <input type="text" class="form-control" id="email" name="email">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">Search</button>
                    <a href="?controller=user&action=search" class="btn btn-secondary">Reset</a>
                </div>
            </div>
        </form>

        <!-- Hiển thị kết quả tìm kiếm -->
        <?php if (!empty($users)): ?>
            <h3>Result:</h3>
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>
                            <a href="?controller=user&action=search&name=<?= urlencode($name) ?>&email=<?= urlencode($email) ?>&page=<?= $page ?>&sort=<?= $newSort ?>"
                                class="text-white ">
                                ID ↕
                        </th>
                        </a>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Avatar</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= $user->getId() ?></td>
                            <td><?= htmlspecialchars($user->getName()) ?></td>
                            <td><?= htmlspecialchars($user->getEmail()) ?></td>
                            <td>
                                <?php if ($user->getAvatar()): ?>

                                    <img src="uploads/images/avatar/<?= $user->getAvatar() ?>" width="50" height="50"
                                        class="rounded-circle" title="<?= $user->getAvatar() ?>">
                                <?php else: ?>
                                    <span class="text-muted">No avatar</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?= $user->getStatus() == 1 ?
                                    '<span class="text-success">Active</span>' :
                                    '<span class="text-danger">Banned</span>'; ?>
                            </td>
                            <td>
                                <!-- Nút sửa -->
                                <a href="?controller=user&action=edit&id=<?= $user->getId() ?>"
                                    class="btn btn-warning btn-sm">Edit</a>

                                <!-- Nút xóa -->
                                <form method="POST" action="?controller=user&action=deleteUser&id=<?= $user->getId() ?>"
                                    style="display:inline-block;">
                                    <input type="hidden" name="id" value="<?= $user->getId() ?>">
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
                                    href="?controller=user&action=search&name=<?= urlencode($name) ?>&email=<?= urlencode($email) ?>&page=1&sort=<?= $sort ?>">«
                                    First</a>
                            </li>

                            <!-- Nút Trước -->
                            <li class="page-item <?= $page == 1 ? 'disabled' : '' ?>">
                                <a class="page-link"
                                    href="?controller=user&action=search&name=<?= urlencode($name) ?>&email=<?= urlencode($email) ?>&page=<?= $page - 1 ?>&sort=<?= $sort ?>">«
                                    Prev</a>
                            </li>

                            <!-- Số trang -->
                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                                    <a class="page-link"
                                        href="?controller=user&action=search&name=<?= urlencode($name) ?>&email=<?= urlencode($email) ?>&page=<?= $i ?>&sort=<?= $sort ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>

                            <!-- Nút Sau -->
                            <li class="page-item <?= $page == $totalPages ? 'disabled' : '' ?>">
                                <a class="page-link"
                                    href="?controller=user&action=search&name=<?= urlencode($name) ?>&email=<?= urlencode($email) ?>&page=<?= $page + 1 ?>&sort=<?= $sort ?>">Next
                                    »</a>
                            </li>

                            <!-- Nút Last -->
                            <li class="page-item <?= $page == $totalPages ? 'disabled' : '' ?>">
                                <a class="page-link"
                                    href="?controller=user&action=search&name=<?= urlencode($name) ?>&email=<?= urlencode($email) ?>&page=<?= $totalPages ?>&sort=<?= $sort ?>">Last
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
                        <th>Status</th>
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