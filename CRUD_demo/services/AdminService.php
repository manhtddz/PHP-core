<?php
require_once(dirname(__DIR__) . "/repositories/AdminRepository.php");
require_once(dirname(__DIR__) . "/utils/Validation.php");
require_once(dirname(__DIR__) . "/utils/FileHelper.php");

// require_once '../dto/UserRequest.php';

class AdminService
{
    private $adminRepo;
    private $fileHelper;
    public function __construct()
    {
        $this->adminRepo = new AdminRepository();
        $this->fileHelper = new FileHelper();
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
    public function getAllAdmins($page)
    {
        return $this->adminRepo->getAll(pageNumber: $page);
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
        if (empty($data['password']) || !Validation::validatePassword(trim($data['password']))) {
            $errors['passwordError'] = "Mật khẩu ko hợp lệ";
        } else {
            $data["password"] = password_hash(trim($data['password']), PASSWORD_DEFAULT);
        }
        if (empty($data['name']) || strlen(trim($data['name'])) > 128) {
            $errors['nameError'] = "Tên phải từ 1 đến 128 ký tự";
        }
        if (empty($data['email']) || strlen(trim($data['email'])) > 128 || !Validation::validateEmail(trim($data['email']))) {
            $errors['emailError'] = "Email ko hợp lệ";
        } else if ($this->adminRepo->existedEmail(trim($data['email']))) {
            $errors['emailError'] = "Email đã tồn tại";
        }
        if (empty($data['avatar']) || strlen(trim($data['avatar'])) > 128) {
            $errors['avatarError'] = "Chưa chọn file hoặc tên file quá dài";
        } else if (!$this->fileHelper->isImageFile($data['avatar'])) {
            $errors['avatarError'] = "Chọn sai loại file";
        }
        if (!empty($errors)) {
            throw new ValidationException($errors);
        }
        $this->fileHelper->uploadFile($data['avatar']);
        return $this->adminRepo->create($data);
    }
    public function updateAdmin($id, AdminUpdateRequest $admin)
    {
        $data = $admin->toArray();
        $errors = [];
        if (empty($data['name']) || strlen(trim($data['name'])) > 128) {
            $errors['nameError'] = "Tên phải từ 1 đến 128 ký tự";
        }
        if (
            empty($data['email']) || strlen(trim($data['email'])) > 128 ||
            !Validation::validateEmail(trim($data['email']))
        ) {
            $errors['emailError'] = "Email ko hợp lệ";
        } else {
            $checkedId = $this->adminRepo->existedEmail(trim($data['email']));
            if ($checkedId != $id) {
                $errors['emailError'] = "Email đã tồn tại";
            }
        }
        if (empty($data['avatar']) || strlen(trim($data['avatar'])) > 128) {
            $errors['avatarError'] = "Avatar phải từ 1 đến 128 ký tự";
            $this->fileHelper->deleteFile($data['avatar']);
        } else if (!$this->fileHelper->isImageFile($data['avatar'])) {
            $errors['avatarError'] = "Chọn sai loại file";
        }
        if (!empty($errors)) {
            throw new ValidationException($errors);
        }
        if ($data['avatar'] !== $_POST['current_avatar']) {
            $this->fileHelper->uploadFile($data['avatar']);
            $this->fileHelper->deleteFile($_POST['current_avatar']);
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