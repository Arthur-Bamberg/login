<?php
require_once '../models/User.class.php';

$acao = $_GET['acao'];


include_once '../model/User.class.php';

//Cadastrar no banco
switch($acao){

case "create":
    //name mediaurl email password phone
    // cria um novo usuário
    $user = new User();
    $user->setUsername($_POST['nickname']);
    $user->setName($_POST['nome']);
    $user->setMediaUrl($_POST['foto']);
    $user->setEmail($_POST['email']);
    $user->setPassword($_POST['senha']);
    $user->setPhone($_POST['telefone']);
    $user->create();
    header('Location:../view/users.php ');
        //atualiza a tela
            $user = new User();
            $user->setIdUser($_REQUEST['id']);
            User::getAll();
    break;
case "read":
    // pega e mostra todos user$user
    $user = User::getAll();
    break;
case "updade":
    //atualiza um usuário cadastrado
    $user = new User();
    $user->setIdUser($_POST['id']);
    $user->setUsername($_POST['nickname']);
    $user->setName($_POST['nome']);
    $user->setMediaUrl($_POST['foto']);
    $user->setEmail($_POST['email']);
    $user->setPassword($_POST['senha']);
    $user->setPhone($_POST['telefone']);
    $user->update();
    header('Location:../view/users.php ');
        //atualiza a tela
            $user = new User();
            $user->setIdUser($_REQUEST['id']);
            $user->getAll();
    break;
case "delete":
    //deleta um usuário
    $user->delete($_REQUEST['id']);
    header('Location:../view/users.php ');
        //atualiza a tela
            $user = new User();
            $user->setIdUser($_REQUEST['id']);
            $user->getAll();
    break;
}



$test = 1;  