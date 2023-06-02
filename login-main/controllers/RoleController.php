<?php
require_once '../models/Role.class.php';

$acao = $_GET['acao'];


include_once '../model/Role.class.php';

//Cadastrar no banco
switch($acao){

case "create":
    //name mediaurl email password phone
    // cria um novo papel
    $role = new Role();
    $role->setName($_POST['nome']);
    $role->setDescription($_POST['description']);
    $role->setIsActive($_POST['isActive']);
    $role->create();
    header('Location:../view/roles.php ');
        //atualiza a tela
            $role = new Role();
            $role->setIdRole($_REQUEST['id']);
            Role::getAll();
    break;
case "read":
    // pega e mostra todos user$role
    $role = Role::getAll();
    break;
case "updade":
    //atualiza um papel cadastrado
    $role = new Role();
    $role->setIdRole($_POST['id']);
    $role->setName($_POST['nome']);
    $role->setDescription($_POST['description']);
    $role->setIsActive($_POST['IsActive']);
    $role->update();
    header('Location:../view/roles.php ');
        //atualiza a tela
            $role = new Role();
            $role->setIdRole($_REQUEST['id']);
            $role->getAll();
    break;
case "delete":
    //deleta um papel
    $role->delete($_REQUEST['id']);
    header('Location:../view/roles.php ');
        //atualiza a tela
            $role = new Role();
            $role->setIdRole($_REQUEST['id']);
            $role->getAll();
    break;
}



$test = 1;  



?>