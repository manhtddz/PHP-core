<?php
require_once(dirname(__DIR__) . "/repositories/UserRepository.php");
require_once(dirname(__DIR__) . "/utils/Validation.php");
require_once(dirname(__DIR__) . "/services/BaseService.php");


// require_once '../dto/UserRequest.php';

class UserService extends BaseService
{
    private $userRepo;

    public function __construct()
    {
        parent::__construct();
        $this->userRepo = new UserRepository();
    }

    public function login(LoginRequest $request)
    {
        $user = $this->userRepo->findByEmail($request->getEmail());
        if (empty($user)) {
            throw new Exception("Email rỗng hoặc ko tồn tại");
        }
        if (password_verify($request->getPassword(), $user->getPassword())) {
            return $user;
        } else {
            throw new Exception("Sai email hoặc mật khẩu");
        }

    }
    public function getUserById($id)
    {
        $user = $this->userRepo->findById($id);
        if (empty($user)) {
            throw new Exception("User không tồn tại");
        }
        return $user;
    }
    public function getAllUsers($pageNumber)
    {
        return $this->userRepo->getAll(pageNumber: $pageNumber);
    }

    public function search(SearchRequest $request,$page)
    {
        $data = $request->toArray();
        return $this->userRepo->search($data, pageNumber: $page);
    }

    public function createUser(UserCreateRequest $user)
    {
        $data = $user->toArray();
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
        } else if ($this->userRepo->existedEmail(trim($data['email']))) {
            $errors['emailError'] = "Email đã tồn tại";
        }
        if (empty($data['avatar']) || strlen(trim($data['avatar'])) > 128) {
            $errors['avatarError'] = "Chưa chọn file hoặc tên file quá dài";
        }
        if (!$this->fileHelper->isImageFile($data['avatar'])) {
            $errors['avatarError'] = "Chọn sai loại file";
        }
        if (!empty($errors)) {
            throw new ValidationException($errors);
        }
        $this->fileHelper->uploadFile($data['avatar']);
        return $this->userRepo->create($data);
    }
    public function updateUser($id, UserUpdateRequest $user)
    {
        $data = $user->toArray();
        $errors = [];

        if (empty($data['name']) || strlen(trim($data['name'])) > 128) {
            $errors['nameError'] = "Tên phải từ 1 đến 128 ký tự";
        }
        if (empty($data['email']) || strlen(trim($data['email'])) > 128 || !Validation::validateEmail(trim($data['email']))) {
            $errors['emailError'] = "Email ko hợp lệ";
        } else {
            $checkedId = $this->userRepo->existedEmail(trim($data['email']));
            if ($checkedId != $id) {
                $errors['emailError'] = "Email đã tồn tại";
            }
        }
        if (empty($data['avatar']) || strlen(trim($data['avatar'])) > 128) {
            $errors['avatarError'] = "Avatar phải từ 1 đến 128 ký tự";
            $this->fileHelper->deleteFile($data['avatar']);
        }
        if (!$this->fileHelper->isImageFile($data['avatar'])) {
            $errors['avatarError'] = "Chọn sai loại file";
        }
        if (!empty($errors)) {
            throw new ValidationException($errors);
        }
        if ($data['avatar'] !== $_POST['current_avatar']) {
            $this->fileHelper->uploadFile($data['avatar']);
            $this->fileHelper->deleteFile($_POST['current_avatar']);
        }
        return $this->userRepo->update($id, $data);
    }
    public function deleteUser($id)
    {
        $user = $this->userRepo->findById($id);
        if (empty($user)) {
            throw new Exception("User ko tồn tại");
        }
        return $this->userRepo->delete($id);
    }
}
?>