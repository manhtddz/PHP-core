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

    // public function login(LoginRequest $request)
    // {
    //     $user = $this->userRepo->findByEmail($request->getEmail());
    //     if (empty($user)) {
    //         throw new Exception("Email is not existed");
    //     }
    //     if (password_verify($request->getPassword(), $user->getPassword()) && $user->getStatus() === 1) {
    //         return $user;
    //     } else {
    //         throw new Exception("Email is banned or wrong password");
    //     }

    // }
    public function getUserById($id)
    {
        $user = $this->userRepo->findById($id);
        if (empty($user)) {
            throw new Exception("Data does not exist");
        }
        return $user;
    }
    public function getAllUsers($pageNumber, $sort)
    {
        return $this->userRepo->getAll(pageNumber: $pageNumber, sort: $sort);
    }

    public function findByFacebookId($facebook_id)
    {
        return $this->userRepo->findByFacebookId($facebook_id);
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
        $tempDir = __DIR__ . "/../uploads/images/temp/";

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
            if ($this->userRepo->existedEmail(trim($data['email']))) {
                $errors['emailError'] = "Email is already existed";
            }
        }
        if (empty($data['status'])) {
            $errors['statusError'] = "Status is required";
        }
        if (empty($data['avatar']) || $data['avatar'] === '') {
            $errors['avatarError'] = "File required";
        } else {
            if (strlen(trim($data['avatar'])) > 128) {
                $errors['avatarError'] = "Avatar name file max lenght is 128";
            }
            if (!$this->fileHelper->isImageFile($data['avatar'])) {
                $errors['avatarError'] = "Avatar must be png or jpg file";
                $this->fileHelper->deleteFile($data['avatar'], $tempDir);

            }
        }
        if (!empty($errors)) {
            throw new ValidationException($errors);
        }
        $data["password"] = password_hash($data["password"], PASSWORD_DEFAULT);
        unset($data['passwordVerify']);
        $uploadDir = __DIR__ . "/../uploads/images/avatar/";
        if (file_exists($tempDir . $data['avatar'])) {
            $newPath = $uploadDir . $data['avatar'];
            rename($tempDir . $data['avatar'], $newPath); // Di chuyển file
        }
        // $this->fileHelper->uploadFile(
        //     $data['avatar'],
        //     $_FILES["new_avatar"],
        //     $uploadDir
        // );
        return $this->userRepo->create($data);
    }

    public function createFacebookUser(FacebookUserCreateRequest $user)
    {
        $data = $user->toArray();
        return $this->userRepo->create($data);

    }

    public function updateUser($id, UserUpdateRequest $user)
    {
        $data = $user->toArray();
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
        if (empty($data['email'])) {
            $errors['emailError'] = "Email cannot be blank";
        } else {
            if (strlen($data['email']) > 128) {
                $errors['emailError'] = "Email max length is 128";
            }
            if (!Validation::validateEmail(trim($data['email']))) {
                $errors['emailError'] = "The email format is incorrect";
            } else {
                $checkedId = $this->userRepo->existedEmail(trim($data['email']));
                if ($checkedId != $id) {
                    $errors['emailError'] = "Email is already existed";
                }
            }
        }
        if (empty($data['status'])) {
            $errors['statusError'] = "Status is required";
        }
        // var_dump($data['avatar']);
        // exit;
        // if (empty($data['avatar'])) {
        //     $errors['avatarError'] = "You have to choose avatar file";
        // }
        // if (strlen(trim($data['avatar'])) > 128) {
        //     $errors['avatarError'] = "Avatar name file max lenght is 128";
        // }
        // if (!$this->fileHelper->isImageFile($data['avatar'])) {
        //     $errors['avatarError'] = "Avatar must be png or jpg file";
        // }
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
        $tempDir = __DIR__ . "/../uploads/images/temp/";
        $uploadDir = __DIR__ . "/../uploads/images/avatar/";
        if (file_exists($tempDir . $data['avatar'])) {
            $newPath = $uploadDir . $data['avatar'];
            rename($tempDir . $data['avatar'], $newPath); // Di chuyển file

            $this->fileHelper->deleteFile($_POST['current_avatar'], $uploadDir);
        }
        $data["password"] = password_hash($data["password"], PASSWORD_DEFAULT);
        unset($data['passwordVerify']);
        return $this->userRepo->update($id, $data);
    }
    public function deleteUser($id)
    {
        $user = $this->userRepo->findById($id);
        if (empty($user)) {
            throw new Exception("Data does not exist");
        }
        return $this->userRepo->delete($id);
    }
}
?>