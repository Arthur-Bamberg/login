<?php
require_once '../models/Role.class.php';

class RoleController {
    private $parameters;
    private $getMethods = ['getRole', 'getAll'];

    public function __construct() {
        if($_SERVER['REQUEST_METHOD'] === 'GET') {
            $method = $_GET['method'];
            unset($_GET['method']);

            if (!in_array($method, $this->getMethods)) {
                throw new InvalidArgumentException('Invalid method');
            }

            $this->parameters = (object) $_GET;
        } else {
            $this->parameters = json_decode(file_get_contents('php://input'));
            $method = $this->parameters->method;
        }
        $this->$method();
    }

    public function createRole() {
        $role = new Role();

        $role->setName($this->parameters->name);
        $role->setDescription($this->parameters->description);
        $role->setIsActive(true);

        try {
            $role->create();
            echo $role->toJson();
        } catch (InvalidArgumentException $e) {
            echo json_encode($e);
        }
    }

    public function updateRole() {
        $role = Role::getById($this->parameters->idRole);

        if ($role) {
            $role->setName($this->parameters->name);
            $role->setDescription($this->parameters->description);

            try {
                $role->update();
                echo $role->toJson();
            } catch (InvalidArgumentException $e) {
                echo json_encode($e);
            }
        }
    }

    public function deleteRole() {
        $role = Role::getById($this->parameters->idRole);

        if ($role) {
            $role->delete();
            echo 'Role deleted';
        } else {
            echo 'Role not found';
        }
    }

    public function getRole() {
        echo json_encode(Role::getById($this->parameters->idRole, true));
    }

    public function getAll() { 
        echo json_encode(Role::getAll());
    }
}

new RoleController();