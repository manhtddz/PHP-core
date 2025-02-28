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
        <h2 class="mb-3">Thêm Admin</h2>


        <form action="?controller=admin&action=createAdmin" method="POST" enctype="multipart/form-data">
            <div class="mb-3">

            </div>
            <div class="mb-3">
                <label for="name" class="form-label">Tên</label>
                <input type="text" class="form-control" id="name" name="name" value="<?= $oldData['name'] ?? ''; ?>">
                <div class="text-danger"><?= $errors['nameError'] ?? ''; ?></div>
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
                <label for="new_avatar">Chọn file để upload avatar:</label>
                <input type="file" name="new_avatar" id="new_avatar">
                <div class="text-danger"><?= $errors['avatarError'] ?? ''; ?></div>

            </div>

            <div class="mb-3">
                <!-- <label for="ins_id" class="form-label">Người thêm (ID)</label> -->
                <input type="hidden" class="form-control" id="ins_id" name="ins_id"
                    value="<?= $_SESSION['admin_id'] ?>">
            </div>

            <button type="submit" class="btn btn-success" name="add">Thêm Admin</button>
            <a href="?controller=admin" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>