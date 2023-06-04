<?php
require_once '../models/Role.class.php';
require_once '../controllers/RoleController.class.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $roleController = new RoleController();
    $roleController->createRole();
}
?>