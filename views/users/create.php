<?php

?>
<?php require_once(dirname(__DIR__) . "/layouts/head.php") ?>


<body>
    <?php require_once(dirname(__DIR__) . "/layouts/header.php") ?>
    <div class="container mt-4">
        <h2 class="mb-3">User - Create</h2>


        <!-- Form thêm người dùng -->
        <form action="?controller=user&action=createUser" method="POST" enctype="multipart/form-data">
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

            <div class="mb-3">
                <?php
                $selectedStatus = $oldData['status'] ?? '';
                ?>
                <label for="status" class="form-label">Status</label>
                <div class="form-check mb-2">
                    <input type="radio" id="status_active" name="status" value="1" class="form-check-input"
                        <?= $selectedStatus == "1" ? 'checked' : ''; ?>>
                    <label for="status_active" class="form-check-label">Active</label>
                </div>

                <div class="form-check mb-2">
                    <input type="radio" id="status_banned" name="status" value="2" class="form-check-input"
                        <?= $selectedStatus == "2" ? 'checked' : ''; ?>>
                    <label for="status_banned" class="form-check-label">Banned</label>
                </div>
                <div class="text-danger"><?= $errors['statusError'] ?? ''; ?></div>
            </div>

            <div class="mb-3">
                <!-- <label for="ins_id" class="form-label">Người thêm (ID)</label> -->
                <input type="hidden" class="form-control" id="ins_id" name="ins_id"
                    value="<?= $_SESSION['auth']['id'] ?>">
            </div>

            <button type="submit" class="btn btn-success">Create</button>
            <a href="?controller=user" class="btn btn-secondary">Cancel</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>