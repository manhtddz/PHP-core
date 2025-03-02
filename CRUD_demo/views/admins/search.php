<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Tìm Kiếm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?= require_once(dirname(__DIR__) . "/layouts/header.php") ?>

    <div class="container mt-5">
        <h2 class="mb-4">Tìm Kiếm Admin</h2>

        <!-- Form tìm kiếm -->
        <form method="GET" class="mb-3">
            <input type="hidden" name="controller" value="admin">
            <input type="hidden" name="action" value="search">
            <div class="row">
                <div class="col-md-4">
                    <label for="name" class="form-label">Tên người dùng:</label>
                    <input type="text" class="form-control" id="name" name="name">
                </div>
                <div class="col-md-4">
                    <label for="email" class="form-label">Email:</label>
                    <input type="text" class="form-control" id="email" name="email">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">Tìm kiếm</button>
                    <a href="?controller=admin&action=search" class="btn btn-secondary">Reset</a>
                </div>
            </div>
        </form>

        <!-- Hiển thị kết quả tìm kiếm -->
        <?php if (!empty($admins)): ?>
            <h3>Kết quả tìm kiếm:</h3>
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Tên</th>
                        <th>Email</th>
                        <th>Avatar</th>
                        <th>Role</th>
                        <th>Ngày Tạo</th>
                        <th>Thao Tác</th>
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
                                    <span class="text-muted">Không có ảnh</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?= $admin->getRoleType() == 1 ? '<span class="text-success">Admin</span>' : '<span class="text-danger">SuperAdmin</span>'; ?>
                            </td>
                            <td><?= $admin->getInsDatetime() ?></td>
                            <td>
                                <!-- Nút sửa -->
                                <a href="?controller=admin&action=edit&id=<?= $admin->getId() ?>"
                                    class="btn btn-warning btn-sm">Sửa</a>

                                <!-- Nút xóa -->
                                <form method="POST" action="?controller=admin&action=deleteAdmin&id=<?= $admin->getId() ?>"
                                    style="display:inline-block;">
                                    <input type="hidden" name="id" value="<?= $admin->getId() ?>">
                                    <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Bạn có chắc chắn muốn xóa?');">
                                        Xóa
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <?php if ($totalPages > 1): ?>
                    <nav>
                        <ul class="pagination">
                            <li class="page-item <?= $page == 1 ? 'disabled' : '' ?>">
                                <a class="page-link"
                                    href="?controller=admin&action=search&name=<?= urlencode($name) ?>&email=<?= urlencode($email) ?>&page=<?= $page - 1 ?>">«
                                    Trước</a>
                            </li>

                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                                    <a class="page-link"
                                        href="?controller=admin&action=search&name=<?= urlencode($name) ?>&email=<?= urlencode($email) ?>&page=<?= $i ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>

                            <li class="page-item <?= $page == $totalPages ? 'disabled' : '' ?>">
                                <a class="page-link"
                                    href="?controller=admin&action=search&name=<?= urlencode($name) ?>&email=<?= urlencode($email) ?>&page=<?= $page + 1 ?>">Sau
                                    »</a>
                            </li>
                        </ul>
                    </nav>
                <?php endif; ?>
            </table>
        <?php else: ?>
            <p class="text-muted">Không tìm thấy người dùng nào.</p>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>