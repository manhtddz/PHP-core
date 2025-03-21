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
    public function findByEmail(string $email, $typeOfUser)
    {
        try {
            if ($typeOfUser == "admins") {
                $stmt = $this->db->prepare(
                    "SELECT role_type, id, password FROM {$typeOfUser} 
                    WHERE email = :email AND del_flag = 0"
                );
            } elseif ($typeOfUser == "users") {
                $stmt = $this->db->prepare(
                    "SELECT id, password, status FROM {$typeOfUser} 
                    WHERE email = :email AND del_flag = 0"
                );
            } else {
                echo 'sai roi';
                exit;
            }
            $stmt->execute([
                'email' => $email,
            ]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            // print_r($data);
            // exit;
            return $data ?: null;
        } catch (Throwable $e) {
            echo $e->getMessage();
            return null;
        }
    }

}
