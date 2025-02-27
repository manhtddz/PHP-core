<?php
class SearchRequest
{
    private $email;
    private $name;
    // Constructor
    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            # code...
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    // Getters
    public function getName()
    {
        return $this->name;
    }
    public function getEmail()
    {
        return $this->email;
    }


    // Setters
    public function setName($name)
    {
        $this->name = $name;
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
