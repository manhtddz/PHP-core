<?php
class LoginRequest
{
    private $email;
    private $password;
    // Constructor
    public function __construct($data = [])
    {
        if (!empty($data)) {
            $this->password = $data['password'] ?? '';
            $this->email = $data['email'] ?? '';
        }
    }

    // Getters
    public function getPassword()
    {
        return $this->password;
    }
    public function getEmail()
    {
        return $this->email;
    }


    // Setters
    public function setPassword($password)
    {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }
    public function setEmail($email)
    {
        $this->email = $email;
    }

    // Hàm chuyển đổi object thành mảng
    public function toArray()
    {
        return get_object_vars($this);
    }
}
