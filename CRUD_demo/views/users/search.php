<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Tìm Kiếm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?= require_once(dirname(__DIR__) . "/layouts/header.php") ?>

    <div class="container mt-5">
        <h2 class="mb-4">Tìm Kiếm Người Dùng</h2>

        <!-- Form tìm kiếm -->
        <form method="POST" action="?controller=user&action=search" class="mb-3">
            <div class="row">
                <div class="col-md-4">
                    <label for="name" class="form-label">Tên người dùng:</label>
                    <input type="text" class="form-control" id="name" name="name">
                </div>
                <div class="col-md-4">
                    <label for="email" class="form-label">Email:</label>
                    <input type="text" class="form-control" id="email" name="email">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">Tìm kiếm</button>
                    <a href="search.php" class="btn btn-secondary">Reset</a>
                </div>
            </div>
        </form>

        <!-- Hiển thị kết quả tìm kiếm -->
        <?php if (isset($users) && !empty($users)): ?>
            <h3>Kết quả tìm kiếm:</h3>
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Tên</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= $user->getId() ?></td>
                            <td><?= htmlspecialchars($user->getName()) ?></td>
                            <td><?= htmlspecialchars($user->getEmail()) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-muted">Không tìm thấy người dùng nào.</p>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>