<?php
require_once(dirname(__DIR__) . "/repositories/BaseRepository.php");
require_once(dirname(__DIR__) . "/models/User.php");

// require_once '../repositories/BaseRepository.php';
// require_once '../models/User.php';

class UserRepository extends BaseRepository
{
    protected $table = "users";
    protected $model = User::class;
    public function findByFacebookId(string $facebook_id)
    {
        try {

            $stmt = $this->db->prepare(
                "SELECT id, status, del_flag FROM users WHERE facebook_id = :facebook_id LIMIT 1"
            );

            $stmt->execute([
                'facebook_id' => $facebook_id,
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
