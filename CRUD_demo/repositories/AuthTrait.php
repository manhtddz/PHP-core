<?php
require_once(dirname(__DIR__) . "/repositories/BaseRepository.php");
require_once(dirname(__DIR__) . "/models/Admin.php");
require_once(dirname(__DIR__) . "/config/Database.php");

// require_once '../repositories/BaseRepository.php';
// require_once '../models/User.php';

trait AuthTrait
{
    protected $db;
    public function initDB()
    {
        $this->db = Database::getInstance()->conn;
    }
    public function findByEmail(string $email)
    {
        try {
            $stmt = $this->db->prepare(
                "SELECT 'user' AS role, id, password FROM users WHERE email = :email AND del_flag = 0
                UNION
                SELECT 'admin' AS role, id, password FROM admins WHERE email = :email AND del_flag = 0"
            );
            $stmt->execute(['email' => $email]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            print_r($data);
            return $data ?: null;
        } catch (Throwable $e) {
            echo $e->getMessage();
            return null;
        }
    }
}
