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
    public function getAllUsers($pageNumber, $sort)
    {
        return $this->userRepo->getAll(pageNumber: $pageNumber, sort: $sort);
    }

    public function search(SearchRequest $request, int $page, $sort)
    {
        $data = $request->toArray();
        return $this->userRepo->search($data, pageNumber: $page, sort: $sort);
    }

    public function createUser(UserCreateRequest $user)
    {
        $data = $user->toArray();
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
        if ($this->userRepo->existedEmail(trim($data['email']))) {
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
        return $this->userRepo->create($data);
    }
    public function updateUser($id, UserUpdateRequest $user)
    {
        $data = $user->toArray();
        $errors = [];

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
            $checkedId = $this->userRepo->existedEmail(trim($data['email']));
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