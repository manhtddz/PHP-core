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


        <!-- Form thêm người dùng -->
        <form action="?controller=user&action=updateUser" method="POST">
            <div class="mb-3">
                <!-- <label for="name" class="form-label">Tên</label> -->
                <input type="hidden" class="form-control" id="id" name="id" value="<?= $user->getId() ?>">
            </div>
            <div class="mb-3">
                <label for="name" class="form-label">Tên</label>
                <input type="text" class="form-control" id="name" name="name" value="<?= $user->getName() ?>">
                <div class="text-danger"><?= $errors['nameError'] ?? ''; ?></div>
            </div>

            <div class="mb-3">
                <label for="facebook_id" class="form-label">Facebook ID</label>
                <input type="text" class="form-control" id="facebook_id" name="facebook_id"
                    value="<?= $user->getFacebookId() ?>">
                <div class="text-danger"><?= $errors['facebookIdError'] ?? ''; ?></div>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Mật Khẩu</label>
                <input type="password" class="form-control" id="password" name="password"
                    value="<?= $user->getPassword() ?>">
                <div class="text-danger"><?= $errors['passwordError'] ?? ''; ?></div>

            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?= $user->getEmail() ?>">
                <div class="text-danger"><?= $errors['emailError'] ?? ''; ?></div>
            </div>

            <div class="mb-3">
                <label for="avatar" class="form-label">Ảnh Đại Diện (URL)</label>
                <input type="text" class="form-control" id="avatar" name="avatar" value="<?= $user->getAvatar() ?>">
                <div class="text-danger"><?= $errors['avatarError'] ?? ''; ?></div>
            </div>

            <div class="mb-3">
                <label for="status" class="form-label">Trạng Thái</label>
                <select class="form-control" id="status" name="status">
                    <option value="1" selected>Hoạt động</option>
                    <option value="0">Ngừng hoạt động</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="upd_id" class="form-label">Người Sửa (ID)</label>
                <input type="number" class="form-control" id="upd_id" name="upd_id" value="<?= $user->getUpdId() ?>">
            </div>

            <button type="submit" class="btn btn-success">Sửa Người Dùng</button>
            <a href="?controller=user" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>