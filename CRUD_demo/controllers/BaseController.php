<?php

abstract class BaseController
{
    const VIEW_FOLDER_NAME = "views";
    public function view($viewPath, array $data = [])
    {
        foreach ($data as $key => $value) {
            $$key = $value;
        }
        require(self::VIEW_FOLDER_NAME . '/' .
            str_replace('.', '/', $viewPath) . '.php');
    }
    public function redirectError($errorMessage)
    {
        $errorPage = "error";
        $this->view($errorPage, ["errorMessage" => $errorMessage]);
    }
}