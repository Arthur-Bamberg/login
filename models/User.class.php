<?php
require_once '../utils/PDOConnector.php';
require_once 'Role.class.php';

class User {
    private $pdo;
    private $idUser;
    private $username;
    private $name;
    private $mediaUrl;
    private $email;
    private $password;
    private $phone;
    private $safeParameters = "idUser, username, name, mediaUrl, email, phone";
    private $roles = [];
    private $isActive;

    public function __construct() {
        $this->pdo = new PDOConnector();
    }

    public function getIdUser() {
        return $this->idUser;
    }

    public function setIdUser($idUser) {
        if (!is_int($idUser) || $idUser <= 0) {
            throw new InvalidArgumentException('Invalid value for idUser');
        }
        $this->idUser = $idUser;
    }

    public function getUsername() {
        return $this->username;
    }

    public function setUsername($username) {
        if (!is_string($username) || empty($username)) {
            throw new InvalidArgumentException('Invalid value for username');
        }
        $this->username = $username;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        if (!is_string($name) || empty($name)) {
            throw new InvalidArgumentException('Invalid value for name');
        }
        $this->name = $name;
    }

    public function getMediaUrl() {
        return $this->mediaUrl;
    }

    public function setMediaUrl($mediaUrl) {
        if (!filter_var($mediaUrl, FILTER_VALIDATE_URL) && !empty($mediaUrl)) {
            throw new InvalidArgumentException('Invalid value for mediaUrl');
        }
        $this->mediaUrl = $mediaUrl;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Invalid value for email');
        }
        $this->email = $email;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setPassword($password) {
        if (strlen($password) < 8) {
            throw new InvalidArgumentException('Invalid value for password');
        }
        $this->password = $password;
    }

    public function getPhone() {
        return $this->phone;
    }

    public function setPhone($phone) {
        if (!preg_match('/^\+?\d{1,3}[-.\s]?\(?\d{1,3}\)?[-.\s]?\d{1,4}[-.\s]?\d{1,4}$/', $phone)) {
            throw new InvalidArgumentException('Invalid value for phone');
        }
        $this->phone = $phone;
    }

    public function getIsActive() {
        return $this->isActive;
    }

    public function setIsActive($isActive) {
        if (!is_bool($isActive)) {
            throw new InvalidArgumentException('Invalid value for isActive');
        }
        $this->isActive = $isActive;
    }

    public function getRoles() {
        if(empty($this->roles)) {
            $this->pdo->query(
                "SELECT 
                    role.idRole, role.name, role.description, role.isActive
                FROM role
                    INNER JOIN user_role 
                        ON role.idRole = user_role.idRole
                WHERE user_role.idUser = :idUser",
                [':idUser' => $this->idUser]
            );

            $this->roles = $this->pdo->getModelResult('Role');
        }

        return $this->roles;
    }

    public function addRole($idRole) {
        $this->pdo->query(
            "INSERT INTO user_role (FK_idRole, FK_idUser, isActive)
                VALUES (:idRole, :idUser, 1)
            ON DUPLICATE KEY UPDATE
                isActive = 1", 
        [
            ':idRole' => $idRole,
            ':idUser' => $this->idUser
        ]);

        $this->setIdUser($this->pdo->getLastInsertedId());
    }

    public function removeRole($idRole) {
        $this->pdo->query(
            "UPDATE user_role 
            SET isActive = 0
            WHERE FK_idRole = :idRole
            AND FK_idUser = :idUser", 
        [
            ':idRole' => $idRole,
            ':idUser' => $this->idUser
        ]);
    }

    public function create() {
        $this->pdo->query("INSERT INTO user (username, name, mediaUrl, email, password, phone) VALUES (:username, :name, :mediaUrl, :email, sha1(md5(:password)), :phone)", [
            ':username' => $this->username,
            ':name' => $this->name,
            ':mediaUrl' => $this->mediaUrl,
            ':email' => $this->email,
            ':password' => $this->password,
            ':phone' => $this->phone
        ]);

        $this->setIdUser($this->pdo->getLastInsertedId());
    }

    public function update() {
        $this->pdo->query("UPDATE user SET username = :username, name = :name, mediaUrl = :mediaUrl, email = :email, password = sha1(md5(:password)), phone = :phone WHERE idUser = :idUser", [
            ':username' => $this->username,
            ':name' => $this->name,
            ':mediaUrl' => $this->mediaUrl,
            ':email' => $this->email,
            ':password' => $this->password,
            ':phone' => $this->phone,
            ':idUser' => $this->idUser
        ]);
    }

    public function delete() {
        $this->pdo->query("UPDATE user SET isActive = 0 WHERE idUser = :idUser", [
            ':idUser' => $this->idUser
        ]);
    }

    public function toJson() {
        $data = array(
            'idUser' => $this->idUser,
            'username' => $this->username,
            'name' => $this->name,
            'mediaUrl' => $this->mediaUrl,
            'email' => $this->email,
            'password' => $this->password,
            'phone' => $this->phone,
            'isActive' => $this->isActive
        );

        $roles = array();
        foreach ($this->roles as $role) {
            $roles[] = $role->toJson();
        }
        $data['roles'] = $roles;

        return json_encode($data);
    }

    public static function getById($idUser, $objectMode = false) {
        $pdo = new PDOConnector();
        $pdo->query(
            "SELECT * 
                FROM user
            WHERE idUser = :idUser
            AND isActive = 1",
            [
                ':idUser' => $idUser
            ]
        );

        if($objectMode) {
            return $pdo->getObjectResult()[0];
        }

        return $pdo->getModelResult(get_class(new self))[0];
    }

    public static function getAll() {
        $pdo = new PDOConnector();
        $pdo->query(
            "SELECT * 
                FROM user
            WHERE isActive = 1"
        );

        return $pdo->getObjectResult();
    }
}