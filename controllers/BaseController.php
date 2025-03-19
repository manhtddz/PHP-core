<?php

abstract class BaseController
{
    const VIEW_FOLDER_NAME = "views";
    protected $fileHelper;

    public function __construct()
    {
        $this->fileHelper = new FileHelper();
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
    protected function storeOldImage($newFile, $oldNameFile, $tempDir)
    {
        if (!empty($newFile) && $newFile["error"] === UPLOAD_ERR_OK) {
            $newTempFileName = time() . "_" . basename($newFile["name"]);
            if (
                $this->fileHelper->uploadFile(
                    $newTempFileName,
                    $newFile,
                    $tempDir
                )
            ) {
                if ($this->fileHelper->isImageFile($newTempFileName)) {
                    $_SESSION["temp_avatar"] = $newTempFileName;
                }
                $_POST['avatar'] = $newTempFileName;
                if (!empty($oldNameFile)) {
                    $this->fileHelper->deleteFile(basename($oldNameFile), $tempDir);
                }
            }
        } elseif (
            !empty($oldNameFile) &&
            $this->fileHelper->isImageFile($oldNameFile)
        ) {
            $_POST['avatar'] = $oldNameFile;
            $_SESSION["temp_avatar"] = $oldNameFile;

        } else {
            $_POST['avatar'] = '';
        }
    }
    // public function redirectError($errorMessage)
    // {
    //     $errorPage = "error";
    //     $this->view($errorPage, ["errorMessage" => $errorMessage]);
    // }
}