<?php
session_start();
if (isset($_SESSION["admin_id"]) && $_SESSION["admin_id"] != "0") {
    header("Location: ?controller=admin");
    exit;
}

$errors = $_SESSION['errors'] ?? []; // Lấy lỗi từ session nếu có
unset($_SESSION['errors']); // Xóa lỗi sau khi lấy
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <h3 class="text-center">Đăng nhập Admin</h3>

                <!-- Hiển thị lỗi -->
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger"><?= $_SESSION['error'] ?></div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>

                <form action="?controller=admin&action=login" method="POST">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="text" class="form-control" id="email" name="email">
                        <?php if (!empty($errors['emailError'])): ?>
                            <p style="color: red;"><?php echo $errors['emailError']; ?></p>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Mật khẩu</label>
                        <input type="password" class="form-control" id="password" name="password">
                        <?php if (!empty($errors['passwordError'])): ?>
                            <p style="color: red;"><?php echo $errors['passwordError']; ?></p>
                        <?php endif; ?>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Đăng nhập</button>
                </form>
                <a href="?">User</a>
            </div>
        </div>
    </div>
</body>

</html>