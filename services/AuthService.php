<?php
require_once(dirname(__DIR__) . "/utils/FileHelper.php");
require_once(dirname(__DIR__) . "/repositories/AuthTrait.php");


class AuthService
{
    use AuthTrait;
    public function __construct()
    {
        $this->initDB();
    }
    public function login(LoginRequest $request, $typeOfUser)
    {
        $auth = $this->findByEmail($request->getEmail(), $typeOfUser);
        if (empty($auth)) {
            throw new Exception("Email is not exists");
        }
        if ($auth['status'] === '2') {
            throw new Exception("You are banned");
        }
        if (password_verify($request->getPassword(), $auth['password'])) {
            return $auth;
        } else {
            throw new Exception("Wrong password");
        }
    }
    
}