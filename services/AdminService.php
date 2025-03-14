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
            throw new Exception("Data does not exist");
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

        if (empty($data['password'])) {
            $errors['passwordError'] = "Password cannot be blank";
        } elseif (strlen($data['password']) < 3 || strlen($data['password']) > 100) {
            $errors['passwordError'] = "Password length must be between 3 and 100 characters";
        }

        if (empty($data['passwordVerify'])) {
            $errors['passwordVerifyError'] = "Password verify cannot be blank";
        } elseif (strlen($data['passwordVerify']) < 3 || strlen($data['passwordVerify']) > 100) {
            $errors['passwordVerifyError'] = "Password verify length must be between 3 and 100 characters";
        } elseif ($data['password'] !== $data['passwordVerify']) {
            $errors['passwordError'] = "Password verify must match the password";
        }

        if (empty($data['name'])) {
            $errors['nameError'] = "Name cannot be blank";
        }
        if (strlen(trim($data['name'])) > 128) {
            $errors['nameError'] = "Name max length is 128";
        }
        if (empty($data['email'])) {
            $errors['emailError'] = "Email cannot be blank";
        } else {
            if (strlen($data['email']) > 128) {
                $errors['emailError'] = "Email max length is 128";
            }
            if (!Validation::validateEmail($data['email'])) {
                $errors['emailError'] = "The email format is incorrect";
            }
            if ($this->adminRepo->existedEmail(trim($data['email']))) {
                $errors['emailError'] = "Email is already existed";
            }
        }
        if (empty($data['role_type'])) {
            $errors['roleTypeError'] = "Role is required";
        }
        if (empty($data['avatar']) || $data['avatar'] === '') {
            $errors['avatarError'] = "File required";
        } else {
            if (strlen(trim($data['avatar'])) > 128) {
                $errors['avatarError'] = "Avatar name file max lenght is 128";
            }
            if (!$this->fileHelper->isImageFile($data['avatar'])) {
                $errors['avatarError'] = "Avatar must be png or jpg file";
            }
        }
        if (!empty($errors)) {
            throw new ValidationException($errors);
        }
        $data["password"] = password_hash($data["password"], PASSWORD_DEFAULT);
        unset($data['passwordVerify']);
        $this->fileHelper->uploadFile($data['avatar']);
        return $this->adminRepo->create($data);
    }
    public function updateAdmin($id, AdminUpdateRequest $admin)
    {
        $data = $admin->toArray();
        $errors = [];
        if (!empty($data['password'])) {
            if (strlen($data['password']) < 3 || strlen($data['password']) > 100) {
                $errors['passwordError'] = "Password length must be between 3 and 100 characters";
            }

            if (empty($data['passwordVerify'])) {
                $errors['passwordVerifyError'] = "Password verify cannot be blank";
            } elseif (strlen($data['passwordVerify']) < 3 || strlen($data['passwordVerify']) > 100) {
                $errors['passwordVerifyError'] = "Password verify length must be between 3 and 100 characters";
            } elseif ($data['password'] !== $data['passwordVerify']) {
                $errors['passwordError'] = "Password verify must match the password";
            }
        } else {
            unset($data['password']);
            unset($data['passwordVerify']);
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
        if (empty($data['role_type'])) {
            $errors['roleTypeError'] = "Role is required";
        }
        // var_dump($data['avatar']);
        // exit;
        if (empty($data['avatar']) || $data['avatar'] === '') {
            $errors['avatarError'] = "File required";
        } else {
            if (strlen(trim($data['avatar'])) > 128) {
                $errors['avatarError'] = "Avatar name file max lenght is 128";
            }
            if (!$this->fileHelper->isImageFile($data['avatar'])) {
                $errors['avatarError'] = "Avatar must be png or jpg file";
            }
        }
        if (empty($data['email'])) {
            $errors['emailError'] = "Email cannot be blank";
        } else {
            if (strlen($data['email']) > 128) {
                $errors['emailError'] = "Email max length is 128";
            }
            if (!Validation::validateEmail(trim($data['email']))) {
                $errors['emailError'] = "The email format is incorrect";
            } else {
                $checkedId = $this->adminRepo->existedEmail(trim($data['email']));
                if ($checkedId != $id) {
                    $errors['emailError'] = "Email is already existed";
                }
            }
        }

        if (!empty($errors)) {
            throw new ValidationException($errors);
        }
        if ($data['avatar'] !== $_POST['current_avatar']) {
            $this->fileHelper->uploadFile($data['avatar']);
            $this->fileHelper->deleteFile($_POST['current_avatar']);
        }
        $data["password"] = password_hash($data["password"], PASSWORD_DEFAULT);
        unset($data['passwordVerify']);
        return $this->adminRepo->update($id, $data);
    }
    public function deleteAdmin($id)
    {
        $admin = $this->adminRepo->findById($id);
        if (empty($admin)) {
            throw new Exception("Data does not exist");
        }
        return $this->adminRepo->delete($id);
    }
}
?>