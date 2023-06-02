<?php
require_once '../models/User.class.php';

$user = new User();
$user->setUsername("john_doe");
$user->setName("John Doe");
$user->setMediaUrl("https://example.com/john_doe.jpg");
$user->setEmail("john.doe@example.com");
$user->setPassword("password123");
$user->setPhone("+123456789");
$user->create();

// Update the user
$user->setName("John Smith");
$user->setEmail("john.smith@example.com");
$user->update();

// Get user by ID
$retrievedUser = User::getById(1);
print_r($retrievedUser);

// Get all users
$allUsers = User::getAll();
print_r($allUsers);

// Add a role to the user
$user->addRole(1);

// Get the roles of the user
$userRoles = $user->getRoles();
print_r($userRoles);

// Remove a role from the user
$user->removeRole(1);

// Delete the user
$user->delete();