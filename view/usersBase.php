<?php

require_once '../models/User.class.php';
require_once '../utils/PDOConnector.php';

$users = User::getAll();
$acao = 'create';
// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $users = new User();
    $users->setIdUser($_REQUEST['id']);
    //$users->load;

        if(isset($_GET['id'])){
            $users = new User();
            $users->setIdUser($_REQUEST['id']);
    
            $acao = 'update';
    
        }else{
            $users = new User();
        }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">

    <style>
        :root {
            --bs-table-striped-color: pink;
}
    </style>
</head>

<body>
    <form action="../controllers/UserController.php?acao=<?= $acao ?>" method="POST">
    <label>Nome de usuário (nickname):</label>
        <input type="text" name="nome" required><br><br>
        <label>Nome completo:</label>
        <input type="text" name="nome" required><br><br>
        <label>Foto de perfil:</label>
        <input type="image" name="foto"><br><br>
        <label>Email:</label>
        <input type="email" name="email" required><br><br>
        <label>Senha:</label>
        <input type="password" name="senha" required><br><br>
        <label>Telefone:</label>
        <input type="tel" name="phone" required><br><br>
        <input type="submit" value="Register">

        <input type="hidden" name="id" value="<?= $role->getIdUser()?>" />

        <input type="submit" value="Enviar" />

    </form>



    <table class="table table-striped">
        <thead>
            <tr>
                <th>Id</th>
                <th>Nome</th>
                <th>Ações</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach($roles as $role){ ?>
            <tr>
                <td>
                    <?php echo $role->getIdUser() ?>
                </td>
                <td>
                    <?= $role->getNome() ?>
                </td>
                <td>
                    <a href="../controllers/role.php?acao=delete&id=<?= $role->getIdUser() ?>" class="btn btn-danger">Excluir</a>
                    <a href="?id=<?= $role->getIdUser() ?>" class="btn btn-success">Editar</a>

                </td>
            </tr>
            <?php } ?>        
        </tbody>
    </table>

</body>

</html>

