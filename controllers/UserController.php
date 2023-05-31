<?php
require_once '../models/User.class.php';

$acao = $_GET['acao'];


include_once '../model/Filme.class.php';

//Cadastrar no banco
switch($acao){

case "create":
    // cria um novo usuário
    $user = new User();
    $user->setNome($_POST['nome']);
    $user->save();
    header('Location:../view/filme_mpa.php ');
        //atualiza a tela
            $user = new Filme();
            $user->setID($_REQUEST['id']);
            $user->load();
    break;
case "read":
    // pega e mostra todos user$user
    $user = User::getAll();
    break;
case "updade":
    //atualiza um usuário cadastrado
    $user = new Filme();
    $user->setId($_POST['id']);
    $user->setNome($_POST['nome']);
    $user->update();
    header('Location:../view/filme_mpa.php ');
        //atualiza a tela
            $user = new Filme();
            $user->setID($_REQUEST['id']);
            $user->load();
    break;
case "delete":
    //deleta um usuário
    Filme::deletar($_REQUEST['id']);
    header('Location:../view/filme_mpa.php ');
        //atualiza a tela
            $user = new Filme();
            $user->setID($_REQUEST['id']);
            $user->load();
    break;
}



$test = 1;  