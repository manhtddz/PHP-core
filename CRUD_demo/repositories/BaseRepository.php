<?php
// require_once '../config/Database.php';
require_once(dirname(__DIR__) . "/config/Database.php");

require_once(dirname(__DIR__) . "/interfaces/IRepository.php");

// require_once '../interfaces/IRepository.php';

abstract class BaseRepository implements IRepository
{
    protected $db;
    protected $table;
    protected $model;
    public function __construct()
    {
        $this->db = Database::getInstance()->conn;
    }

    //thêm attribute, limit, offset
    public function getAll(int $limit = 6, int $pageNumber = 1)
    {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE del_flag = 0";
            $offset = ($pageNumber - 1) * $limit;
            $countSql = str_replace("SELECT *", "SELECT COUNT(*) as total", $sql);

            $countStmt = $this->db->prepare($countSql);
            $countStmt->execute();
            $totalRecords = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];
            $totalPages = ceil($totalRecords / $limit);

            $results = $this->db->query($sql . " LIMIT $limit OFFSET $offset")->fetchAll(PDO::FETCH_ASSOC);
            return [array_map(fn($data) => new $this->model($data), $results), $totalPages];

        } catch (Throwable $e) {
            echo $e->getMessage();
            return null;
        }
    }
    public function findByEmail(string $email)
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE email = :email AND del_flag = 0");
            $stmt->execute(['email' => $email]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            return $data ? new $this->model($data) : null;
        } catch (Throwable $e) {
            echo $e->getMessage();
            return null;
        }
    }
    public function findById(int $id)
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = :id  AND del_flag = 0");
            $stmt->execute(['id' => $id]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            return $data ? new $this->model($data) : null;
        } catch (Throwable $e) {
            echo $e->getMessage();
            return null;
        }
    }
    public function search(array $data, int $limit = 6, int $pageNumber = 1)
    {
        $sql = "SELECT * FROM {$this->table} WHERE del_flag = 0";
        $offset = ($pageNumber - 1) * $limit;
        $params = [];

        $conditions = [];

        if (!empty($data['name'])) {
            $conditions[] = "name LIKE :name";
            $params['name'] = "%{$data['name']}%";
        }

        if (!empty($data['email'])) {
            $conditions[] = "email LIKE :email";
            $params['email'] = "%{$data['email']}%";
        }

        if (!empty($conditions)) {
            $sql .= " AND (" . implode(" OR ", $conditions) . ")";
        }
        $countSql = str_replace("SELECT *", "SELECT COUNT(*) as total", $sql);

        $countStmt = $this->db->prepare($countSql);
        $countStmt->execute($params);
        $totalRecords = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];

        $totalPages = ceil($totalRecords / $limit);

        $stmt = $this->db->prepare($sql . " LIMIT $limit OFFSET $offset");

        $stmt->execute($params);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return [array_map(fn($data) => new $this->model($data), $results), $totalPages];
    }

    // Thêm user mới
    public function create(array $data)
    {
        // print_r($data);

        try {
            $columns = implode(", ", array_keys($data));
            // print ($columns);
            $values = ":" . implode(", :", array_keys($data));// :value1, :value2, :value3
            // print ($values);

            $stmt = $this->db->prepare("INSERT INTO {$this->table} ({$columns}) VALUES ({$values})");
            return $stmt->execute($data);
        } catch (Throwable $e) {
            echo $e->getMessage();
        }

    }

    // Cập nhật user
    public function update(int $id, array $data)
    {
        try {
            $fields = implode(", ", array_map(fn($key) => "{$key} = :{$key}", array_keys($data)));
            // print ($fields);

            $stmt = $this->db->prepare("UPDATE {$this->table} SET {$fields} WHERE id = :id");
            $data['id'] = $id;
            return $stmt->execute($data);
        } catch (\Throwable $e) {
            echo $e->getMessage();
        }
    }

    // Xóa user
    public function delete(int $id)
    {
        try {
            $stmt = $this->db->prepare("UPDATE {$this->table} SET del_flag = 1 WHERE id = :id");
            return $stmt->execute([
                'id' => $id,
            ]);
        } catch (\Throwable $e) {
            echo $e->getMessage();
        }
    }
    public function existedEmail(string $email)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE email = :email");
        $stmt->execute([
            'email' => $email
        ]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            return $result['id'];
        } else {
            return null;
        }
    }
}
?>