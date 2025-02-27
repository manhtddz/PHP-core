<?php
class AdminUpdateRequest
{
    private $name;
    private $email;
    private $avatar;
    private $role_type;
    // private $ins_id;
    private $upd_id;
    // private $ins_datetime;
    private $upd_datetime;
    private $del_flag;

    // Constructor
    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            # code...
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
        $this->upd_id = $data['upd_id'];
        $this->upd_datetime = $data['upd_datetime'] ?? date('Y-m-d H:i:s');
        $this->del_flag = $data['del_flag'] ?? '0';
    }

    // Getters
    public function getName()
    {
        return $this->name;
    }
    // public function getFacebookId()
    // {
    //     return $this->facebook_id;
    // }

    public function getEmail()
    {
        return $this->email;
    }
    public function getAvatar()
    {
        return $this->avatar;
    }
    public function getRoleType()
    {
        return $this->role_type;
    }
    // public function getInsId()
    // {
    //     return $this->ins_id;
    // }
    public function getUpdId()
    {
        return $this->upd_id;
    }
    // public function getInsDatetime()
    // {
    //     return $this->ins_datetime;
    // }
    public function getUpdDatetime()
    {
        return $this->upd_datetime;
    }
    public function getDelFlag()
    {
        return $this->del_flag;
    }

    // Setters
    public function setName($name)
    {
        $this->name = $name;
    }
    // public function setFacebookId($facebook_id)
    // {
    //     $this->facebook_id = $facebook_id;
    // }

    public function setEmail($email)
    {
        $this->email = $email;
    }
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;
    }
    public function setRollType($role_type)
    {
        $this->role_type = $role_type;
    }
    // public function setInsId($ins_id)
    // {
    //     $this->ins_id = $ins_id;
    // }
    public function setUpdId($upd_id)
    {
        $this->upd_id = $upd_id;
    }
    // public function setInsDatetime($ins_datetime)
    // {
    //     $this->ins_datetime = $ins_datetime;
    // }
    public function setUpdDatetime($upd_datetime)
    {
        $this->upd_datetime = $upd_datetime;
    }
    public function setDelFlag($del_flag)
    {
        $this->del_flag = $del_flag;
    }

    // Hàm chuyển đổi object thành mảng
    public function toArray()
    {
        return get_object_vars($this);
    }
}
