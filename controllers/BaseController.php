<?php

abstract class BaseController
{
    const VIEW_FOLDER_NAME = "views";
    public function __construct()
    {
        session_start();
    }
    protected function view($viewPath, array $data = [])
    {
        foreach ($data as $key => $value) {
            $$key = $value;
        }
        require(self::VIEW_FOLDER_NAME . '/' .
            str_replace('.', '/', $viewPath) . '.php');
    }

    protected function checkLogin(array $role)
    {
        // if ($_SESSION['isOldLogin']) {
        //     session_destroy();
        //     session_start();
        //     $this->redirectWithError("?", "Another account logined!");
        //     exit;
        // }

        if (!in_array($_SESSION['auth']['role_type'], $role)) {
            $this->redirectWithError("?action=adminIndex", "You don't have permission!");
            exit;
        }
    }
    protected function redirectWithError(string $url, string $errorMessage = null): void
    {
        if ($errorMessage)
            $_SESSION['error'] = $errorMessage;
        header("Location: $url");
        exit;
    }
    protected function redirectWithErrors(string $url, array $errorMessage = []): void
    {
        if (!empty($errorMessage)) {
            $_SESSION['errors'] = $errorMessage;
        }
        header("Location: $url");
        exit;
    }
    protected function cleanInputData(array $data)
    {
        return array_map(fn($value) => htmlspecialchars(stripslashes(trim($value))), $data);
    }
    protected function cleanOneData(string $data)
    {
        return htmlspecialchars(stripslashes(trim($data)));
    }
    // public function redirectError($errorMessage)
    // {
    //     $errorPage = "error";
    //     $this->view($errorPage, ["errorMessage" => $errorMessage]);
    // }
}