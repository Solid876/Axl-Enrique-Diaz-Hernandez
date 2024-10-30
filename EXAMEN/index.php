<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare('SELECT * FROM usuarios WHERE username = ? AND role = "admin"');
    $stmt->execute([$username]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['user'] = $admin['username'];
        $_SESSION['role'] = 'admin';
        header('Location: admin.php');
        exit;
    }

    $stmt = $pdo->prepare('SELECT * FROM usuarios WHERE username = ? AND role = "user"');
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = $user['username'];
        $_SESSION['role'] = 'user';
        header('Location: catalogo.php'); 
        exit;
    }

    $error = 'Credenciales incorrectas.';
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="css/cetis.css">
</head>
<body>
    <div class="container">
        <h1>Inicia Sesión</h1>
        <form method="POST">
            <input type="text" name="username" placeholder="Usuario" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <button type="submit">Entrar</button>
        </form>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <p><a href="register.php">¿No tienes una cuenta? Regístrate aquí.</a></p>
    </div>
</body>
</html>
