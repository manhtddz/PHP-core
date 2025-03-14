<?php
require_once(dirname(__DIR__) . "/utils/FileHelper.php");


abstract class BaseService
{
    protected $fileHelper;
    public function __construct()
    {
        $this->fileHelper = new FileHelper();
    }
}