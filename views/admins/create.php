<?php require_once(dirname(__DIR__) . "/layouts/head.php"); ?>

<body>
    <?php require_once(dirname(__DIR__) . "/layouts/header.php"); ?>
    <div class="container mt-4">
        <h2 class="mb-3">Admin - Create</h2>


        <form action="?controller=admin&action=createAdmin" method="POST" enctype="multipart/form-data">
            <div class="mb-3">

            </div>
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?= $oldData['name'] ?? ''; ?>">
                <div class="text-danger"><?= $errors['nameError'] ?? ''; ?></div>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password"
                    value="<?= $oldData['password'] ?? ''; ?>">
                <div class="text-danger"><?= $errors['passwordError'] ?? ''; ?></div>
            </div>
            <div class="mb-3">
                <label for="passwordVerify" class="form-label">Verify password:</label>
                <input type="password" class="form-control" id="passwordVerify" name="passwordVerify"
                    value="<?= $oldData['passwordVerify'] ?? ''; ?>">
                <div class="text-danger"><?= $errors['passwordVerifyError'] ?? ''; ?></div>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="text" class="form-control" id="email" name="email" value="<?= $oldData['email'] ?? ''; ?>">
                <div class="text-danger"><?= $errors['emailError'] ?? ''; ?></div>
            </div>

            <div class="mb-3">
                <label for="new_avatar">Upload avatar:</label>
                <input type="file" name="new_avatar" id="new_avatar">
                <div class="text-danger"><?= $errors['avatarError'] ?? ''; ?></div>
            </div>
            <?php
            $tempAvatar = $_SESSION["temp_avatar"] ?? '';
            unset($_SESSION["temp_avatar"]);
            ?>
            <?php if ($tempAvatar && $tempAvatar !== ""): ?>
                <img src="uploads/images/temp/<?= $tempAvatar ?>" width="150"><br>
                <input type="hidden" name="tempFileName" value="<?php echo $tempAvatar; ?>">
            <?php endif; ?>
            <div class="mb-3">
                <?php
                $selectedRole = $oldData['role_type'] ?? '';
                ?>
                <label for="role_type" class="form-label">Role</label>
                <div class="form-check mb-2">
                    <input type="radio" id="role_admin" name="role_type" value="1" class="form-check-input"
                        <?= $selectedRole == "1" ? 'checked' : ''; ?>>
                    <label for="role_admin" class="form-check-label">Admin</label>
                </div>

                <div class="form-check mb-2">
                    <input type="radio" id="role_super_admin" name="role_type" value="2" class="form-check-input"
                        <?= $selectedRole == "2" ? 'checked' : ''; ?>>
                    <label for="role_super_admin" class="form-check-label">Super Admin</label>
                </div>
                <div class="text-danger"><?= $errors['roleTypeError'] ?? ''; ?></div>
            </div>

            <div class="mb-3">
                <!-- <label for="ins_id" class="form-label">Người thêm (ID)</label> -->
                <input type="hidden" class="form-control" id="ins_id" name="ins_id"
                    value="<?= $_SESSION['auth']['id'] ?>">
            </div>

            <button type="submit" class="btn btn-success" name="add">Create</button>
            <a href="?controller=admin" class="btn btn-secondary">Cancel</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>