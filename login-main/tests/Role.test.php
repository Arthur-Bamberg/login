<?php
require_once '../models/Role.class.php';

$role = new Role();
$role->setName("Administrator");
$role->setDescription("Full access rights");
$role->create();

// Update the role
$role->setName("Super Administrator");
$role->setDescription("Extended access rights");
$role->update();

// Get roles for a user
$roles = Role::getByUser(1);
print_r($roles);

// Get all roles
$allRoles = Role::getAll();
print_r($allRoles);

// Delete the role
$role->delete();
$test = 1;