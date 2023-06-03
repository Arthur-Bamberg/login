<?php
require_once '../models/User.class.php';

class UserController {
    private $parameters;
    private $getMethods = ['getUser', 'getAll'];

    public function __construct() {
        if($_SERVER['REQUEST_METHOD'] === 'GET') {
            $method = $_GET['method'];

            if (!in_array($method, $this->getMethods)) {
                throw new InvalidArgumentException('Invalid method');
            }
            
            unset($_GET['method']);

            $this->parameters = (object) $_GET;
        } else {
            $this->parameters = json_decode(file_get_contents('php://input'));
            $method = $this->parameters->method;
        }
        $this->$method();
    }

    public function createUser() {
        $user = new User();

        $user->setUsername($this->parameters->username);
        $user->setName($this->parameters->name);
        $user->setMediaUrl($this->parameters->mediaUrl);
        $user->setEmail($this->parameters->email);
        $user->setPassword($this->parameters->password);
        $user->setPhone($this->parameters->phone);
        $user->setIsActive(true);

        try {
            $user->create();
            echo $user->toJson();
        } catch (InvalidArgumentException $e) {
            // Handle any validation errors
            echo null;
        }
    }

    public function updateUser() {
        $user = User::getById($this->parameters->idUser);

        if ($user) {
            $user->setUsername($this->parameters->username);
            $user->setName($this->parameters->name);
            $user->setMediaUrl($this->parameters->mediaUrl);
            $user->setEmail($this->parameters->email);
            $user->setPassword($this->parameters->password);
            $user->setPhone($this->parameters->phone);

            try {
                $user->update();
                echo $user->toJson();
            } catch (InvalidArgumentException $e) {
                // Handle any validation errors
                echo json_encode($e);
            }
        }
    }

    public function deleteUser() {
        $user = User::getById($this->parameters->idUser);

        if ($user) {
            $user->delete();
            echo 'User deleted';
        } else {
            echo 'User not found';
        }
    }

    public function getUser() {
        echo json_encode(User::getById($this->parameters->idUser, true));
    }

    public function getAll() {
        echo json_encode(User::getAll());
    }

    public function addRoleToUser() {
        $user = User::getById($this->parameters->idUser);

        if ($user) {
            try {
                $user->addRole($this->parameters->idRole);
                echo $user->toJson();
            } catch (InvalidArgumentException $e) {
                // Handle any validation errors
                echo json_encode($e);
            }
        }

        echo 'User not found';
    }

    public function removeRoleFromUser() {
        $user = User::getById($this->parameters->idUser);

        if ($user) {
            $user->removeRole($this->parameters->idRole);
            return $user;
        }

        echo 'User not found';
    }
}

new UserController();
