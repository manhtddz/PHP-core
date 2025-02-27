<?php
require_once(dirname(__DIR__) . "/repositories/UserRepository.php");
require_once(dirname(__DIR__) . "/utils/Validation.php");

// require_once '../dto/UserRequest.php';

class UserService
{
    private $userRepo;
    public function __construct()
    {
        $this->userRepo = new UserRepository();
    }

    public function login(LoginRequest $request)
    {
        $user = $this->userRepo->findByEmail($request->getEmail());
        if (empty($user)) {
            throw new Exception("Email ko tồn tại");
        }
        if (password_verify($request->getPassword(), $user->getPassword())) {
            return $user;
        } else {
            throw new Exception("Sai email hoặc mật khẩu");
        }

    }
    public function getUserById($id)
    {
        return $this->userRepo->findById($id);
    }
    public function getAllUsers()
    {
        return $this->userRepo->getAll();
    }

    public function search(SearchRequest $request)
    {
        $data = $request->toArray();
        return $this->userRepo->search($data);
    }

    public function createUser(UserCreateRequest $user)
    {
        $data = $user->toArray();
        $errors = [];
        if (strlen(empty($data['password'])) || !Validation::validatePassword(trim($data['password']))) {
            $errors['passwordError'] = "Mật khẩu ko hợp lệ";
        } else {
            $data["password"] = password_hash(trim($data['password']), PASSWORD_DEFAULT);
        }
        if (strlen(empty($data['facebook_id'])) || strlen(trim($data['facebook_id'])) > 64) {
            $errors['facebookIdError'] = "Facebook id phải từ 1 đến 64 ký tự";
        }
        if (strlen(empty($data['name'])) || strlen(trim($data['name'])) > 128) {
            $errors['nameError'] = "Tên phải từ 1 đến 128 ký tự";
        }
        if (strlen(empty($data['email'])) || strlen(trim($data['email'])) > 128 || !Validation::validateEmail(trim($data['email']))) {
            $errors['emailError'] = "Email ko hợp lệ";
        } else if ($this->userRepo->existedEmail(trim($data['email']))) {
            $errors['emailError'] = "Email đã tồn tại";
        }
        if (strlen(empty($data['avatar'])) || strlen(trim($data['avatar'])) > 128) {
            $errors['avatarError'] = "Avatar phải từ 1 đến 128 ký tự";
        }
        if (!empty($errors)) {
            throw new ValidationException($errors);
        }
        return $this->userRepo->create($data);
    }
    public function updateUser($id, UserUpdateRequest $user)
    {
        $data = $user->toArray();
        $errors = [];
        // if (strlen(empty($data['password'])) || !Validation::validatePassword($data['password'])) {
        //     $errors['passwordError'] = "Mật khẩu ko hợp lệ";
        // } else {
        //     $data["password"] = password_hash($data["password"], PASSWORD_DEFAULT);
        // }
        if (strlen(empty($data['facebook_id'])) || strlen($data['facebook_id']) > 64) {
            $errors['facebookIdError'] = "Facebook id phải từ 1 đến 64 ký tự";
        }
        if (strlen(empty($data['name'])) || strlen(trim($data['name'])) > 128) {
            $errors['nameError'] = "Tên phải từ 1 đến 128 ký tự";
        }
        if (strlen(empty($data['email'])) || strlen(trim($data['email'])) > 128 || !Validation::validateEmail(trim($data['email']))) {
            $errors['emailError'] = "Email ko hợp lệ";
        } else {
            $checkedId = $this->userRepo->existedEmail(trim($data['email']));
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
        return $this->userRepo->update($id, $data);
    }
    public function deleteUser($id)
    {
        $user = $this->userRepo->findById($id);
        if (empty($user)) {
            throw new Exception("not found");
        }
        return $this->userRepo->delete($id);
    }
}
?>