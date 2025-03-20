<?php

// require_once("../../controllers/UserController.php");
// $userController = new UserController();
// if (isset($_GET["id"]) && $_GET["id"] !== "") {
//     $id = $_GET["id"];
//     $user = $userController->getOne($id);
// }
?>
<?php require_once(dirname(__DIR__) . "/layouts/head.php") ?>


<body>
    <?php require_once(dirname(__DIR__) . "/layouts/header.php") ?>

    <div class="container mt-4">
        <h2 class="mb-3">Admin - Edit</h2>

        <?php if (isset($error)): ?>
            <div><?= $error ?></div>
        <?php endif; ?>
        <form action="?controller=admin&action=updateAdmin" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <input type="hidden" class="form-control" id="id" name="id" value="<?= $admin->getId() ?>">
            </div>
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name"
                    value="<?= $oldEditData['name'] ?? $admin->getName() ?>">
                <div class="text-danger"><?= $errors['nameError'] ?? ''; ?></div>

            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="text" class="form-control" id="email" name="email"
                    value="<?= $oldEditData['email'] ?? $admin->getEmail() ?>">
                <div class="text-danger"><?= $errors['emailError'] ?? ''; ?></div>

            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password"
                    value="<?= $oldEditData['password'] ?? ''; ?>">
                <div class="text-danger"><?= $errors['passwordError'] ?? ''; ?></div>

            </div>
            <div class="mb-3">
                <label for="passwordVerify" class="form-label">Verify password</label>
                <input type="password" class="form-control" id="passwordVerify" name="passwordVerify"
                    value="<?= $oldEditData['passwordVerify'] ?? '' ?>">
                <div class="text-danger"><?= $errors['passwordVerifyError'] ?? ''; ?></div>
            </div>
            <div class="mb-3">
                <input type="hidden" name="current_avatar" value="<?= $admin->getAvatar() ?>">

                <!-- Chọn ảnh mới -->
                <input type="file" name="new_avatar">
                <div class="text-danger"><?= $errors['avatarError'] ?? ''; ?></div>
                <?php
                $tempAvatar = $_SESSION["temp_avatar"] ?? '';
                unset($_SESSION["temp_avatar"]);

                $avatarPath = $tempAvatar ?: $admin->getAvatar();
                $folder = ($tempAvatar && $tempAvatar !== $admin->getAvatar()) ? "temp" : "avatar";
                ?>

                <?php if (!empty($avatarPath)): ?>
                    <img src="uploads/images/<?= $folder ?>/<?= $avatarPath ?>" width="150"><br>
                <?php endif; ?>

                <input type="hidden" name="tempFileName" value="<?= $avatarPath ?>">

            </div>

            <div class="mb-3">
                <label for="role_type" class="form-label">Role</label>
                <div class="form-check mb-2">
                    <input type="radio" id="role_admin" name="role_type" value="1" class="form-check-input"
                        <?= $admin->getRoleType() == "1" ? 'checked' : ''; ?>>
                    <label for="role_admin" class="form-check-label">Admin</label>
                </div>

                <div class="form-check mb-2">
                    <input type="radio" id="role_super_admin" name="role_type" value="2" class="form-check-input"
                        <?= $admin->getRoleType() == "2" ? 'checked' : ''; ?>>
                    <label for="role_super_admin" class="form-check-label">Super Admin</label>
                </div>
                <div class="text-danger"><?= $errors['roleTypeError'] ?? ''; ?></div>
            </div>

            <div class="mb-3">
                <!-- <label for="upd_id" class="form-label">Người Sửa (ID)</label> -->
                <input type="hidden" class="form-control" id="upd_id" name="upd_id"
                    value="<?= $_SESSION['auth']['id'] ?>">
            </div>

            <button type="submit" class="btn btn-success">Update</button>
            <a href="?controller=admin" class="btn btn-secondary">Cancel</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>