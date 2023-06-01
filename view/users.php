<?php

require_once '../models/User.class.php';
require_once '../utils/PDOConnector.php';

$users = User::getAll();
$acao = 'create';
// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $users = new User();
    $users->setIdUser($_REQUEST['id'];
    //$users->load;

    // Validate form data
    if (empty($username) || empty($name) || empty($email) || empty($password) || empty($phone)) {
        $error = "All fields are required.";
    } else {
        
    }
}

// Close the database pdo$pdo
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Registration</title>
</head>
<body>
    <h2>Registro de usuário</h2>
    <?php if (isset($error)): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>
    <?php if (isset($success)): ?>
        <p style="color: green;"><?php echo $success; ?></p>
    <?php endif; ?>
    <form method="POST" action="">
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
    </form>
</body>
</html>


