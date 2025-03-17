<?php
// session_start();
// $_SESSION["role"] = "user";
// $_SESSION["id"] = 9;
// if (isset($_SESSION["role"])) {
//     $_SESSION['role'] == 'user' ?
//         header('Location: ?controller=user&action=info') :
//         header('Location: ?controller=admin&action=index');
//     exit;
// }
$errors = $_SESSION['errors'] ?? []; // Lấy lỗi từ session nếu có
unset($_SESSION['errors']); // Xóa lỗi sau khi lấy
?>
<?php require_once(dirname(__DIR__) . "/layouts/head.php") ?>


<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <h3 class="text-center">User - Login</h3>

                <!-- Hiển thị lỗi -->
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger"><?= $_SESSION['error'] ?></div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>

                <form action="?action=login" method="POST">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="text" class="form-control" id="email" name="email">
                        <?php if (!empty($errors['emailError'])): ?>
                            <p style="color: red;"><?php echo $errors['emailError']; ?></p>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password">
                        <?php if (!empty($errors['passwordError'])): ?>
                            <p style="color: red;"><?php echo $errors['passwordError']; ?></p>
                        <?php endif; ?>
                    </div>
                    <input type="hidden" class="form-control" id="typeOfUser" name="typeOfUser" value="users">

                    <button type="submit" class="btn btn-primary w-100">Login</button>
                </form>
                <a href="?action=adminIndex">Admin login</a>
                <a href="<?= $loginUrl ?>">login via facebook</a>

            </div>
        </div>
    </div>
</body>

</html>