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
        <h2 class="mb-3">Thêm Admin</h2>


        <form action="?controller=admin&action=createAdmin" method="POST">
            <div class="mb-3">

            </div>
            <div class="mb-3">
                <label for="name" class="form-label">Tên</label>
                <input type="text" class="form-control" id="name" name="name" value="<?= $oldData['name'] ?? ''; ?>">
                <div><?= $errors['nameError'] ?? ''; ?></div>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Mật Khẩu</label>
                <input type="text" class="form-control" id="password" name="password"
                    value="<?= $oldData['password'] ?? ''; ?>">
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="text" class="form-control" id="email" name="email" value="<?= $oldData['email'] ?? ''; ?>">
                <div><?= $errors['emailError'] ?? ''; ?></div>
            </div>

            <div class="mb-3">
                <label for="avatar" class="form-label">Ảnh Đại Diện (URL)</label>
                <input type="text" class="form-control" id="avatar" name="avatar"
                    value="<?= $oldData['avatar'] ?? ''; ?>">
                <div><?= $errors['avatarError'] ?? ''; ?></div>
            </div>

            <div class="mb-3">
                <label for="ins_id" class="form-label">Người thêm (ID)</label>
                <input type="number" class="form-control" id="ins_id" name="ins_id" value="1">
            </div>

            <button type="submit" class="btn btn-success" name="add">Thêm Admin</button>
            <a href="?controller=admin&action=index" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>