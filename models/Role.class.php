<?php
require_once '../utils/PDOConnector.php';

class Role {
    private $pdo;
    private $idRole;
    private $name;
    private $description;
    private $isActive;

    public function __construct() {
        $this->pdo = new PDOConnector();
    }

    public function getIdRole() {
        return $this->idRole;
    }

    public function setIdRole($idRole) {
        if (is_int($idRole) && $idRole > 0) {
            $this->idRole = $idRole;
        } else {
            throw new InvalidArgumentException("Invalid idRole value");
        }
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        if (is_string($name) && !empty($name)) {
            $this->name = $name;
        } else {
            throw new InvalidArgumentException("Invalid name value");
        }
    }

    public function getDescription() {
        return $this->description;
    }

    public function setDescription($description) {
        if (is_string($description) && !empty($description)) {
            $this->description = $description;
        } else {
            throw new InvalidArgumentException("Invalid description value");
        }
    }

    public function getIsActive() {
        return $this->isActive;
    }

    public function setIsActive($isActive) {
        if (is_bool($isActive)) {
            $this->isActive = $isActive;
        } else {
            throw new InvalidArgumentException("Invalid isActive value");
        }
    }

    public function create() {
        $this->pdo->query(
            "INSERT INTO role (name, description) 
                VALUES (:name, :description)", [
            ':name' => $this->name,
            ':description' => $this->description,
        ]);

        $this->setIdRole($this->pdo->getLastInsertedId());
    }

    public function update() {
        $this->pdo->query(
            "UPDATE role 
                SET name = :name, description = :description
            WHERE idRole = :idRole", [
            ':name' => $this->name,
            ':description' => $this->description,
            ':idRole' => $this->idRole
        ]);
    }

    public function delete() {
        $this->pdo->query("UPDATE role SET isActive = 0 WHERE idRole = :idRole", [
            ':idRole' => $this->idRole
        ]);
    }

    public static function getByUser($idUser, $objectMode = false) {
        $pdo = new PDOConnector();
        $pdo->query(
            "SELECT * 
                FROM role
                    inner join user_role 
                        on role.idRole = user_role.FK_idRole
                    inner join user
                        on user_role.FK_idUser = user.idUser
            WHERE
                user.idUser = :idUser
                and user.isActive = 1
                and role.isActive = 1",
            [
                ':idUser' => $idUser
            ]
        );
        if($objectMode) {
            return $pdo->getObjectResult();
        }

        return $pdo->getModelResult(get_class(new self))[0];
    }

    public static function getById($idRole) {
        $pdo = new PDOConnector();
        $pdo->query(
            "SELECT * 
                FROM role
            WHERE
                role.isActive = 1",
            [
                ':idRole' => $idRole
            ]
        );
        return $pdo->getModelResult(get_class(new self));
    }

    public static function getAll() {
        $pdo = new PDOConnector();
        $pdo->query(
            "SELECT * 
                FROM role
            WHERE isActive = 1"
        );
        return $pdo->getModelResult(get_class(new self));
    }
}       
