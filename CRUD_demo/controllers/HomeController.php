<?php

require_once(dirname(__DIR__) . "/services/UserService.php");
require_once(dirname(__DIR__) . "/controllers/BaseController.php");
require_once(dirname(__DIR__) . "/dto/UserCreateRequest.php");
require_once(dirname(__DIR__) . "/dto/UserUpdateRequest.php");
require_once(dirname(__DIR__) . "/exceptions/ValidationException.php");

// require_once '../dto/UserRequest.php';

class HomeController extends BaseController
{
    // private $userService;
    public function __construct()
    {
        // $this->userService = new UserService();
    }
    public function index(){
        $this->view("logins.user");
    }
    public function adminIndex(){
        $this->view("logins.admin");
    }

    
}
?>