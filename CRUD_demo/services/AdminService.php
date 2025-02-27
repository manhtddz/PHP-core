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
        $admin = $this->adminRepo->findByEmail($request->getEmail());
        if (empty($admin)) {
            throw new Exception("Email ko tồn tại");
        }
        if (password_verify($request->getPassword(), $admin->getPassword())) {
            return $admin;
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

    public function search(SearchRequest $request)
    {
        $data = $request->toArray();
        return $this->adminRepo->search($data);
    }

    public function createAdmin(AdminCreateRequest $admin)
    {
        $data = $admin->toArray();
        $errors = [];
        if (strlen(empty($data['password'])) || !Validation::validatePassword(trim($data['password']))) {
            $errors['passwordError'] = "Mật khẩu ko hợp lệ";
        } else {
            $data["password"] = password_hash(trim($data['password']), PASSWORD_DEFAULT);
        }
        if (strlen(empty($data['name'])) || strlen(trim($data['name'])) > 128) {
            $errors['nameError'] = "Tên phải từ 1 đến 128 ký tự";
        }
        if (strlen(empty($data['email'])) || strlen(trim($data['email'])) > 128 || !Validation::validateEmail(trim($data['email']))) {
            $errors['emailError'] = "Email ko hợp lệ";
        } else if ($this->adminRepo->existedEmail(trim($data['email']))) {
            $errors['emailError'] = "Email đã tồn tại";
        }
        if (strlen(empty($data['avatar'])) || strlen(trim($data['avatar'])) > 128) {
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
        if (strlen(empty($data['name'])) || strlen(trim($data['name'])) > 128) {
            $errors['nameError'] = "Tên phải từ 1 đến 128 ký tự";
        }
        if (strlen(empty($data['email'])) || strlen(trim($data['email'])) > 128 || !Validation::validateEmail(trim($data['email']))) {
            $errors['emailError'] = "Email ko hợp lệ";
        } else {
            $checkedId = $this->adminRepo->existedEmail(trim($data['email']));
            if ($checkedId != $id) {
                $errors['emailError'] = "Email đã tồn tại";
            }
        }
        if (strlen(empty($data['avatar'])) || strlen(trim($data['avatar'])) > 128) {
            $errors['avatarError'] = "Avatar phải từ 1 đến 128 ký tự";
        }
        if (!empty($errors)) {
            throw new ValidationException($errors);
        }
        return $this->adminRepo->update($id, $data);
    }
    public function deleteAdmin($id)
    {
        $user = $this->adminRepo->findById($id);
        if (empty($user)) {
            throw new Exception("not found");
        }
        return $this->adminRepo->delete($id);
    }
}
?>