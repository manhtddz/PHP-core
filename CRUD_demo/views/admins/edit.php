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
    <div class="container mt-4">
        <h2 class="mb-3">Thêm Người Dùng</h2>

        <?php if (isset($error)): ?>
            <div><?= $error ?></div>
        <?php endif; ?>
        <form action="?controller=admin&action=updateAdmin" method="POST">
            <div class="mb-3">
                <input type="hidden" class="form-control" id="id" name="id" value="<?= $user->getId() ?>">
            </div>
            <div class="mb-3">
                <label for="name" class="form-label">Tên</label>
                <input type="text" class="form-control" id="name" name="name" value="<?= $user->getName() ?>">
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Mật Khẩu</label>
                <input type="password" class="form-control" id="password" name="password"
                    value="<?= $user->getPassword() ?>">
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?= $user->getEmail() ?>">
            </div>

            <div class="mb-3">
                <label for="avatar" class="form-label">Ảnh Đại Diện (URL)</label>
                <input type="text" class="form-control" id="avatar" name="avatar" value="<?= $user->getAvatar() ?>">
            </div>

            <div class="mb-3">
                <label for="role_type" class="form-label">Role</label>
                <select class="form-control" id="role_type" name="role_type">
                    <option value="1" selected>Admin</option>
                    <option value="2">Super admin</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="upd_id" class="form-label">Người Sửa (ID)</label>
                <input type="number" class="form-control" id="upd_id" name="upd_id" value="<?= $user->getUpdId() ?>">
            </div>

            <button type="submit" class="btn btn-success">Sửa Admin</button>
            <a href="?controller=admin&action=index" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>