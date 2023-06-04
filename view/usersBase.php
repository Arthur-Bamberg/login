<?php
require_once '../models/User.class.php';
require_once '../controllers/UserController.class.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userController = new UserController();
    $userController->createUser();
}
?>