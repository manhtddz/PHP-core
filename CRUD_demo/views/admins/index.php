<?php

// require_once("../../controllers/UserController.php");
// $userController = new UserController();
// $users = $userController->getAllUsers();

?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh Sách Người Dùng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-4">
        <h2 class="mb-3">Danh Sách Người Dùng</h2>

        <!-- Nút Thêm Người Dùng -->
        <a href="?controller=admin&action=create" class="btn btn-primary mb-3">Thêm Người Dùng</a>

        <!-- Hiển thị Bảng Người Dùng -->
        <table class="table table-bordered table-striped">
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
                                <img src="<?= htmlspecialchars($admin->getAvatar()) ?>" width="50" height="50"
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
                            <form method="POST" action="/admins/delete" style="display:inline-block;">
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
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>