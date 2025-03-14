<?php

class ValidationException extends Exception
{
    private array $errors;
    public function __construct(array $errors, $message = "Dữ liệu không hợp lệ", $code = 0)
    {
        parent::__construct($message, $code);
        $this->errors = $errors;
    }
    public function getErrors(): array
    {
        return $this->errors;
    }
}