<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông tin người dùng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">My Website</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="?&action=logout">Log out</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-4">
        <h2>Thông tin người dùng</h2>
        <table class="table table-bordered">
            <tr>
                <th>ID</th>
                <td><?= htmlspecialchars($user->getId()) ?></td>
            </tr>
            <tr>
                <th>Tên</th>
                <td><?= htmlspecialchars($user->getName()) ?></td>
            </tr>
            <tr>
                <th>Email</th>
                <td><?= htmlspecialchars($user->getEmail()) ?></td>
            </tr>
            <tr>
                <th>Trạng thái</th>
                <td><?= $user->getStatus() == 1 ? 'Hoạt động' : 'Bị khóa' ?></td>
            </tr>
            <tr>
                <th>Avatar</th>
                <td>
                    <?php if ($user->getAvatar()): ?>

                        <img src="uploads/images/avatar/<?= $user->getAvatar() ?>" width="50" height="50"
                            class="rounded-circle" title="<?= $user->getAvatar() ?>">
                    <?php else: ?>
                        <span class="text-muted">Không có ảnh</span>
                    <?php endif; ?>
                </td>
            </tr>
        </table>
        <!-- <a href="index.php" class="btn btn-secondary">Quay lại</a> -->
    </div>
</body>

</html>