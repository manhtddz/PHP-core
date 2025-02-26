<?php
require_once(dirname(__DIR__) ."/repositories/BaseRepository.php");
require_once(dirname(__DIR__) ."/models/User.php");

// require_once '../repositories/BaseRepository.php';
// require_once '../models/User.php';

class UserRepository extends BaseRepository
{
    protected $table = "users";
    protected $model = User::class;
}
