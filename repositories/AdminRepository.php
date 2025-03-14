<?php
require_once(dirname(__DIR__) ."/repositories/BaseRepository.php");
require_once(dirname(__DIR__) ."/models/Admin.php");

// require_once '../repositories/BaseRepository.php';
// require_once '../models/User.php';

class AdminRepository extends BaseRepository
{
    protected $table = "admins";
    protected $model = Admin::class;
}
