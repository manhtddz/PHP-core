<?php

// require_once("../../controllers/UserController.php");
// $userController = new UserController();
// if (isset($_GET["id"]) && $_GET["id"] !== "") {
//     $id = $_GET["id"];
//     $user = $userController->getOne($id);
// }
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Người Dùng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?= require_once(dirname(__DIR__) . "/layouts/header.php") ?>

    <div class="container mt-4">
        <h2 class="mb-3">Sửa Admin</h2>

        <?php if (isset($error)): ?>
            <div><?= $error ?></div>
        <?php endif; ?>
        <form action="?controller=admin&action=updateAdmin" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <input type="hidden" class="form-control" id="id" name="id" value="<?= $admin->getId() ?>">
            </div>
            <div class="mb-3">
                <label for="name" class="form-label">Tên</label>
                <input type="text" class="form-control" id="name" name="name" value="<?= $admin->getName() ?>">
                <div class="text-danger"><?= $errors['nameError'] ?? ''; ?></div>

            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="text" class="form-control" id="email" name="email" value="<?= $admin->getEmail() ?>">
                <div class="text-danger"><?= $errors['emailError'] ?? ''; ?></div>

            </div>

            <div class="mb-3">
                <input type="hidden" name="current_avatar" value="<?= $admin->getAvatar() ?>">

                <!-- Hiển thị ảnh cũ -->
                <?php if (!empty($admin->getAvatar())): ?>
                    <img src="uploads/images/avatar/<?= $admin->getAvatar() ?>" width="150"><br>
                <?php endif; ?>

                <!-- Chọn ảnh mới -->
                <input type="file" name="new_avatar">
                <div class="text-danger"><?= $errors['avatarError'] ?? ''; ?></div>

            </div>

            <div class="mb-3">
                <label for="role_type" class="form-label">Role</label>
                <select class="form-control" id="role_type" name="role_type">
                    <option value="1" selected>Admin</option>
                    <option value="2">Super admin</option>
                </select>
            </div>

            <div class="mb-3">
                <!-- <label for="upd_id" class="form-label">Người Sửa (ID)</label> -->
                <input type="hidden" class="form-control" id="upd_id" name="upd_id"
                    value="<?= $_SESSION['admin_id'] ?>">
            </div>

            <button type="submit" class="btn btn-success">Sửa Admin</button>
            <a href="?controller=admin" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>