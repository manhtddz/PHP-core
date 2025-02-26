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
    public function getAll($limit = 10, $pageNumber = 1)
    {
        try {
            // $selected = explode(',', $select);
            $offset = ($pageNumber - 1) * $limit;
            $results = $this->db->query("SELECT * FROM {$this->table} WHERE del_flag = 0 LIMIT $limit OFFSET $offset")->fetchAll(PDO::FETCH_ASSOC);
            return array_map(fn($data) => new $this->model($data), $results);

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
    public function findById($id)
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
    public function update($id, array $data)
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
    public function delete($id)
    {
        try {
            $del_flag = 1;
            $stmt = $this->db->prepare("UPDATE {$this->table} SET del_flag = :del_flag WHERE id = :id");
            return $stmt->execute([
                'id' => $id,
                'del_flag' => $del_flag,
            ]);
        } catch (\Throwable $e) {
            echo $e->getMessage();
        }
    }
}
?>