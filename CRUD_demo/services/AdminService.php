<?php
require_once(dirname(__DIR__) . "/repositories/AdminRepository.php");
require_once(dirname(__DIR__) . "/utils/Validation.php");
require_once(dirname(__DIR__) . "/services/BaseService.php");

// require_once '../dto/UserRequest.php';

class AdminService extends BaseService
{
    private $adminRepo;
    public function __construct()
    {
        parent::__construct();
        $this->adminRepo = new AdminRepository();
    }
    public function getAdminById($id)
    {
        $admin = $this->adminRepo->findById($id);
        if (empty($admin)) {
            throw new Exception("Admin is not existed");
        }
        return $admin;
    }
    public function getAllAdmins($pageNumber, $sort)
    {
        return $this->adminRepo->getAll(pageNumber: $pageNumber, sort: $sort);
    }

    public function search(SearchRequest $request, int $page, $sort)
    {
        $data = $request->toArray();
        return $this->adminRepo->search($data, pageNumber: $page, sort: $sort);
    }

    public function createAdmin(AdminCreateRequest $admin)
    {
        $data = $admin->toArray();
        $errors = [];
        if (!Validation::validatePassword(trim($data['password']))) {
            $errors['passwordError'] = "The password format is incorrect";
        } else {
            $data["password"] = password_hash(trim($data['password']), PASSWORD_DEFAULT);
        }
        if (empty($data['name'])) {
            $errors['nameError'] = "Name cannot be blank";
        }
        if (strlen(trim($data['name'])) > 128) {
            $errors['nameError'] = "Name max length is 128";
        }
        if (strlen($data['email']) > 128) {
            $errors['emailError'] = "Email max length is 128";
        }
        if (!Validation::validateEmail($data['email'])) {
            $errors['emailError'] = "The email format is incorrect";
        }
        if ($this->adminRepo->existedEmail(trim($data['email']))) {
            $errors['emailError'] = "Email is already existed";
        }
        if (empty($data['avatar'])) {
            $errors['avatarError'] = "You have to choose avatar file";
        }
        if (strlen(trim($data['avatar'])) > 128) {
            $errors['avatarError'] = "Avatar name file max lenght is 128";
        }
        if (!$this->fileHelper->isImageFile($data['avatar'])) {
            $errors['avatarError'] = "Avatar must be png or jpg file";
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
        if (!empty($data['password'])) {
            $admin = $this->adminRepo->findById($id);

            if (password_verify($data['passwordConfirm'], $admin->getPassword())) {
                unset($data['passwordConfirm']);
                $data["password"] = password_hash(trim($data['password']), PASSWORD_DEFAULT);
            } else {
                $errors['passwordError'] = "Cannot confirm old password";
            }
        } else {
            unset($data['password']);
        }
        if (empty($data['name'])) {
            $errors['nameError'] = "Name cannot be blank";
        }
        if (strlen(trim($data['name'])) > 128) {
            $errors['nameError'] = "Name max length is 128";
        }
        if (strlen($data['email']) > 128) {
            $errors['emailError'] = "Email max length is 128";
        }
        if (empty($data['avatar'])) {
            $errors['avatarError'] = "You have to choose avatar file";
        }
        if (strlen(trim($data['avatar'])) > 128) {
            $errors['avatarError'] = "Avatar name file max lenght is 128";
        }
        if (!$this->fileHelper->isImageFile($data['avatar'])) {
            $errors['avatarError'] = "Avatar must be png or jpg file";
        }
        if (!Validation::validateEmail(trim($data['email']))) {
            $errors['emailError'] = "The email format is incorrect";
        } else {
            $checkedId = $this->adminRepo->existedEmail(trim($data['email']));
            if ($checkedId != $id) {
                $errors['emailError'] = "Email is already existed";
            }
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
        $admin = $this->adminRepo->findById($id);
        if (empty($admin)) {
            throw new Exception("Admin is not existed");
        }
        return $this->adminRepo->delete($id);
    }
}
?>