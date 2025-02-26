<?php
require_once(dirname(__DIR__) . "/repositories/AdminRepository.php");
require_once(dirname(__DIR__) . "/utils/Validation.php");

// require_once '../dto/UserRequest.php';

class AdminService
{
    private $adminRepo;
    public function __construct()
    {
        $this->adminRepo = new AdminRepository();
    }
    public function login(LoginRequest $request)
    {
        $user = $this->adminRepo->findByEmail($request->getEmail());
        if (empty($user)) {
            throw new Exception("Sai email hoặc mật khẩu");
        }
        if (password_verify($request->getPassword(), $user->getPassword())) {
            return $user;
        } else {
            throw new Exception("Sai email hoặc mật khẩu");
        }

    }
    public function getAdminById($id)
    {
        return $this->adminRepo->findById($id);
    }
    public function getAllAdmins()
    {
        return $this->adminRepo->getAll();
    }

    public function createAdmin(AdminCreateRequest $admin)
    {
        $data = $admin->toArray();
        $errors = [];
        if (strlen(empty($data['password'])) || !Validation::validatePassword($data['password'])) {
            $errors['passwordError'] = "Mật khẩu ko hợp lệ";
        }
        if (strlen(empty($data['name'])) || strlen($data['name']) > 128) {
            $errors['nameError'] = "Tên phải từ 1 đến 128 ký tự";
        }
        if (strlen(empty($data['email'])) || strlen($data['email']) > 128 || !Validation::validateEmail($data['email'])) {
            $errors['emailError'] = "Email phải từ 1 đến 128 ký tự";
        }
        if (strlen(empty($data['avatar'])) || strlen($data['avatar']) > 128) {
            $errors['avatarError'] = "Avatar phải từ 1 đến 128 ký tự";
        }
        if (!empty($errors)) {
            throw new ValidationException($errors);
        }
        return $this->adminRepo->create($data);
    }
    public function updateAdmin($id, AdminUpdateRequest $admin)
    {
        $data = $admin->toArray();
        $errors = [];
        if (strlen(empty($data['name'])) || strlen($data['name']) > 128) {
            $errors['nameError'] = "Tên phải từ 1 đến 128 ký tự";
        }
        if (strlen(empty($data['email'])) || strlen($data['email'] || Validation::validateEmail($data['email'])) > 128) {
            $errors['emailError'] = "Email phải từ 1 đến 128 ký tự";
        }
        if (strlen(empty($data['avatar'])) || strlen($data['avatar']) > 128) {
            $errors['avatarError'] = "Avatar phải từ 1 đến 128 ký tự";
        }
        if (!empty($errors)) {
            throw new ValidationException($errors);
        }
        return $this->adminRepo->update($id, $data);
    }
    public function deleteAdmin($id)
    {
        return $this->adminRepo->delete($id);
    }
}
?>