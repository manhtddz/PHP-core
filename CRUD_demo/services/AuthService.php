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
    public function login(LoginRequest $request)
    {
        $auth = $this->findByEmail($request->getEmail());
        if (empty($auth)) {
            throw new Exception("Email ko tồn tại");
        }
        if (password_verify($request->getPassword(), $auth['password'])) {
            return $auth;
        } else {
            throw new Exception("Sai email hoặc mật khẩu");
        }
    }
}