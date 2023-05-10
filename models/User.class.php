<?php
require_once '../utils/PDOConnector.php';

class User {
    private $pdo;
    private $idUser;
    private $username;
    private $email;
    private $password;

    public function __construct() {
        $this->pdo = new PDOConnector();
    }

    public function getIdUser() {
        return $this->idUser;
    }

    public function setIdUser($idUser) {
        $this->idUser = $idUser;
    }

    public function getUsername() {
        return $this->username;
    }

    public function setUsername($username) {
        $this->username = $username;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function save() {
        $pdo->executeSQL("INSERT INTO users (username, email, password) VALUES (:username, :email, sha1(md5(:password)))", [
            ':username' => $this->username,
            ':email' => $this->email,
            ':password' => $this->password
        ]);
    }

    public static function getAll() {
        $pdo = new PDOConnector();
        $pdo->query("SELECT * FROM user");
        return $pdo->getModelResult(get_class(new self));
    }
}