<?php


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
        <form action="?controller=user&action=createUser" method="POST">
            <div class="mb-3">

            </div>
            <div class="mb-3">
                <label for="name" class="form-label">Tên</label>
                <input type="text" class="form-control" id="name" name="name" value="<?= $oldData['name'] ?? ''; ?>">
                <div class="text-danger"><?= $errors['nameError'] ?? ''; ?></div>
            </div>

            <div class="mb-3">
                <label for="facebook_id" class="form-label">Facebook ID</label>
                <input type="text" class="form-control" id="facebook_id" name="facebook_id"
                    value="<?= $oldData['facebook_id'] ?? ''; ?>">
                <div class="text-danger"><?= $errors['facebookIdError'] ?? ''; ?></div>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Mật Khẩu</label>
                <input type="text" class="form-control" id="password" name="password"
                    value="<?= $oldData['password'] ?? ''; ?>">
                    <div class="text-danger"><?= $errors['passwordError'] ?? ''; ?></div>

            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="text" class="form-control" id="email" name="email" value="<?= $oldData['email'] ?? ''; ?>">
                <div class="text-danger"><?= $errors['emailError'] ?? ''; ?></div>
            </div>
            


            <div class="mb-3">
                <label for="avatar" class="form-label">Ảnh Đại Diện (URL)</label>
                <input type="text" class="form-control" id="avatar" name="avatar"
                    value="<?= $oldData['avatar'] ?? ''; ?>">
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
                <label for="ins_id" class="form-label">Người thêm (ID)</label>
                <input type="number" class="form-control" id="ins_id" name="ins_id" value="1">
            </div>

            <button type="submit" class="btn btn-success" name="add">Thêm Người Dùng</button>
            <a href="?controller=user" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>