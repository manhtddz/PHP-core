<?php

?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh Sách Người Dùng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        a {
            text-decoration: none;
        }

        a:hover {
            text-decoration: none;
        }
    </style>
</head>

<body>
    <?= require_once(dirname(__DIR__) . "/layouts/header.php") ?>
    <div class="container mt-4">
        <h2 class="mb-3">Danh Sách Người Dùng</h2>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= $_SESSION['success'] ?></div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <!-- Nút Thêm Người Dùng -->
        <a href="?controller=user&action=create" class="btn btn-primary mb-3">Thêm Người Dùng</a>

        <!-- Hiển thị Bảng Người Dùng -->
        <?php if (!empty($users)): ?>
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>
                            <a href="?controller=user&sort=<?= $newSort ?>" class="text-white">
                                ID ↕
                            </a>
                        </th>
                        <th>Tên</th>
                        <th>Facebook ID</th>
                        <th>Email</th>
                        <th>Avatar</th>
                        <th>Trạng Thái</th>
                        <th>Ngày Tạo</th>
                        <th>Thao Tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= $user->getId() ?></td>
                            <td><?= htmlspecialchars($user->getName()) ?></td>
                            <td><?= htmlspecialchars($user->getFacebookId()) ?></td>
                            <td><?= htmlspecialchars($user->getEmail()) ?></td>
                            <td>
                                <?php if ($user->getAvatar()): ?>

                                    <img src="uploads/images/avatar/<?= $user->getAvatar() ?>" width="50" height="50"
                                        class="rounded-circle" title="<?= $user->getAvatar() ?>">
                                <?php else: ?>
                                    <span class="text-muted">Không có ảnh</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?= $user->getStatus() == 1 ? '<span class="text-success">Hoạt động</span>' : '<span class="text-danger">Ngừng hoạt động</span>'; ?>
                            </td>
                            <td><?= $user->getInsDatetime() ?></td>
                            <td>
                                <!-- Nút sửa -->
                                <a href="?controller=user&action=edit&id=<?= $user->getId() ?>"
                                    class="btn btn-warning btn-sm">Sửa</a>

                                <!-- Nút xóa -->
                                <form method="POST" action="?controller=user&action=deleteUser&id=<?= $user->getId() ?>"
                                    style="display:inline-block;">
                                    <input type="hidden" name="id" value="<?= $user->getId() ?>">
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
                                <a class="page-link" href="?controller=user&page=<?= $page - 1 ?>&sort=<?= $sort ?>">«
                                    Trước</a>
                            </li>

                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                                    <a class="page-link" href="?controller=user&page=<?= $i ?>&sort=<?= $sort ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>

                            <li class="page-item <?= $page == $totalPages ? 'disabled' : '' ?>">
                                <a class="page-link" href="?controller=user&page=<?= $page + 1 ?>&sort=<?= $sort ?>">Sau
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
                        <th>Tên</th>
                        <th>Email</th>
                        <th>Avatar</th>
                        <th>Role</th>
                        <th>Ngày Tạo</th>
                        <th>Thao Tác</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="7">
                            <p class="">Can't find any admin</p>
                        </td>
                    </tr>

                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>